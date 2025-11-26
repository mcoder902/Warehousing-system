@extends("master")
@section("title", "ویرایش اطلاعات پرسنل | رضوان انبار")

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
            <div class="max-w-4xl mx-auto">

                <!-- هدر صفحه -->
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                            <i data-lucide="user-cog" class="w-6 h-6 text-emerald-600"></i>
                            ویرایش اطلاعات پرسنل
                        </h2>
                        <p class="text-gray-500 text-sm mt-1">ویرایش مشخصات: <span class="font-bold text-gray-700">{{ $personnel->full_name }}</span></p>
                    </div>
                    <a href="{{ route('personnel.index') }}" class="hidden md:flex items-center gap-2 text-gray-500 hover:text-gray-800 transition-colors">
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        <span>بازگشت به لیست</span>
                    </a>
                </div>

                <!-- فرم -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="h-2 bg-gradient-to-r from-blue-600 to-indigo-500"></div>

                    <form action="{{ route('personnel.update', $personnel->id) }}" method="POST" class="p-6 md:p-8 space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- نمایش خطاها -->
                        @if ($errors->any())
                            <div class="bg-red-50 text-red-700 p-4 rounded-xl border border-red-100 flex items-start gap-3 animate-pulse">
                                <i data-lucide="alert-triangle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <h4 class="font-bold text-sm mb-1">خطا در ویرایش اطلاعات:</h4>
                                    <ul class="list-disc list-inside text-sm opacity-90">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- نام -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">نام <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name', $personnel->first_name) }}"
                                       class="w-full px-4 py-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all @error('first_name') border-red-500 bg-red-50 @else border-gray-200 @enderror"
                                       required>
                                @error('first_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- نام خانوادگی -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">نام خانوادگی <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name', $personnel->last_name) }}"
                                       class="w-full px-4 py-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all @error('last_name') border-red-500 bg-red-50 @else border-gray-200 @enderror"
                                       required>
                                @error('last_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- کد ملی -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">کد ملی</label>
                                <input type="text" name="national_code" value="{{ old('national_code', $personnel->national_code) }}" maxlength="10"
                                       class="w-full px-4 py-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all font-mono text-left @error('national_code') border-red-500 bg-red-50 @else border-gray-200 @enderror"
                                       dir="ltr">
                                @error('national_code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- شماره پرسنلی -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">شماره پرسنلی</label>
                                <input type="text" name="personnel_code" value="{{ old('personnel_code', $personnel->personnel_code) }}"
                                       class="w-full px-4 py-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all font-mono text-left @error('personnel_code') border-red-500 bg-red-50 @else border-gray-200 @enderror"
                                       dir="ltr">
                                @error('personnel_code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- واحد سازمانی -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">واحد سازمانی</label>
                                <input type="text" name="department" value="{{ old('department', $personnel->department) }}"
                                       class="w-full px-4 py-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>

                            <!-- شماره تماس -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">شماره تماس</label>
                                <input type="text" name="phone" value="{{ old('phone', $personnel->phone) }}"
                                       class="w-full px-4 py-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all font-mono text-left"
                                       dir="ltr">
                            </div>

                            <!-- وضعیت -->
                            <div class="col-span-1 md:col-span-2">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_active" value="0"> <!-- برای ارسال مقدار فالس اگر چک نشد -->
                                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $personnel->is_active) ? 'checked' : '' }}>
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                    <span class="ms-3 text-sm font-medium text-gray-700">وضعیت حساب کاربری فعال باشد</span>
                                </label>
                            </div>

                        </div>

                        <div class="flex items-center justify-between gap-4 pt-6 border-t border-gray-100">

                            <!-- دکمه حذف (سمت راست) -->
                            <div class="flex-1">
                                @if(!$personnel->currentItems()->exists())
                                    <button type="button" onclick="if(confirm('آیا از حذف کامل این پرسنل اطمینان دارید؟')) document.getElementById('delete-form').submit();" class="text-red-500 hover:text-red-700 text-sm font-bold flex items-center gap-1 transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        حذف پرسنل
                                    </button>
                                @else
                                    <span class="text-gray-400 text-xs flex items-center gap-1 cursor-help" title="تا زمانی که کالا دست این شخص است امکان حذف وجود ندارد">
                                        <i data-lucide="lock" class="w-3 h-3"></i>
                                        غیرقابل حذف (دارای کالا)
                                    </span>
                                @endif
                            </div>

                            <!-- دکمه‌های اصلی (سمت چپ) -->
                            <div class="flex gap-3">
                                <a href="{{ route('personnel.index') }}" class="px-6 py-3 text-gray-500 hover:text-gray-800 font-medium transition-colors">
                                    انصراف
                                </a>
                                <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transform hover:-translate-y-0.5 transition-all flex items-center gap-2">
                                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                                    ذخیره تغییرات
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- فرم مخفی برای حذف -->
                    <form id="delete-form" action="{{ route('personnel.destroy', $personnel->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>

                </div>
            </div>
        </div>
    </main>
@endsection