@extends("master")
@section("title", "ویرایش کالا: " . $item->name . " | رضوان انبار")

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
            <a href="{{ route('items.create') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl transition-colors">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                <span>ثبت کالای جدید</span>
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

        <header class="md:hidden h-16 bg-brand-dark text-white flex items-center justify-between px-4 shadow-md z-10">
            <span class="font-bold">رضوان انبار</span>
            <button @click="sidebarOpen = !sidebarOpen"><i data-lucide="menu"></i></button>
        </header>

        <div x-show="sidebarOpen" class="md:hidden fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm" @click="sidebarOpen = false"></div>

        <div class="flex-1 overflow-y-auto p-4 md:p-8">
            <div class="max-w-4xl mx-auto">

                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                            <i data-lucide="edit" class="w-6 h-6 text-emerald-600"></i>
                            ویرایش کالا
                        </h2>
                        <p class="text-gray-500 text-sm mt-1">ویرایش مشخصات کالای: <span class="font-bold text-gray-700">{{ $item->name }}</span></p>
                    </div>
                    <a href="{{ route('items.index') }}" class="hidden md:flex items-center gap-2 text-gray-500 hover:text-gray-800 transition-colors">
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        <span>بازگشت به لیست</span>
                    </a>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="h-2 bg-gradient-to-r from-blue-600 to-emerald-500"></div>

                    <form action="{{ route('items.update', $item->id) }}" method="POST" class="p-6 md:p-8 space-y-8">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="bg-red-50 text-red-700 p-4 rounded-xl border border-red-100 flex items-start gap-3 animate-pulse">
                                <i data-lucide="alert-triangle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <h4 class="font-bold text-sm mb-1">لطفا خطاهای زیر را برطرف کنید:</h4>
                                    <ul class="list-disc list-inside text-sm opacity-90">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- نام کالا -->
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-sm font-bold text-gray-700 mb-2">نام کالا <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="text" name="name" value="{{ old('name', $item->name) }}" maxlength="255"
                                           class="w-full pl-4 pr-10 py-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-emerald-500 transition-all @error('name') border-red-500 bg-red-50 @else border-gray-200 @enderror" required>
                                    <i data-lucide="tag" class="absolute right-3 top-3.5 text-gray-400 w-5 h-5"></i>
                                </div>
                                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- مدل -->
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-sm font-medium text-gray-600 mb-2">مدل</label>
                                <input type="text" name="model" value="{{ old('model', $item->model) }}" maxlength="100"
                                       class="w-full px-4 py-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-emerald-500 transition-all @error('model') border-red-500 bg-red-50 @else border-gray-200 @enderror">
                                @error('model') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- شماره سریال -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">شماره سریال <span class="text-red-500">*</span></label>
                                <input type="text" name="serial_number" value="{{ old('serial_number', $item->serial_number) }}"
                                       class="w-full px-4 py-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-emerald-500 transition-all font-mono text-left @error('serial_number') border-red-500 bg-red-50 @else border-gray-200 @enderror"
                                       dir="ltr" required>
                                @error('serial_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- کد اموال -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">کد اموال</label>
                                <input type="text" name="inventory_code" value="{{ old('inventory_code', $item->inventory_code) }}"
                                       class="w-full px-4 py-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-emerald-500 transition-all font-mono text-left @error('inventory_code') border-red-500 bg-red-50 @else border-gray-200 @enderror"
                                       dir="ltr">
                                @error('inventory_code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- وضعیت -->
                            <!-- نکته: در صفحه ویرایش، امکان تغییر وضعیت دستی (مثلاً ارسال به تعمیر) وجود دارد -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">وضعیت فعلی</label>
                                <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 transition-all cursor-pointer">
                                    <option value="available" {{ old('status', $item->status) == 'available' ? 'selected' : '' }}>🟢 موجود در انبار (سالم)</option>
                                    <option value="assigned" {{ old('status', $item->status) == 'assigned' ? 'selected' : '' }} disabled>🟠 دست پرسنل (غیرقابل تغییر دستی)</option>
                                    <option value="repair" {{ old('status', $item->status) == 'repair' ? 'selected' : '' }}>🔴 نیازمند تعمیر / اسقاط</option>
                                </select>
                                @if($item->status == 'assigned')
                                    <p class="text-xs text-amber-600 mt-1">برای تغییر وضعیت کالا از حالت "دست پرسنل"، باید از طریق صفحه لیست اقلام، عملیات "عودت" را انجام دهید.</p>
                                @endif
                            </div>
                        </div>

                        <!-- توضیحات -->
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">توضیحات و مشخصات فنی</label>
                            <textarea name="description" rows="4"
                                      class="w-full px-4 py-3 bg-gray-50 border rounded-xl focus:ring-2 focus:ring-emerald-500 transition-all @error('description') border-red-500 bg-red-50 @else border-gray-200 @enderror">{{ old('description', $item->description) }}</textarea>
                             @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                            <a href="{{ route('items.index') }}" class="px-6 py-3 text-gray-500 hover:text-gray-800 font-medium transition-colors">
                                انصراف
                            </a>
                            <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 transform hover:-translate-y-0.5 transition-all flex items-center gap-2">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                                ذخیره تغییرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection