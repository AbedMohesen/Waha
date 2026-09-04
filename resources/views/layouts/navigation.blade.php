<nav x-data="{ open: false }" class="oasis-nav sticky top-0 z-50 bg-white" aria-label="التنقل الرئيسي">
    <div class="oasis-container flex min-h-16 items-center justify-between gap-4 py-3 sm:min-h-[72px] lg:min-h-[83px]">
        <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 text-oasis-green">
            <img class="h-11 w-11 rounded-full border border-oasis-mint bg-white p-1" src="{{ asset('assets/img/icon.png') }}" alt="">
            <span class="text-sm font-extrabold sm:text-base">واحة الشهداء</span>
            <span class="hidden border-r border-black/10 pr-3 text-xs font-semibold text-black/50 sm:inline">الإدارة</span>
        </a>

        <div class="hidden items-center gap-5 lg:flex">
            <a href="{{ route('dashboard.index') }}" @class(['oasis-nav-link', 'oasis-nav-link-active' => request()->routeIs('dashboard.index')])>نظرة عامة</a>
            <a href="{{ route('dashboard.martyr.index') }}" @class(['oasis-nav-link', 'oasis-nav-link-active' => request()->routeIs('dashboard.martyr.*')])>السجلات</a>
            <a href="{{ route('dashboard.condolences.index') }}" @class(['oasis-nav-link', 'oasis-nav-link-active' => request()->routeIs('dashboard.condolences.*')])>
                <span>التعزيات</span>
                @if ($pendingCondolencesCount > 0)
                    <span data-pending-condolences-count="{{ $pendingCondolencesCount }}" class="mr-1.5 inline-grid min-w-5 place-items-center rounded-full bg-oasis-gold px-1.5 py-0.5 text-[11px] font-bold text-oasis-house">{{ $pendingCondolencesCount }}</span>
                @endif
            </a>
            <a href="{{ route('dashboard.homepage-content.index') }}" @class(['oasis-nav-link', 'oasis-nav-link-active' => request()->routeIs('dashboard.homepage-content.*')])>الواجهة الرئيسية</a>
        </div>

        <div class="hidden items-center gap-2 lg:flex">
            <a href="{{ route('front.index') }}" class="oasis-button oasis-button-outline text-xs">عرض الموقع</a>
            <x-dropdown align="left" width="48">
                <x-slot name="trigger">
                    <button class="oasis-button oasis-button-dark text-xs" type="button">{{ Str::limit(Auth::user()->name ?? 'الحساب', 18) }} <i class="fa-solid fa-chevron-down text-[10px]"></i></button>
                </x-slot>
                <x-slot name="content">
                    <div class="bg-oasis-cream p-2">
                        <x-dropdown-link :href="route('profile.edit')">الملف الشخصي</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">@csrf <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">تسجيل الخروج</x-dropdown-link></form>
                    </div>
                </x-slot>
            </x-dropdown>
        </div>

        <button type="button" class="grid h-11 w-11 place-items-center rounded-full border border-black/15 text-oasis-house lg:hidden" x-on:click="open = ! open" x-bind:aria-expanded="open.toString()" aria-label="فتح القائمة">
            <i class="fa-solid" x-bind:class="open ? 'fa-xmark' : 'fa-bars'"></i>
        </button>
    </div>

    <div x-show="open" x-transition class="border-t border-black/5 bg-oasis-cream p-4 lg:hidden">
        <div class="oasis-container grid gap-1 px-0">
            <a href="{{ route('dashboard.index') }}" class="rounded-lg px-4 py-3 text-sm font-semibold hover:bg-white">نظرة عامة</a>
            <a href="{{ route('dashboard.martyr.index') }}" class="rounded-lg px-4 py-3 text-sm font-semibold hover:bg-white">إدارة السجلات</a>
            <a href="{{ route('dashboard.condolences.index') }}" class="rounded-lg px-4 py-3 text-sm font-semibold hover:bg-white">مراجعة التعزيات</a>
            <a href="{{ route('dashboard.homepage-content.index') }}" class="rounded-lg px-4 py-3 text-sm font-semibold hover:bg-white">محتوى الواجهة الرئيسية</a>
            <div class="mt-2 flex flex-wrap gap-2 border-t border-black/5 pt-3">
                <a href="{{ route('front.index') }}" class="oasis-button oasis-button-outline text-xs">عرض الموقع</a>
                <a href="{{ route('profile.edit') }}" class="oasis-button oasis-button-dark text-xs">الملف الشخصي</a>
            </div>
        </div>
    </div>
</nav>
