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
        Schema::create('users', function (Blueprint $table) {
            // Основные поля
            $table->id();
            $table->string('nick',30);
            $table->string('email',100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password',50);
            // Дополнительные поля
            $table->string('name',45)->nullable();
            $table->string('city',30)->nullable();
            $table->string('country',30)->nullable();
            $table->string('favourite_clubs',80)->nullable();
            $table->boolean('is_habar')->default(false);
            $table->mediumInteger('kit_id')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('banned_until')->nullable();
            $table->string('socialite_key')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
