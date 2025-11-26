@extends("master")
@section("title", "مدیریت پرسنل | رضوان انبار")

@section("style")
    <style>
        .sidebar-gradient { background: linear-gradient(180deg, #091c21 0%, #032f27 100%); }
        [x-cloak] { display: none !important; }
    </style>
@endsection

@section("nav")
    <body class="bg-gray-50 font-sans h-screen flex overflow-hidden" x-data="{ sidebarOpen: false }">

    <!-- ۱. سایدبار -->
    <aside class="sidebar-gradient text-white w-64 flex-shrink-0 hidden md:flex flex-col shadow-2xl transition-all z-20">
        <div class="h-20 flex items-center px-6 border-b border-white/10 gap-3">
            <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <i data-lucide="package" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="font-bold text-lg tracking-tight">رضوان انبار</h1>
                <span class="text-xs text-emerald-400 opacity-80">پنل مدیریت</span>
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="{{ route('items.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl transition-colors">
                <i data-lucide="layers" class="w-5 h-5"></i>
                <span>لیست اقلام</span>
            </a>
            <a href="{{ route('personnel.index') }}" class="flex items-center gap-3 px-4 py-3 bg-white/10 text-white rounded-xl border border-white/5 shadow-inner">
                <i data-lucide="users" class="w-5 h-5 text-emerald-400"></i>
                <span>مدیریت پرسنل</span>
            </a>
            <a href="{{ route('assignments.history') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl transition-colors">
                <i data-lucide="history" class="w-5 h-5"></i>
                <span>تاریخچه تحویل</span>
            </a>
        </nav>

        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-emerald-800 flex items-center justify-center text-emerald-200 font-bold border-2 border-emerald-600">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold truncate">{{ auth()->user()->name ?? 'مدیر سیستم' }}</p>
                    <button class="text-xs text-red-400 hover:text-red-300 flex items-center gap-1 mt-0.5">
                        <i data-lucide="log-out" class="w-3 h-3"></i> خروج
                    </button>
                </div>
            </div>
        </div>
    </aside>
@endsection

@section("main")
    <main class="flex-1 flex flex-col h-full overflow-hidden relative">

        <!-- هدر موبایل -->
        <header class="md:hidden h-16 bg-brand-dark text-white flex items-center justify-between px-4 shadow-md z-10">
            <span class="font-bold">رضوان انبار</span>
            <button @click="sidebarOpen = !sidebarOpen"><i data-lucide="menu"></i></button>
        </header>
        <div x-show="sidebarOpen" class="md:hidden fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm" @click="sidebarOpen = false"></div>

        <!-- محتوا -->
        <div class="flex-1 overflow-y-auto p-4 md:p-8">

            <!-- عنوان و دکمه افزودن -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <i data-lucide="users" class="w-6 h-6 text-emerald-600"></i>
                        لیست پرسنل سازمان
                    </h2>
                    <p class="text-gray-500 text-sm mt-1">مدیریت کارکنان، ثبت اطلاعات تماس و واحد سازمانی</p>
                </div>
                <a href="{{ route('personnel.create') }}" class="flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl shadow-lg shadow-emerald-500/30 transition-all font-bold">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                    ثبت پرسنل جدید
                </a>
            </div>

            <!-- نمایش پیام موفقیت/خطا -->
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 text-emerald-700 p-4 rounded-xl border border-emerald-100 flex items-center gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl border border-red-100 flex items-center gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- باکس جستجو -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6">
                <form action="{{ route('personnel.index') }}" method="GET" class="flex gap-4">
                    <div class="flex-1 relative">
                        <i data-lucide="search" class="absolute right-3 top-3.5 text-gray-400 w-5 h-5"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="w-full pr-10 pl-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                               placeholder="جستجو بر اساس نام خانوادگی یا شماره پرسنلی...">
                    </div>
                    <button type="submit" class="bg-gray-800 text-white px-6 rounded-xl hover:bg-gray-700 transition-colors font-bold">
                        جستجو
                    </button>
                    @if(request('search'))
                        <a href="{{ route('personnel.index') }}" class="bg-red-50 text-red-500 px-4 rounded-xl hover:bg-red-100 border border-red-100 flex items-center justify-center">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </a>
                    @endif
                </form>
            </div>

            <!-- جدول داده‌ها -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-right">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-sm">
                                <th class="px-6 py-4 font-bold">نام و نام خانوادگی</th>
                                <th class="px-6 py-4 font-bold">شماره پرسنلی</th>
                                <th class="px-6 py-4 font-bold">واحد سازمانی</th>
                                <th class="px-6 py-4 font-bold">کد ملی / تماس</th>
                                <th class="px-6 py-4 font-bold">وضعیت</th>
                                <th class="px-6 py-4 font-bold text-center">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($personnel as $p)
                            <tr class="hover:bg-gray-50/50 transition-colors group">

                                <!-- نام و آواتار -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm border border-indigo-100">
                                            {{ substr($p->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800">{{ $p->full_name }}</div>
                                            <!-- لینک سریع به اقلام این شخص -->
                                            {{-- اگر ریلیشن defined باشد می‌توان تعداد را نشان داد --}}
                                            {{-- <span class="text-[10px] text-emerald-600">۳ کالا در اختیار دارد</span> --}}
                                        </div>
                                    </div>
                                </td>

                                <!-- شماره پرسنلی -->
                                <td class="px-6 py-4">
                                    <span class="font-mono bg-gray-100 text-gray-600 px-2 py-1 rounded text-sm border border-gray-200">
                                        {{ $p->personnel_code ?? '---' }}
                                    </span>
                                </td>

                                <!-- واحد سازمانی -->
                                <td class="px-6 py-4 text-gray-600 text-sm">
                                    {{ $p->department ?? 'تعیین نشده' }}
                                </td>

                                <!-- اطلاعات تماس -->
                                <td class="px-6 py-4">
                                    <div class="flex flex-col text-xs text-gray-500 gap-1">
                                        <span class="font-mono">ID: {{ $p->national_code ?? '-' }}</span>
                                        <span class="font-mono">Tel: {{ $p->phone ?? '-' }}</span>
                                    </div>
                                </td>

                                <!-- وضعیت (فعال/غیرفعال) -->
                                <td class="px-6 py-4">
                                    @if($p->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            فعال
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                            غیرفعال
                                        </span>
                                    @endif
                                </td>

                                <!-- عملیات -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">

                                        <a href="{{ route('personnel.edit', $p) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-500 hover:text-white transition-all tooltip" title="ویرایش">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>

                                        <!-- فرم حذف -->
                                        <form action="{{ route('personnel.destroy', $p) }}" method="POST" onsubmit="return confirm('آیا از حذف این پرسنل اطمینان دارید؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-500 hover:text-white transition-all tooltip" title="حذف">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i data-lucide="user-x" class="w-12 h-12 opacity-20"></i>
                                            <p>هیچ پرسنلی یافت نشد!</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between" dir="ltr">
                    {{ $personnel->links() }}
                </div>
            </div>
        </div>
    </main>
@endsection