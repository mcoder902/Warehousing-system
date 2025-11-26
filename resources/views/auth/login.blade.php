
@extends("master")

@section("title","ورود به سامانه")

@section("head")
    <script>
        function loginForm() {
            return {
                phone_number: '',
                password: '',
                showPassword: false,
                loading: false,
                errors: {}, // برای نمایش خطاها

                submit() {
                    // ریست کردن خطاها
                    this.errors = {};

                    if(!this.phone_number || !this.password) {
                        this.errors = { general: 'لطفا تمام فیلدها را پر کنید' };
                        return;
                    }

                    this.loading = true;

                    axios.post('{{ route("login.submit") }}', {
                        phone_number: this.phone_number,
                        password: this.password
                    })
                        .then(response => {
                            // لاگین موفق
                            // انتقال کاربر به آدرسی که از بکند آمد
                            window.location.href = response.data.redirect_url;
                        })
                        .catch(error => {
                            this.loading = false;

                            if (error.response && error.response.status === 422) {
                                // خطاهای ولیدیشن لاراول
                                // فرض بر این است که ساختار خطاها استاندارد لاراول است
                                this.errors = error.response.data.errors;

                                // اگر خطای کلی بود (مثلا رمز اشتباه) و روی فیلد خاصی نبود
                                if(this.errors.phone_number) {
                                    // می‌توانیم آلرت بدهیم یا زیر اینپوت نمایش دهیم
                                    console.log(this.errors.phone_number[0]);
                                }
                            } else {
                                alert('خطای سیستمی رخ داده است.');
                                console.error(error);
                            }
                        });
                }
            }
        }
    </script>
@endsection

@section("style")
    <style>
        /* ساخت گرادینت دقیقاً مشابه طرح فیگما */
        .login-gradient {
            background: linear-gradient(135deg, #091c21 0%, #034f36 100%);
        }

        /* انیمیشن ملایم برای ورود کارت */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fadeUp 0.6s ease-out forwards;
        }

        /* انیمیشن متن کناری (با کمی تاخیر و حرکت از راست) */
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-fade-in-right {
            animation: fadeInRight 0.8s ease-out forwards;
            opacity: 0; /* وضعیت اولیه مخفی */
        }
        .animation-delay-300 {
            animation-delay: 0.3s;
        }
    </style>
@endsection

@section("main")
    <body class="bg-white h-screen w-full flex items-center justify-center relative overflow-hidden font-sans">

    <!-- بک‌گراند سبز سمت چپ با انحنا و متن -->
    <!-- اضافه شدن flex items-center justify-center برای وسط‌چین کردن محتوای متنی -->
    <div class="absolute top-0 left-0 w-full md:w-[55%] h-full login-gradient rounded-b-[50px] md:rounded-b-none md:rounded-br-[200px] z-0 transition-all duration-500 shadow-2xl flex flex-col items-center justify-center">

        <!-- متن و لوگوی سمت چپ (فقط دسکتاپ) -->
        <div class="hidden md:flex flex-col items-center justify-center text-white z-20 animate-fade-in-right animation-delay-300 mb-20 pr-10">

            <!-- آیکون انبار -->
            <div class="mb-6 p-5 bg-white/10 rounded-3xl backdrop-blur-md border border-white/10 shadow-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-emerald-300 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>

            <!-- عنوان اصلی -->
            <h2 class="text-5xl font-black tracking-tighter mb-4 text-transparent bg-clip-text bg-gradient-to-b from-white to-emerald-200 drop-shadow-lg">
                رضوان انبار
            </h2>

            <!-- زیرنویس -->
            <p class="text-xl text-emerald-100 font-medium tracking-wide opacity-90">
                سامانه هوشمند مدیریت کالا و انبارداری
            </p>

            <!-- خط تزئینی -->
            <div class="w-24 h-1.5 bg-emerald-500 rounded-full mt-8 shadow-lg shadow-emerald-500/40"></div>
        </div>

    </div>

    <!-- کانتینر اصلی -->
    <!-- تغییر: md:justify-start برای قرارگیری فرم در سمت راست (در حالت RTL) -->
    <div class="container mx-auto px-4 relative z-10 flex justify-center md:justify-start items-center h-full">

        <!-- کارت لاگین -->
        <!-- تغییر: اضافه کردن md:mr-20 برای فاصله گرفتن از لبه راست صفحه -->
        <div x-data="loginForm()"
             class="bg-white rounded-3xl shadow-2xl w-full max-w-[450px] p-8 md:p-12 animate-fade-up border border-gray-100 md:mr-20 lg:mr-32">

            <!-- هدر کارت -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black text-gray-800 mb-3 tracking-tight">ورود</h1>
                <p class="text-gray-400 text-sm font-medium">نام کاربری و رمز عبور خود را وارد کنید</p>
            </div>

            <!-- فرم -->
            <form @submit.prevent="submit" method="post" href="{{route("login.submit")}}" class="space-y-5">

                <!-- اینپوت شماره تلفن -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 text-right"> شماره تلفن </label>
                    <input type="text"
                           x-model="phone_number"
                           class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all text-sm placeholder-gray-300 text-right"
                           placeholder="شماره تلفن خود را وارد کنید">
                    <p x-show="errors.phone_number" x-text="errors.phone_number && errors.phone_number[0]" class="text-red-500 text-xs mt-1 mr-1"></p>
                </div>

                <!-- اینپوت رمز عبور (همراه با دکمه نمایش) -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 text-right">رمز عبور</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'"
                               x-model="password"
                               class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all text-sm placeholder-gray-300 text-right pl-10"
                               placeholder="رمز عبور خود را وارد کنید">
                        <p x-show="errors.password" x-text="errors.password && errors.password[0]" class="text-red-500 text-xs mt-1 mr-1"></p>

                        <!-- دکمه چشم -->
                        <button type="button"
                                @click="showPassword = !showPassword"
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-emerald-600 transition-colors focus:outline-none">
                            <!-- آیکون چشم باز -->
                            <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- آیکون چشم بسته -->
                            <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 013.949-5.388" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.058 10.058 0 01-3.7 5.59m-1.7-1.7l-2.872-2.872" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- دکمه ورود -->
                <button type="submit"
                        :disabled="loading"
                        class="w-full bg-[#10b981] hover:bg-[#059669] text-white font-bold py-3.5 rounded-xl shadow-lg shadow-emerald-500/30 transition-all duration-300 transform active:scale-95 flex justify-center items-center mt-4">

                    <span x-show="!loading" class="text-lg">ورود</span>

                    <!-- لودینگ -->
                    <svg x-show="loading" class="animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>

                <!-- لینک فراموشی -->
                <div class="text-center pt-2">
                    <a href="{{route("forgot-password")}}" class="text-sm font-medium text-red-400 hover:text-red-500 transition-colors border-b border-dashed border-red-300 hover:border-red-500 pb-0.5">
                        فراموشی رمز عبور
                    </a>
                </div>

            </form>
        </div>
    </div>
    </body>
@endsection

@section("js")

@endsection