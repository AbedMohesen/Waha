<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="oasis-kicker">إدارة السجلات</p>
                <h1 class="oasis-heading mt-1">سجلات الشهداء</h1>
            </div>

            <a href="{{ route('dashboard.martyr.create') }}" class="oasis-button oasis-button-primary text-xs">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>إضافة سجل جديد</span>
            </a>
        </div>
    </x-slot>

    <div class="oasis-container py-8 sm:py-10">
        <!-- Search Section: White Card -->
        <section class="oasis-card p-6 sm:p-8">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-oasis-mint text-oasis-green text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <h2 class="text-base font-bold text-oasis-house">بحث في سجلات الإدارة</h2>
            </div>

            <form id="search_form" class="relative">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute right-4 top-1/2 -translate-y-1/2 text-oasis-accent search_icon"></i>
                    <input autocomplete="off" type="search" name="q" placeholder="اكتب اسم الشهيد أو الرقم الوطني (3 أحرف على الأقل)..."
                        class="oasis-input !py-3.5 pr-11 text-sm sm:text-base search_input">
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="oasis-button oasis-button-primary search_btn text-xs">
                        <span>تنفيذ البحث</span>
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                    </button>
                </div>
            </form>
        </section>

        <!-- Status & Results Section -->
        <section class="mt-8">
            <!-- Status Messages -->
            <div class="wrapper_navigation mb-5"></div>

            <!-- Counter Result -->
            <div class="wrapper_count mb-6 text-center sm:text-right text-base font-semibold text-oasis-house"></div>

            <!-- Items Grid -->
            <div class="wrapper_list grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5"></div>

            <!-- Pagination Container -->
            <div id="pagination_container" class="flex justify-center items-center gap-2 mt-8 flex-wrap"></div>
        </section>
    </div>

    <x-slot name="js">
    <script>
        const search_form = document.getElementById('search_form');
        const search_btn = document.querySelector('.search_btn');
        const input = document.querySelector('.search_input');
        const wrapper_count = document.querySelector('.wrapper_count');
        const wrapper_list = document.querySelector('.wrapper_list');
        const wrapper_navigation = document.querySelector('.wrapper_navigation');
        const pagination_container = document.getElementById('pagination_container');
        const search_icon = document.querySelector('.search_icon');

        let currentQuery = '';

        search_form.onsubmit = function (e) {
            e.preventDefault();
            let text = input.value.trim();
            if (!text || text.length < 3) {
                wrapper_navigation.innerHTML = '';
                wrapper_navigation.insertAdjacentHTML('afterbegin', `<div class="oasis-alert-error text-center text-sm font-semibold">يرجى إدخال 3 أحرف على الأقل (الاسم أو الرقم الوطني) للبدء بالبحث.</div>`);
                return;
            }

            currentQuery = text;
            fetchData(text, 1);
        }

        function fetchData(text, page = 1) {
            wrapper_navigation.innerHTML = '';
            search_icon.classList.remove('fa-magnifying-glass');
            search_icon.classList.add('fa-spinner', 'fa-spin');

            wrapper_list.innerHTML = '';
            wrapper_count.innerHTML = '';
            pagination_container.innerHTML = '';

            fetch(`{{ route('search') }}?q=${encodeURIComponent(text)}&page=${page}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(res => {
                    if (!res.ok) throw new Error('تعذر تنفيذ الطلب');
                    return res.json();
                })
                .then(data => {
                    let totalCount = data.total || 0;
                    wrapper_count.innerHTML = `تم العثور على <span class="oasis-pill-mint font-bold mx-1">${totalCount}</span> سجل`;

                    if (totalCount > 0) {
                        wrapper_navigation.innerHTML = `<div class="oasis-alert-success text-center text-xs font-semibold">تم العثور على سجلات مطابقة لمعايير البحث.</div>`;
                    } else {
                        wrapper_navigation.innerHTML = `<div class="oasis-empty text-center">لم يتم العثور على بيانات تطابق مدخلات البحث. جرّب كتابة اسم مختلف.</div>`;
                    }

                    let itemsData = data.data || [];
                    itemsData.forEach((d) => {
                        let url_show = `{{ url('/dashboard/martyr') }}/${d.id}`;
                        let url_edit = `{{ url('/dashboard/martyr') }}/${d.id}/edit`;
                        let url_delete = `{{ url('/dashboard/martyr') }}/${d.id}`;
                        let item = `
                        <article class="oasis-card p-5 flex flex-col justify-between hover:-translate-y-0.5">
                            <div class="flex items-start gap-3">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-oasis-mint text-oasis-green font-bold text-sm">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-bold text-base text-oasis-house truncate">${d.name_ar}</h3>
                                    <p class="text-xs text-black/50 mt-1">الرقم الوطني: ${d.national_id || 'غير مسجل'}</p>
                                </div>
                            </div>

                            <div class="mt-5 pt-4 border-t border-black/5 flex flex-wrap items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <a href="${url_show}" class="oasis-button oasis-button-primary text-xs !min-h-9 !px-3.5">
                                        <span>عرض</span>
                                    </a>
                                    <a href="${url_edit}" class="oasis-button oasis-button-outline text-xs !min-h-9 !px-3.5">
                                        <span>تعديل</span>
                                    </a>
                                </div>
                                <form action="${url_delete}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل نهائيًا؟')">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="oasis-button oasis-button-danger text-xs !min-h-9 !px-3">
                                        <span>حذف</span>
                                    </button>
                                </form>
                            </div>
                        </article>`;
                        wrapper_list.insertAdjacentHTML('beforeend', item);
                    });

                    if (data.last_page > 1) {
                        renderPagination(data);
                    }

                    search_icon.classList.add('fa-magnifying-glass');
                    search_icon.classList.remove('fa-spinner', 'fa-spin');
                })
                .catch((error) => {
                    console.error('Error:', error);
                    wrapper_navigation.innerHTML = `<div class="oasis-alert-error text-center text-xs">حدث خطأ أثناء جلب البيانات، يرجى المحاولة لاحقاً.</div>`;
                    search_icon.classList.add('fa-magnifying-glass');
                    search_icon.classList.remove('fa-spinner', 'fa-spin');
                });
        }

        function renderPagination(paginationData) {
            const currentPage = paginationData.current_page;
            const lastPage = paginationData.last_page;
            let html = '';

            if (currentPage > 1) {
                html += `<button onclick="fetchData('${currentQuery}', ${currentPage - 1})" class="oasis-button oasis-button-outline text-xs !min-h-9 !px-3">السابق</button>`;
            } else {
                html += `<button disabled class="oasis-button oasis-button-outline text-xs !min-h-9 !px-3 opacity-40 cursor-not-allowed">السابق</button>`;
            }

            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(lastPage, currentPage + 2);

            for (let i = startPage; i <= endPage; i++) {
                if (i === currentPage) {
                    html += `<button class="oasis-button oasis-button-primary text-xs !min-h-9 !w-9 !p-0">${i}</button>`;
                } else {
                    html += `<button onclick="fetchData('${currentQuery}', ${i})" class="oasis-button oasis-button-outline text-xs !min-h-9 !w-9 !p-0">${i}</button>`;
                }
            }

            if (currentPage < lastPage) {
                html += `<button onclick="fetchData('${currentQuery}', ${currentPage + 1})" class="oasis-button oasis-button-outline text-xs !min-h-9 !px-3">التالي</button>`;
            } else {
                html += `<button disabled class="oasis-button oasis-button-outline text-xs !min-h-9 !px-3 opacity-40 cursor-not-allowed">التالي</button>`;
            }

            pagination_container.innerHTML = html;
        }
    </script>
    </x-slot>
</x-app-layout>
