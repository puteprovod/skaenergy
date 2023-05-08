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
        Schema::create('gbook_posts', function (Blueprint $table) {
            $table->id();
            $table->string('name',30);
            $table->string('city', 30)->nullable();
            $table->unsignedBigInteger('date')->nullable();
            $table->string('host',17);
            $table->text('comment');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('premod')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gbook_posts');
    }
};
