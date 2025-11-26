<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_id')
                  ->constrained('items')
                  ->cascadeOnDelete();

            // ارتباط با پرسنل (تحویل گیرنده)
            $table->foreignId('personnel_id')
                  ->constrained('personnels') // نام جدول پرسنل را چک کنید (جمع بسته شده یا مفرد)
                  ->cascadeOnDelete();

            // ارتباط با ادمین/انباردار (کسی که عملیات را انجام داده)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // زمان‌های عملیاتی
            $table->timestamp('assigned_at')->useCurrent(); // زمان تحویل
            $table->timestamp('returned_at')->nullable(); // زمان عودت (اگر نال باشد یعنی هنوز دست شخص است)

            // توضیحات (مثل وضعیت کالا هنگام تحویل)
            $table->text('notes')->nullable();

            $table->timestamps();

            // --- ایندکس‌های پیشنهادی برای سرعت بالا در گزارش‌گیری ---

            // برای پیدا کردن سریع کالاهای فعال (دست پرسنل)
            // کوئری: where item_id = X and returned_at is null
            $table->index(['item_id', 'returned_at']);

            // برای گزارش‌گیری از سوابق یک شخص خاص
            $table->index('personnel_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};