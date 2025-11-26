@extends("master")
@section("title","مدیریت اقلام | رضوان انبار")

@section("style")
    <style>
        .sidebar-gradient { background: linear-gradient(180deg, #091c21 0%, #032f27 100%); }
        [x-cloak] { display: none !important; }
    </style>
@endsection

@section("nav")
    <!-- شروع Body و تعریف Alpine Data -->
    <body class="bg-gray-50 font-sans h-screen flex overflow-hidden"
          x-data="{
          sidebarOpen: false,
          assignModalOpen: false,
          returnModalOpen: false,
          selectedItem: null,

          openAssign(item) {
              this.selectedItem = item;
              this.assignModalOpen = true;
          },
          openReturn(item) {
              this.selectedItem = item;
              this.returnModalOpen = true;
          }
      }">

    <!-- ۱. سایدبار (ثابت سمت راست) -->
    <aside class="sidebar-gradient text-white w-64 flex-shrink-0 hidden md:flex flex-col shadow-2xl transition-all z-20">
        <!-- لوگو -->
        <div class="h-20 flex items-center px-6 border-b border-white/10 gap-3">
            <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <i data-lucide="package" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="font-bold text-lg tracking-tight">رضوان انبار</h1>
                <span class="text-xs text-emerald-400 opacity-80">پنل مدیریت</span>
            </div>
        </div>

        <!-- منو -->
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

        <!-- پروفایل کاربر -->
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-emerald-800 flex items-center justify-center text-emerald-200 font-bold border-2 border-emerald-600">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold truncate">{{ auth()->user()->name ?? 'مدیر سیستم' }}</p>
                    <a href="{{route("logout")}}" class="text-xs text-red-400 hover:text-red-300 flex items-center gap-1 mt-0.5">
                        <i data-lucide="log-out" class="w-3 h-3"></i> خروج
                    </a>
                </div>
            </div>
        </div>
    </aside>
@endsection

@section("main")
    <!-- ۲. محتوای اصلی -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative">

        <!-- هدر موبایل -->
        <header class="md:hidden h-16 bg-brand-dark text-white flex items-center justify-between px-4 shadow-md z-10">
            <span class="font-bold">رضوان انبار</span>
            <button @click="sidebarOpen = !sidebarOpen"><i data-lucide="menu"></i></button>
        </header>

        <!-- بخش اسکرول‌خور -->
        <div class="flex-1 overflow-y-auto p-4 md:p-8">

            <!-- عنوان و دکمه افزودن -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <i data-lucide="box" class="w-6 h-6 text-emerald-600"></i>
                        لیست اقلام انبار
                    </h2>
                    <p class="text-gray-500 text-sm mt-1">مدیریت موجودی، تخصیص کالا و پیگیری وضعیت‌ها</p>
                </div>
                <a href="{{ route('items.create') }}" class="flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl shadow-lg shadow-emerald-500/30 transition-all font-bold">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    ثبت کالای جدید
                </a>
            </div>

            <!-- باکس فیلتر و جستجو -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6">
                <form action="{{ route('items.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <!-- جستجو -->
                    <div class="md:col-span-2 relative">
                        <i data-lucide="search" class="absolute right-3 top-3.5 text-gray-400 w-5 h-5"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="w-full pr-10 pl-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                               placeholder="جستجو در نام، سریال یا کد اموال...">
                    </div>

                    <!-- فیلتر وضعیت -->
                    <div>
                        <select name="status" onchange="this.form.submit()" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 transition-all cursor-pointer">
                            <option value="">همه وضعیت‌ها</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>🟢 موجود در انبار</option>
                            <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>🟠 دست پرسنل</option>
                            <option value="repair" {{ request('status') == 'repair' ? 'selected' : '' }}>🔴 تعمیرات/اسقاط</option>
                        </select>
                    </div>

                    <!-- دکمه اعمال/پاک کردن -->
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-gray-800 text-white rounded-xl hover:bg-gray-700 transition-colors">جستجو</button>
                        @if(request()->has('search') || request()->has('status'))
                            <a href="{{ route('items.index') }}" class="px-4 py-3 bg-red-50 text-red-500 rounded-xl hover:bg-red-100 border border-red-100" title="پاک کردن فیلتر">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- جدول داده‌ها -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-right">
                        <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-sm">
                            <th class="px-6 py-4 font-bold">نام کالا / مدل</th>
                            <th class="px-6 py-4 font-bold">شناسه (سریال/اموال)</th>
                            <th class="px-6 py-4 font-bold">وضعیت</th>
                            <th class="px-6 py-4 font-bold">محل قرارگیری</th>
                            <th class="px-6 py-4 font-bold text-center">عملیات</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @forelse($items as $item)
                            <tr class="hover:bg-gray-50/50 transition-colors group cursor-pointer"
                            @click="window.location.href = '{{ route('items.show', $item->id) }}'"
                            >

                                    <!-- نام کالا -->
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800">{{ $item->name }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $item->model ?? 'بدون مدل' }}</div>
                                    </td>

                                    <!-- شناسه‌ها -->
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1">
                                            <span class="text-xs font-mono bg-blue-50 text-blue-600 px-2 py-0.5 rounded w-fit">SN: {{ $item->serial_number }}</span>
                                            @if($item->inventory_code)
                                                <span class="text-xs font-mono bg-purple-50 text-purple-600 px-2 py-0.5 rounded w-fit">INV: {{ $item->inventory_code }}</span>
                                            @endif
                                        </div>
                                    </td>
                                </a>

                                <!-- وضعیت (Badge) -->
                                <td class="px-6 py-4">
                                    @if($item->status == 'available')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                            موجود در انبار
                                        </span>
                                    @elseif($item->status == 'assigned')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                            <i data-lucide="user" class="w-3 h-3"></i>
                                            تحویل داده شده
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                            {{ $item->status }}
                                        </span>
                                    @endif
                                </td>

                                <!-- محل قرارگیری -->
                                <td class="px-6 py-4">
                                    @if($item->currentPersonnel)
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold border border-indigo-200">
                                                {{ substr($item->currentPersonnel->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-700">{{ $item->currentPersonnel->full_name }}</div>
                                                <div class="text-[10px] text-gray-400">{{ $item->currentPersonnel->department }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm italic opacity-50">---</span>
                                    @endif
                                </td>

                                <!-- دکمه‌های عملیات -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">

                                        @if($item->status == 'available')
                                            <button @click='openAssign(@json($item))'
                                                    class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-500 hover:text-white transition-all tooltip" title="تحویل به پرسنل">
                                                <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                                            </button>
                                        @elseif($item->status == 'assigned')
                                            <button @click='openReturn(@json($item))'
                                                    class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-500 hover:text-white transition-all tooltip" title="عودت به انبار">
                                                <i data-lucide="arrow-down-left" class="w-4 h-4"></i>
                                            </button>
                                        @endif

                                        <a href="{{ route('items.edit', $item) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-500 hover:text-white transition-all" title="ویرایش">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i data-lucide="search-x" class="w-12 h-12 opacity-20"></i>
                                        <p>هیچ کالایی یافت نشد!</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between" dir="ltr">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </main>

    <!-- مودال‌ها -->

    <!-- ۱. مودال تحویل کالا (Assign) -->
    <div x-cloak x-show="assignModalOpen"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <div x-show="assignModalOpen"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"
             @click="assignModalOpen = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="assignModalOpen"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-right shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                <form :action="`/items/${selectedItem?.id}/assign`" method="POST">
                    @csrf
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start gap-4">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="user-plus" class="text-emerald-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-right w-full">
                                <h3 class="text-lg font-bold leading-6 text-gray-900">
                                    تحویل کالا: <span x-text="selectedItem?.name"></span>
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">انتخاب پرسنل</label>
                                        <select name="personnel_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 py-2.5 px-3 border bg-gray-50" required>
                                            <option value="">لطفا انتخاب کنید...</option>
                                            @if(isset($ppersonals))
                                                @foreach($personals as $p)
                                                    <option value="{{ $p->id }}">{{ $p->full_name }} ({{ $p->department }})</option>
                                                @endforeach
                                            @else
                                                @foreach($personals as $p)
                                                    <option value="{{ $p->id }}">{{ $p->full_name }} ({{ $p->department }})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات (اختیاری)</label>
                                        <textarea name="notes" rows="3" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 py-2 px-3 border bg-gray-50" placeholder="مثلا: تحویل به همراه شارژر و کیف"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                        <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-emerald-600 px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-emerald-500 sm:ml-3 sm:w-auto">تایید و ثبت تحویل</button>
                        <button type="button" @click="assignModalOpen = false" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">انصراف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ۲. مودال عودت کالا (Return) -->
    <div x-cloak x-show="returnModalOpen"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <div x-show="returnModalOpen" class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm" @click="returnModalOpen = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="returnModalOpen"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-right shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                <form :action="`/items/${selectedItem?.id}/return`" method="POST">
                    @csrf
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start gap-4">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="archive-restore" class="text-amber-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-right w-full">
                                <h3 class="text-lg font-bold leading-6 text-gray-900">
                                    عودت کالا به انبار
                                </h3>
                                <p class="text-sm text-gray-500 mt-2">
                                    آیا مطمئن هستید که کالای <span class="font-bold text-gray-800" x-text="selectedItem?.name"></span> از پرسنل تحویل گرفته شده و به انبار باز می‌گردد؟
                                </p>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">یادداشت وضعیت (مثلا: سالم، خرابی جزیی)</label>
                                    <textarea name="notes" rows="2" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-2 px-3 border bg-gray-50"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                        <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-amber-600 px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-amber-500 sm:ml-3 sm:w-auto">بله، بازگشت به انبار</button>
                        <button type="button" @click="returnModalOpen = false" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">انصراف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- اسکریپت فعال‌سازی آیکون‌ها -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
    </body>
@endsection