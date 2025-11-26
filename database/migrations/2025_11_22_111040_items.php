<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('model')->nullable();
            $table->string('serial_number')->unique();
            $table->string('inventory_code')->unique()->nullable();
            $table->text('description')->nullable();

            $table->enum('status',['lost','assigned','available'])->default('available');

            $table->foreignId('current_personnel_id')
                  ->nullable()
                  ->constrained('personnel')
                  ->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
};