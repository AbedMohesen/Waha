@extends('front.layout')

@section('title', 'البحث في السجل | واحة الشهداء')

@section('content')
    <section class="oasis-band">
        <div class="oasis-container py-12 sm:py-16">
            <p class="oasis-kicker !text-oasis-gold">السجل الوطني</p>
            <h1 class="mt-3 max-w-2xl text-4xl font-semibold leading-tight text-white sm:text-5xl">ابحث في السجل بهدوء ووضوح.</h1>
            <p class="mt-4 max-w-2xl text-sm leading-8 text-white/70 sm:text-base">اكتب الاسم العربي أو الرقم الوطني، وستظهر لك السجلات المطابقة.</p>
            <form id="search_form" class="mt-8 max-w-3xl rounded-xl bg-white p-3 shadow-[var(--oasis-card-shadow)] sm:flex sm:items-center sm:gap-3">
                <label class="sr-only" for="search_input">الاسم أو الرقم الوطني</label>
                <div class="relative flex-1"><i class="fa-solid fa-magnifying-glass absolute right-4 top-1/2 -translate-y-1/2 text-oasis-accent" id="search_icon"></i><input id="search_input" class="oasis-input !mt-0 w-full !rounded-lg !border-0 !py-3.5 pr-11" type="search" autocomplete="off" placeholder="اكتب الاسم أو الرقم الوطني" required></div>
                <button class="oasis-button oasis-button-primary mt-3 w-full sm:mt-0 sm:w-auto" type="submit">بحث</button>
            </form>
        </div>
    </section>

    <section class="oasis-container oasis-section">
        <div id="search_status" class="min-h-0" aria-live="polite"></div>
        <div class="mt-6 flex flex-col gap-3 border-b border-black/10 pb-5 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="oasis-heading">نتائج البحث</h2>
            <p id="results_count" class="text-sm font-semibold text-black/55"></p>
        </div>
        <div id="results_list" class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3"></div>
        <nav id="pagination_container" class="mt-8 flex flex-wrap justify-center gap-2" aria-label="ترقيم نتائج البحث"></nav>
        <div id="initial_state" class="oasis-empty mt-6"><i class="fa-solid fa-magnifying-glass mb-3 text-2xl text-oasis-accent"></i><p class="font-bold text-oasis-house">ابدأ بالبحث في السجل</p><p class="mt-2">ستظهر النتائج هنا مع إمكانية الانتقال إلى صفحة كل سجل.</p></div>
    </section>
@endsection

@section('js')
<script>
    (() => {
        const form = document.getElementById('search_form');
        const input = document.getElementById('search_input');
        const status = document.getElementById('search_status');
        const results = document.getElementById('results_list');
        const count = document.getElementById('results_count');
        const pagination = document.getElementById('pagination_container');
        const initialState = document.getElementById('initial_state');
        const icon = document.getElementById('search_icon');
        let currentQuery = '';

        const message = (text, kind = 'info') => {
            status.innerHTML = '';
            const item = document.createElement('p');
            item.className = kind === 'error' ? 'oasis-alert-error' : 'oasis-alert-success';
            item.textContent = text;
            status.appendChild(item);
        };

        const setLoading = (loading) => {
            icon.className = loading ? 'fa-solid fa-spinner fa-spin absolute right-4 top-1/2 -translate-y-1/2 text-oasis-accent' : 'fa-solid fa-magnifying-glass absolute right-4 top-1/2 -translate-y-1/2 text-oasis-accent';
        };

        const createResult = (record) => {
            const link = document.createElement('a');
            link.href = `{{ route('martyr', [], false) }}/${record.id}`;
            link.className = 'oasis-card group flex min-h-40 flex-col justify-between p-6 transition hover:-translate-y-0.5 hover:bg-oasis-mint/30';
            const icon = document.createElement('span');
            icon.className = 'grid h-10 w-10 place-items-center rounded-full bg-oasis-cream text-oasis-green';
            icon.innerHTML = '<i class="fa-solid fa-book-open"></i>';
            const name = document.createElement('h3');
            name.className = 'mt-5 text-base font-bold text-oasis-house';
            name.textContent = record.name_ar || 'سجل بدون اسم';
            const action = document.createElement('span');
            action.className = 'mt-4 text-xs font-bold text-oasis-accent';
            action.textContent = 'عرض السجل ←';
            link.append(icon, name, action);
            return link;
        };

        const renderPagination = (data) => {
            pagination.innerHTML = '';
            if (data.last_page <= 1) return;
            const addButton = (label, page, disabled = false, active = false) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.disabled = disabled;
                button.textContent = label;
                button.className = active ? 'oasis-button oasis-button-primary min-w-11 !px-3' : 'oasis-button oasis-button-outline min-w-11 !px-3 disabled:cursor-not-allowed disabled:opacity-40';
                if (!disabled && !active) button.addEventListener('click', () => fetchData(currentQuery, page));
                pagination.appendChild(button);
            };
            addButton('السابق', data.current_page - 1, data.current_page === 1);
            const start = Math.max(1, data.current_page - 2);
            const end = Math.min(data.last_page, data.current_page + 2);
            for (let page = start; page <= end; page++) addButton(String(page), page, false, page === data.current_page);
            addButton('التالي', data.current_page + 1, data.current_page === data.last_page);
        };

        async function fetchData(query, page = 1) {
            status.innerHTML = '';
            results.innerHTML = '';
            pagination.innerHTML = '';
            initialState.hidden = true;
            setLoading(true);
            try {
                const response = await fetch(`{{ route('search', [], false) }}?q=${encodeURIComponent(query)}&page=${page}`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'تعذر تنفيذ البحث حاليًا.');
                count.textContent = `${data.total || 0} نتيجة`;
                if (!data.total) message('لم نعثر على سجلات مطابقة. جرّب كتابة اسم مختلف أو الرقم الوطني.', 'info');
                else message('تم العثور على سجلات مطابقة لبحثك.', 'info');
                (data.data || []).forEach((record) => results.appendChild(createResult(record)));
                renderPagination(data);
            } catch (error) {
                count.textContent = '';
                message(error.message || 'حدث خطأ أثناء جلب النتائج. يرجى المحاولة لاحقًا.', 'error');
            } finally {
                setLoading(false);
            }
        }

        form.addEventListener('submit', (event) => {
            event.preventDefault();
            const query = input.value.trim();
            if (!query) {
                message('يرجى إدخال الاسم أو الرقم الوطني للبدء بالبحث.', 'error');
                input.focus();
                return;
            }
            currentQuery = query;
            fetchData(query);
        });

        const urlParams = new URLSearchParams(window.location.search);
        const initialQ = urlParams.get('q');
        if (initialQ && initialQ.trim()) {
            input.value = initialQ.trim();
            currentQuery = initialQ.trim();
            fetchData(initialQ.trim());
        }
    })();
</script>
@endsection
