<nav x-data="{ open: false }"
    class="bg-emerald-950 text-white border-b border-emerald-900 sticky top-0 z-50 shadow-md">

    <!-- ========================================== -->
    <!-- START: DESKTOP NAVIGATION -->
    <!-- ========================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">

            <!-- Logo -->
            <a href="{{ route('dashboard.index') }}"
                class="flex items-center gap-2.5 font-bold text-lg sm:text-xl text-amber-400 hover:text-amber-300 transition">
                <img class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover"
                    src="{{ asset('assets/img/icon.png') }}"
                    alt="شعار واحة الشهداء">
                <div class="hidden xs:block">
                    <span class="block tracking-wide">واحة الشهداء</span>
                    <span class="block mt-0.5 text-[10px] font-medium text-gray-400">
                        لوحة الإدارة
                    </span>
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex items-center gap-2">
                <a href="{{ route('dashboard.index') }}"
                    class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition
                    {{ request()->routeIs('dashboard.index')
                        ? 'bg-amber-500 text-emerald-950 shadow-sm'
                        : 'text-gray-300 hover:bg-emerald-900 hover:text-amber-400' }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>لوحة التحكم</span>
                </a>

                <a href="{{ route('dashboard.martyr.index') }}"
                    class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition
                    {{ request()->routeIs('dashboard.martyr.*')
                        ? 'bg-amber-500 text-emerald-950 shadow-sm'
                        : 'text-gray-300 hover:bg-emerald-900 hover:text-amber-400' }}">
                    <i class="fa-solid fa-user-shield"></i>
                    <span>إدارة الشهداء</span>
                </a>

                <a href="{{ route('front.index') }}"
                    class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold text-gray-300 hover:bg-emerald-900 hover:text-amber-400 transition">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>عرض الموقع</span>
                </a>
            </div>

            <!-- User Dropdown -->
            <div class="hidden md:flex items-center">
                <x-dropdown align="left" width="56">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-3 rounded-xl border border-emerald-800 bg-emerald-900/70 px-3.5 py-2 text-sm text-gray-200 hover:border-amber-500/50 hover:text-amber-300 transition focus:outline-none">
                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-amber-500 text-emerald-950">
                                <i class="fa-solid fa-user"></i>
                            </span>

                            <span class="max-w-32 truncate font-semibold">
                                {{ Auth::user()->name ?? 'المستخدم' }}
                            </span>

                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100 text-right">
                            <p class="text-sm font-bold text-slate-800">
                                {{ Auth::user()->name ?? '' }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500 truncate">
                                {{ Auth::user()->email ?? '' }}
                            </p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-user-gear text-emerald-700"></i>
                                <span>الملف الشخصي</span>
                            </span>
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <span class="flex items-center gap-2 text-rose-600">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <span>تسجيل الخروج</span>
                                </span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Toggle -->
            <button @click="open = !open"
                type="button"
                aria-label="فتح القائمة"
                class="md:hidden flex h-10 w-10 items-center justify-center rounded-xl text-gray-300 hover:bg-emerald-900 hover:text-white transition focus:outline-none">
                <i class="fa-solid text-xl" :class="open ? 'fa-xmark' : 'fa-bars'"></i>
            </button>
        </div>
    </div>
    <!-- ========================================== -->
    <!-- END: DESKTOP NAVIGATION -->
    <!-- ========================================== -->

    <!-- ========================================== -->
    <!-- START: MOBILE NAVIGATION -->
    <!-- ========================================== -->
    <div x-cloak x-show="open" x-transition
        class="md:hidden bg-emerald-900 border-t border-emerald-800 px-4 py-4">

        <div class="space-y-2">
            <a href="{{ route('dashboard.index') }}"
                class="flex items-center gap-2 rounded-xl px-3 py-2.5 text-sm font-semibold
                {{ request()->routeIs('dashboard.index')
                    ? 'bg-amber-500 text-emerald-950'
                    : 'text-gray-200 hover:bg-emerald-800 hover:text-amber-300' }}">
                <i class="fa-solid fa-chart-line w-5 text-center"></i>
                <span>لوحة التحكم</span>
            </a>

            <a href="{{ route('dashboard.martyr.index') }}"
                class="flex items-center gap-2 rounded-xl px-3 py-2.5 text-sm font-semibold
                {{ request()->routeIs('dashboard.martyr.*')
                    ? 'bg-amber-500 text-emerald-950'
                    : 'text-gray-200 hover:bg-emerald-800 hover:text-amber-300' }}">
                <i class="fa-solid fa-user-shield w-5 text-center"></i>
                <span>إدارة الشهداء</span>
            </a>

            <a href="{{ route('front.index') }}"
                class="flex items-center gap-2 rounded-xl px-3 py-2.5 text-sm font-semibold text-gray-200 hover:bg-emerald-800 hover:text-amber-300">
                <i class="fa-solid fa-arrow-up-right-from-square w-5 text-center"></i>
                <span>عرض الموقع</span>
            </a>
        </div>

        <div class="mt-4 border-t border-emerald-800 pt-4">
            <div class="mb-3 px-3">
                <p class="text-sm font-bold text-white">
                    {{ Auth::user()->name ?? '' }}
                </p>
                <p class="mt-1 text-xs text-gray-400">
                    {{ Auth::user()->email ?? '' }}
                </p>
            </div>

            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-2 rounded-xl px-3 py-2.5 text-sm text-gray-200 hover:bg-emerald-800 hover:text-amber-300">
                <i class="fa-solid fa-user-gear w-5 text-center"></i>
                <span>الملف الشخصي</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                    class="mt-1 w-full flex items-center gap-2 rounded-xl px-3 py-2.5 text-sm text-rose-300 hover:bg-rose-950/30 hover:text-rose-200 transition">
                    <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                    <span>تسجيل الخروج</span>
                </button>
            </form>
        </div>
    </div>
    <!-- ========================================== -->
    <!-- END: MOBILE NAVIGATION -->
    <!-- ========================================== -->
</nav>
