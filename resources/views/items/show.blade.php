@extends("master")
@section("title", "جزئیات کالا: " . $item->name . " | رضوان انبار")

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
            <a href="{{ route('items.index') }}" class="flex items-center gap-3 px-4 py-3 bg-white/10 text-white rounded-xl border border-white/5 shadow-inner">
                <i data-lucide="layers" class="w-5 h-5 text-emerald-400"></i>
                <span>لیست اقلام</span>
            </a>
            <a href="{{ route('personnel.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl transition-colors">
                <i data-lucide="users" class="w-5 h-5"></i>
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
            <div class="max-w-6xl mx-auto space-y-6">

                <!-- نوار ابزار بالا -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-2xl font-bold text-gray-800">{{ $item->name }}</h2>
                            @if($item->status == 'available')
                                <span class="bg-emerald-100 text-emerald-700 text-xs px-3 py-1 rounded-full font-bold border border-emerald-200">موجود در انبار</span>
                            @elseif($item->status == 'assigned')
                                <span class="bg-amber-100 text-amber-700 text-xs px-3 py-1 rounded-full font-bold border border-amber-200">دست پرسنل</span>
                            @elseif($item->status == 'repair')
                                <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full font-bold border border-red-200">در حال تعمیر</span>
                            @endif
                        </div>
                        <p class="text-gray-500 text-sm mt-1">مشاهده جزئیات کامل و سوابق گردش کالا</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('items.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors flex items-center gap-2 text-sm font-bold shadow-sm">
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            بازگشت
                        </a>
                        <a href="{{ route('items.edit', $item) }}" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors flex items-center gap-2 text-sm font-bold shadow-lg shadow-blue-500/30">
                            <i data-lucide="edit" class="w-4 h-4"></i>
                            ویرایش کالا
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- کارت اطلاعات اصلی (سمت راست - بزرگتر) -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
                                <i data-lucide="info" class="w-5 h-5 text-gray-400"></i>
                                <h3 class="font-bold text-gray-700">مشخصات فنی و عمومی</h3>
                            </div>
                            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <span class="text-xs text-gray-400 block mb-1">نام کالا</span>
                                    <p class="font-bold text-gray-800 text-lg">{{ $item->name }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400 block mb-1">مدل</span>
                                    <p class="font-medium text-gray-800">{{ $item->model ?? '---' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400 block mb-1">شماره سریال</span>
                                    <p class="font-mono text-gray-800 bg-gray-50 inline-block px-3 py-1 rounded border border-gray-200" dir="ltr">
                                        {{ $item->serial_number }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400 block mb-1">کد اموال</span>
                                    <p class="font-mono text-gray-800 bg-gray-50 inline-block px-3 py-1 rounded border border-gray-200" dir="ltr">
                                        {{ $item->inventory_code ?? '---' }}
                                    </p>
                                </div>
                                <div class="md:col-span-2">
                                    <span class="text-xs text-gray-400 block mb-1">توضیحات</span>
                                    <p class="text-gray-600 text-sm leading-relaxed bg-gray-50 p-4 rounded-xl border border-gray-100">
                                        {{ $item->description ?? 'توضیحاتی ثبت نشده است.' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- جدول تاریخچه (Audit Log) -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="history" class="w-5 h-5 text-gray-400"></i>
                                    <h3 class="font-bold text-gray-700">تاریخچه گردش کالا</h3>
                                </div>
                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-md">{{ $item->assignments->count() }} رکورد</span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-right text-sm">
                                    <thead class="bg-gray-50 text-gray-500 border-b border-gray-200">
                                        <tr>
                                            <th class="px-6 py-3 font-medium">تحویل گیرنده</th>
                                            <th class="px-6 py-3 font-medium">تاریخ تحویل</th>
                                            <th class="px-6 py-3 font-medium">تاریخ عودت</th>
                                            <th class="px-6 py-3 font-medium">ثبت کننده</th>
                                            <th class="px-6 py-3 font-medium">توضیحات</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse($item->assignments->sortByDesc('assigned_at') as $assignment)
                                            <tr class="hover:bg-gray-50/50 transition-colors {{ is_null($assignment->returned_at) ? 'bg-emerald-50/30' : '' }}">
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                                            {{ substr($assignment->personnel->last_name ?? '?', 0, 1) }}
                                                        </div>
                                                        <span class="font-bold text-gray-700">{{ $assignment->personnel->full_name }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-emerald-600" dir="ltr">
                                                    {{ $assignment->assigned_at->format('Y-m-d H:i') }}
                                                </td>
                                                <td class="px-6 py-4" dir="ltr">
                                                    @if($assignment->returned_at)
                                                        <span class="text-amber-600">{{ $assignment->returned_at->format('Y-m-d H:i') }}</span>
                                                    @else
                                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] px-2 py-0.5 rounded border border-emerald-200">فعال (دست شخص)</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-gray-500">
                                                    {{ $assignment->registrar->name ?? 'سیستم' }}
                                                </td>
                                                <td class="px-6 py-4 text-gray-500 truncate max-w-xs" title="{{ $assignment->notes }}">
                                                    {{ $assignment->notes ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                                    هنوز هیچ گردشی برای این کالا ثبت نشده است.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- سایدبار اطلاعات (سمت چپ - وضعیت فعلی) -->
                    <div class="lg:col-span-1 space-y-6">

                        <!-- وضعیت فعلی -->
                        <div class="bg-white rounded-2xl shadow-lg border border-emerald-100 overflow-hidden relative">
                            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-teal-600"></div>
                            <div class="p-6 text-center">
                                @if($item->currentPersonnel)
                                    <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4 text-amber-600 ring-4 ring-amber-50">
                                        <i data-lucide="user-check" class="w-10 h-10"></i>
                                    </div>
                                    <h3 class="font-bold text-gray-800 text-lg mb-1">در اختیار پرسنل</h3>
                                    <p class="text-gray-500 text-sm mb-6">این کالا در حال حاضر تحویل داده شده است.</p>

                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-right">
                                        <div class="mb-3">
                                            <span class="text-xs text-gray-400 block">تحویل گیرنده:</span>
                                            <span class="font-bold text-gray-800">{{ $item->currentPersonnel->full_name }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <span class="text-xs text-gray-400 block">واحد سازمانی:</span>
                                            <span class="text-gray-600">{{ $item->currentPersonnel->department }}</span>
                                        </div>
                                        <div>
                                            <span class="text-xs text-gray-400 block">تاریخ تحویل:</span>
                                            <span class="text-emerald-600 font-mono text-sm" dir="ltr">
                                                {{ $item->activeAssignment?->assigned_at->format('Y-m-d H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-600 ring-4 ring-emerald-50">
                                        <i data-lucide="archive" class="w-10 h-10"></i>
                                    </div>
                                    <h3 class="font-bold text-gray-800 text-lg mb-1">موجود در انبار</h3>
                                    <p class="text-gray-500 text-sm">این کالا آزاد است و می‌تواند به پرسنل تخصیص داده شود.</p>
                                @endif
                            </div>
                        </div>

                        <!-- اطلاعات سیستمی -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h4 class="font-bold text-gray-700 mb-4 text-sm flex items-center gap-2">
                                <i data-lucide="server" class="w-4 h-4 text-gray-400"></i>
                                اطلاعات سیستمی
                            </h4>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">تاریخ ثبت در سیستم:</span>
                                    <span class="font-mono text-gray-700" dir="ltr">{{ $item->created_at->format('Y-m-d') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">آخرین بروزرسانی:</span>
                                    <span class="font-mono text-gray-700" dir="ltr">{{ $item->updated_at->format('Y-m-d') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">شناسه سیستم (ID):</span>
                                    <span class="font-mono text-gray-700">{{ $item->id }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection