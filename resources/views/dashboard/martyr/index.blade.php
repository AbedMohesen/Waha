<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
            {{ __('Martyr') }}
        <a href="{{ route('dashboard.martyr.create') }}"><button class="p-2 px-4 rounded-full bg-green-600 text-white">Add Martyr</button></a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <main class="py-4 sm:py-5 lg:py-7">

            <!-- Search Form Section -->
            <section id="search-section" class="max-w-5xl mx-auto scroll-mt-24">
                <h2 class="text-base sm:text-lg lg:text-xl font-semibold text-gray-700 mb-4 sm:mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass text-emerald-700"></i>
                    <span>ابحث عن الشهيد</span>
                </h2>

                <form class="relative" id="search_form">
                    <input autocomplete="off" type="search" name="q" placeholder="اكتب اسم الشهيد أو رقم الهوية..."
                        class="w-full h-12 sm:h-14 lg:h-16 rounded-2xl bg-white border border-gray-200 shadow-sm pr-4 sm:pr-5 pl-12 sm:pl-14 text-sm sm:text-base text-gray-700 placeholder:text-gray-400 transition duration-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 focus:outline-none search_input">
                    <button type="submit" aria-label="بحث"
                        class="search_btn absolute left-2 sm:left-3 top-1/2 -translate-y-1/2 h-9 w-9 sm:h-10 sm:w-10 rounded-xl flex items-center justify-center text-gray-500 hover:bg-emerald-100 hover:text-emerald-700 transition">
                        <i class="fa-solid fa-magnifying-glass text-base sm:text-xl search_icon"></i>
                    </button>
                </form>

                <div class="border-b border-gray-200 mt-6 sm:mt-8">

                </div>
            </section>

            <!-- Dynamic Search Results Section -->
            <section class="max-w-5xl mx-auto mt-6">
                <!-- Status Messages & Navigation Alerts -->
                <div class="wrapper_navigation m-2"></div>

                <!-- Counter Result -->
                <div class="wrapper_count text-center text-xl sm:text-2xl my-5 text-gray-700 font-medium"></div>

                <!-- Items Grid -->
                <div class="wrapper_list grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">

                </div>

                <!-- Pagination Container -->
                <div id="pagination_container" class="flex justify-center items-center gap-2 mt-8 flex-wrap"></div>
            </section>

        </main>
        </div>
    </div>
    <x-slot name="js">
<script>
        // --- عناصر الواجهة ---
        const search_form = document.getElementById('search_form');
        const search_btn = document.querySelector('.search_btn');
        const input = document.querySelector('.search_input');
        const wrapper_count = document.querySelector('.wrapper_count');
        const wrapper_list = document.querySelector('.wrapper_list');
        const wrapper_navigation = document.querySelector('.wrapper_navigation');
        const pagination_container = document.getElementById('pagination_container');
        const search_icon = document.querySelector('.search_icon');

        let currentQuery = '';

        // --- حدث البحث عند التقديم ---
        search_form.onsubmit = function (e) {
            e.preventDefault();
            let text = input.value.trim();
            if (!text || text.length < 3) {
                wrapper_navigation.innerHTML = '';
                wrapper_navigation.insertAdjacentHTML('afterbegin', `<p class="bg-amber-100 border border-amber-300 text-amber-800 text-center p-3 rounded-xl text-sm font-medium">الرجاء إدخال الاسم أو رقم الهوية للبدء بالبحث</p>`);
                return;
            }

            currentQuery = text;
            fetchData(text, 1);
        }

        // --- دالة جلب البيانات عبر AJAX (Fetch) ---
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
                    'X-Requested-With': 'XMLHttpRequest', // ترويسة هامة للتحقق من طلب الـ AJAX في لارافيل
                    'Accept': 'application/json'          // تحديد صيغة الاستجابة المطلوبة
                }
            })
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return res.json();
                })
                .then(data => {
                    let totalCount = data.total || 0;
                    wrapper_count.insertAdjacentHTML('afterbegin', `عدد نتائج البحث: (<span class="text-emerald-700 font-bold">${totalCount}</span>)`);

                    if (totalCount > 0) {
                        wrapper_navigation.insertAdjacentHTML('afterbegin', `<p class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-center p-2.5 rounded-xl text-sm font-medium">تم العثور على نتائج المطابقة</p>`);
                    } else {
                        wrapper_navigation.insertAdjacentHTML('afterbegin', `<p class="bg-gray-100 border border-gray-200 text-gray-600 text-center p-3 rounded-xl text-sm">لم يتم العثور على بيانات تطابق مدخلات البحث...</p>`);
                    }

                    // عرض بطاقات النتائج
                    let itemsData = data.data || [];
                    itemsData.forEach((d) => {
                        let url = `{{ url('/martyr') }}/${d.id}`;
                        let url_show = `{{ url('/dashboard/martyr') }}/${d.id}`;
                        let url_edit = `{{ url('/dashboard/martyr') }}/${d.id}/edit`;
                        let url_delete = `{{ url('/dashboard/martyr') }}/${d.id}`;
                        let item = `<div
                        class="cursor-pointer bg-white hover:bg-emerald-50/50 border border-gray-200 hover:border-emerald-300 p-5 text-center rounded-2xl text-slate-800 hover:text-emerald-800 font-bold shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-center items-center gap-2 group">
                        <p class="text-base sm:text-lg"> ${d.name_ar}</p>
                        <div>
                            <a href="${url_show}"><button type="button" class="p-2 bg-sky-600 rounded-2xl text-white px-4">View</button></a>
                            <a href="${url_edit}"><button class="p-2 bg-green-500 rounded-2xl text-white px-4">Edit</button></a>
                            <form class='inline' action="${url_delete}" method="POST">
                                @csrf
                                @method('delete')
                                <button onclick="return confirm('Are you sure ?')" class="p-2 bg-red-500 rounded-2xl text-white px-4">Delete</button>
                            </form>
                        </div>
                    </div>`;
                        wrapper_list.insertAdjacentHTML('beforeend', item);
                    });

                    // إنشاء أزرار الترقيم
                    if (data.last_page > 1) {
                        renderPagination(data);
                    }

                    search_icon.classList.add('fa-magnifying-glass');
                    search_icon.classList.remove('fa-spinner', 'fa-spin');
                })
                .catch((error) => {
                    console.error('Error:', error);
                    wrapper_navigation.insertAdjacentHTML('afterbegin', `<p class="bg-rose-100 border border-rose-300 text-rose-700 text-center p-3 rounded-xl text-sm">حدث خطأ أثناء جلب البيانات، يرجى المحاولة لاحقاً!</p>`);
                    search_icon.classList.add('fa-magnifying-glass');
                    search_icon.classList.remove('fa-spinner', 'fa-spin');
                });
        }

        // --- دالة توليد أزرار الترقيم (Pagination) ---
        function renderPagination(paginationData) {
            const currentPage = paginationData.current_page;
            const lastPage = paginationData.last_page;

            let html = '';

            // زر السابق
            if (currentPage > 1) {
                html += `<button onclick="fetchData('${currentQuery}', ${currentPage - 1})" class="px-3.5 py-2 border border-gray-200 rounded-xl bg-white text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition text-sm font-medium shadow-sm">السابق</button>`;
            } else {
                html += `<button disabled class="px-3.5 py-2 border border-gray-100 rounded-xl bg-gray-50 text-gray-300 text-sm font-medium cursor-not-allowed">السابق</button>`;
            }

            // أرقام الصفحات
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(lastPage, currentPage + 2);

            for (let i = startPage; i <= endPage; i++) {
                if (i === currentPage) {
                    html += `<button class="h-9 w-9 rounded-xl bg-emerald-700 text-white font-bold text-sm shadow-sm">${i}</button>`;
                } else {
                    html += `<button onclick="fetchData('${currentQuery}', ${i})" class="h-9 w-9 rounded-xl border border-gray-200 bg-white text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition text-sm font-medium shadow-sm">${i}</button>`;
                }
            }

            // زر التالي
            if (currentPage < lastPage) {
                html += `<button onclick="fetchData('${currentQuery}', ${currentPage + 1})" class="px-3.5 py-2 border border-gray-200 rounded-xl bg-white text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition text-sm font-medium shadow-sm">التالي</button>`;
            } else {
                html += `<button disabled class="px-3.5 py-2 border border-gray-100 rounded-xl bg-gray-50 text-gray-300 text-sm font-medium cursor-not-allowed">التالي</button>`;
            }

            pagination_container.innerHTML = html;
        }
    </script>
    </x-slot>
</x-app-layout>


