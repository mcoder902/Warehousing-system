@extends("master")

@section("title","مدیریت اقلام | رضوان انبار")

@section("style")
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #091c21 0%, #034f36 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header-hidden
        {
            transform: translateY(-100%);
        }
    </style>
@endsection

@section("nav")
    <body class="bg-gray-50 font-sans min-h-screen flex flex-col"
      x-data="{
          isLoggedIn: @json(Auth::check()),

          mobileMenuOpen: false,
          showHeader: true,
          lastScroll: 0,

          handleScroll() {
              if (this.isLoggedIn) {
                  const currentScroll = window.pageYOffset;
                  if (currentScroll <= 0) {
                      this.showHeader = true;
                  } else if (currentScroll > this.lastScroll && currentScroll > 50) {
                      this.showHeader = false;
                  } else if (currentScroll < this.lastScroll) {
                      this.showHeader = true;
                  }
                  this.lastScroll = currentScroll;
              } else {
                  this.showHeader = true;
              }
          }
      }"
      @scroll.window="handleScroll()">

    <!-- نوار ابزار (Navbar) -->
    <nav class="fixed w-full z-50 transition-transform duration-300 bg-white/90 backdrop-blur-md border-b border-gray-100"
         :class="{ '-translate-y-full': !showHeader }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                <!-- لوگو و نام -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-700 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20 text-white">
                        <i data-lucide="package" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-brand-dark tracking-tight">رضوان انبار</h1>
                        <span class="text-xs text-emerald-600 font-medium block -mt-1">سامانه جامع مدیریت اموال</span>
                    </div>
                </div>
                <!-- منوی دسکتاپ (فقط برای مهمان) -->
                <template x-if="!isLoggedIn">
                    <div class="hidden md:flex items-center gap-8">
                        <a href="#features" class="text-gray-600 hover:text-emerald-600 transition-colors font-medium">امکانات</a>
                        <a href="#about" class="text-gray-600 hover:text-emerald-600 transition-colors font-medium">درباره ما</a>
                        <a href="#contact" class="text-gray-600 hover:text-emerald-600 transition-colors font-medium">تماس</a>
                    </div>
                </template>

                <!-- دکمه‌های ورود/پنل -->
                <div class="hidden md:flex items-center gap-4">
                    <template x-if="isLoggedIn">
                        <div class="flex items-center gap-4 animate-fade-in">
                            <span class="text-sm text-gray-600">خوش آمدید، <span class="font-bold text-emerald-700">مدیر سیستم</span></span>
                            <a href="/dashboard" class="px-6 py-2.5 bg-brand-dark hover:bg-gray-800 text-white rounded-xl font-bold transition-all shadow-lg hover:shadow-xl flex items-center gap-2 transform hover:-translate-y-0.5">
                                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                پنل کاربری
                            </a>
                        </div>
                    </template>

                    <template x-if="!isLoggedIn">
                        <div class="flex items-center gap-3 animate-fade-in">
                            <a href="/login" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold transition-all shadow-lg shadow-emerald-500/30 flex items-center gap-2 transform hover:-translate-y-0.5">
                                <i data-lucide="log-in" class="w-4 h-4"></i>
                                ورود به سامانه
                            </a>
                        </div>
                    </template>
                </div>

                <!-- دکمه منوی موبایل -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-600 hover:text-emerald-600">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <!-- منوی موبایل -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white border-t border-gray-100 p-4 space-y-4 shadow-xl">
            <template x-if="!isLoggedIn">
                <div>
                    <a href="#features" class="block text-gray-600 font-medium mb-2">امکانات</a>
                    <a href="#about" class="block text-gray-600 font-medium mb-2">درباره ما</a>
                    <a href="#contact" class="block text-gray-600 font-medium">تماس</a>
                    <hr class="my-2">
                </div>
            </template>
             <template x-if="isLoggedIn">
                <a href="/dashboard" class="block w-full text-center px-4 py-3 bg-brand-dark text-white rounded-lg font-bold">ورود به پنل</a>
            </template>
            <template x-if="!isLoggedIn">
                <a href="/login" class="block w-full text-center px-4 py-3 bg-emerald-500 text-white rounded-lg font-bold">ورود به حساب</a>
            </template>
        </div>
    </nav>
    @endsection
