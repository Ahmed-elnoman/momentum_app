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
        Schema::create('posts', function (Blueprint $table) {
            $table->integer('post_id', true, true);
            $table->string('post_title', 255);
            $table->longText('post_content');
            $table->string('post_status', 10)->default('1')->comment('1 = active, 0 = inactive');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('post_created')->default(now());
            $table->dateTime('post_modify')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