@section("main")
    <!-- محتوای اصلی -->
    <main class="flex-grow pt-20">

        <!-- حالت ۱: کاربر لاگین شده (ساده و مینیمال) -->
        <template x-if="isLoggedIn">
            <div class="min-h-[80vh] flex flex-col items-center justify-center p-4 relative overflow-hidden">
                <!-- بک‌گراند ساده -->
                <div class="absolute top-0 left-0 w-full h-full bg-gray-50 z-0">
                    <div class="absolute top-0 left-0 w-full h-64 bg-emerald-500/5 rounded-b-[50px]"></div>
                </div>

                <div class="relative z-10 w-full max-w-2xl text-center">
                    <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-600 shadow-lg shadow-emerald-500/10">
                        <i data-lucide="user-check" class="w-10 h-10"></i>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-black text-gray-800 mb-4">
                        خوش آمدید، مدیر عزیز
                    </h1>
                    <p class="text-gray-500 mb-10 text-lg">
                        وضعیت سامانه پایدار است. برای مدیریت اقلام به پنل بروید.
                    </p>

                    <a href="/dashboard" class="inline-flex items-center gap-3 px-10 py-5 bg-gradient-to-r from-brand-dark to-gray-800 text-white text-lg font-bold rounded-2xl shadow-2xl hover:shadow-emerald-500/20 hover:-translate-y-1 transition-all group">
                        <span>ورود به داشبورد مدیریت</span>
                        <i data-lucide="arrow-left" class="w-6 h-6 group-hover:-translate-x-1 transition-transform"></i>
                    </a>

                    <!-- کارت‌های دسترسی سریع (اختیاری برای پر کردن فضا) -->
                    <div class="grid grid-cols-2 gap-4 mt-12 max-w-lg mx-auto">
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm text-right">
                            <span class="text-xs text-gray-400">تعداد اقلام</span>
                            <p class="text-2xl font-bold text-gray-800 mt-1">1,240</p>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm text-right">
                            <span class="text-xs text-gray-400">تحویل شده‌ها</span>
                            <p class="text-2xl font-bold text-emerald-600 mt-1">856</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- حالت ۲: کاربر مهمان (لندینگ کامل) -->
        <template x-if="!isLoggedIn">
            <div>
                <!-- Hero Section -->
                <header class="relative pt-20 pb-20 lg:pt-32 lg:pb-32 overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-[85%] hero-gradient rounded-b-[60px] lg:rounded-b-[100px] z-0"></div>

                    <div class="absolute top-20 left-10 opacity-10 animate-pulse">
                        <i data-lucide="box" class="w-32 h-32 text-white"></i>
                    </div>
                    <div class="absolute top-40 right-20 opacity-5">
                        <i data-lucide="layers" class="w-48 h-48 text-white"></i>
                    </div>

                    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <span class="inline-block py-1 px-3 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-100 text-sm font-bold mb-6 backdrop-blur-sm">
                            نسخه جدید سامانه
                        </span>
                        <h1 class="text-4xl lg:text-6xl font-black text-white mb-6 leading-tight drop-shadow-lg">
                            مدیریت هوشمند و دقیق<br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-200 to-white">دارایی‌های سازمانی</span>
                        </h1>
                        <p class="text-lg lg:text-xl text-emerald-100/90 mb-10 max-w-2xl mx-auto leading-relaxed">
                            سامانه «رضوان انبار» با هدف تسهیل فرایندهای ثبت، تحویل و رهگیری اقلام سازمانی طراحی شده است.
                        </p>

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <a href="/login" class="w-full sm:w-auto px-8 py-4 bg-white text-emerald-700 rounded-2xl font-bold shadow-2xl shadow-white/20 hover:bg-emerald-50 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                                شروع کنید
                                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                            </a>
                            <a href="#features" class="w-full sm:w-auto px-8 py-4 bg-emerald-700/50 text-white border border-emerald-500/50 rounded-2xl font-bold hover:bg-emerald-700/70 backdrop-blur-md transition-all">
                                بیشتر بدانید
                            </a>
                        </div>
                    </div>
                </header>

                <!-- امکانات (Features) -->
                <section id="features" class="py-20 -mt-20 relative z-20">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="glass-card p-8 rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                                <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mb-6 text-emerald-600">
                                    <i data-lucide="shield-check" class="w-8 h-8"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-3">امنیت و پایداری</h3>
                                <p class="text-gray-600 leading-relaxed">
                                    اطلاعات اموال شما با بالاترین استانداردهای امنیتی رمزنگاری شده و سوابق تحویل ذخیره می‌شوند.
                                </p>
                            </div>

                            <div class="glass-card p-8 rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 bg-emerald-600 text-white border-none ring-4 ring-emerald-50">
                                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center mb-6 text-white">
                                    <i data-lucide="zap" class="w-8 h-8"></i>
                                </div>
                                <h3 class="text-xl font-bold mb-3">سرعت و سهولت</h3>
                                <p class="text-emerald-100 leading-relaxed">
                                    رابط کاربری ساده و مدرن برای ثبت و تخصیص کالاها در کمترین زمان ممکن.
                                </p>
                            </div>

                            <div class="glass-card p-8 rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                                <div class="w-14 h-14 bg-teal-100 rounded-2xl flex items-center justify-center mb-6 text-teal-600">
                                    <i data-lucide="bar-chart-3" class="w-8 h-8"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-3">گزارش‌گیری دقیق</h3>
                                <p class="text-gray-600 leading-relaxed">
                                    دسترسی به تاریخچه کامل گردش کالاها و گزارش‌گیری لحظه‌ای از وضعیت انبار.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </template>
    </main>

@endsection


@section("footer")
    <!-- فوتر -->
    <footer>
        <!-- فوتر کامل (فقط مهمان) -->
        <template x-if="!isLoggedIn">
            <div id="contact" class="bg-gray-900 text-white pt-20 pb-10 mt-20 rounded-t-[50px]">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                        <div class="col-span-1 lg:col-span-2">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center text-white">
                                    <i data-lucide="package" class="w-6 h-6"></i>
                                </div>
                                <h2 class="text-2xl font-bold">رضوان انبار</h2>
                            </div>
                            <p class="text-gray-400 leading-relaxed max-w-md">
                                سامانه جامع رضوان انبار، راهکاری مطمئن برای سازمان‌هایی است که به نظم و دقت در مدیریت دارایی‌های خود اهمیت می‌دهند.
                            </p>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold mb-6 text-emerald-400">دسترسی سریع</h3>
                            <ul class="space-y-4 text-gray-400">
                                <li><a href="#" class="hover:text-white transition-colors">قوانین و مقررات</a></li>
                                <li><a href="#" class="hover:text-white transition-colors">سوالات متداول</a></li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold mb-6 text-emerald-400">ارتباط با ما</h3>
                            <ul class="space-y-4 text-gray-400">
                                <li class="flex items-center gap-3">
                                    <i data-lucide="phone" class="w-5 h-5 text-gray-500"></i>
                                    <span dir="ltr">021 - 88 99 00 00</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <i data-lucide="mail" class="w-5 h-5 text-gray-500"></i>
                                    <span>support@rezvan-anbar.ir</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-gray-500 text-sm">
                        <p>&copy; 1403 سامانه رضوان انبار. تمامی حقوق محفوظ است.</p>
                        <div class="flex gap-4 mt-4 md:mt-0">
                            <a href="#" class="hover:text-white"><i data-lucide="instagram" class="w-5 h-5"></i></a>
                            <a href="#" class="hover:text-white"><i data-lucide="linkedin" class="w-5 h-5"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- فوتر ساده (فقط کاربر لاگین شده) -->
        <template x-if="isLoggedIn">
            <div class="bg-white border-t border-gray-100 py-6 mt-auto">
                <div class="max-w-7xl mx-auto px-4 text-center">
                    <p class="text-gray-400 text-sm flex justify-center items-center gap-2">
                        <span>&copy; 1403 سامانه رضوان انبار</span>
                        <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                        <a href="#" class="hover:text-emerald-600 transition-colors">پشتیبانی</a>
                    </p>
                </div>
            </div>
        </template>
    </footer>
@endsection


@section("js")
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Vazirmatn', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            dark: '#0f262a',
                            primary: '#10b981',
                            secondary: '#034f36',
                            light: '#d1fae5'
                        }
                    }
                }
            }
        }
    </script>

@endsection