<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('body');
            $table->string('author', 100)->default('Редакция');
            $table->dateTime('created_at')->useCurrent();
            $table->unsignedInteger('likes_count')->default(0);
        });

        Schema::create('news_likes', function (Blueprint $table) {
            $table->unsignedInteger('news_id');
            $table->char('visitor_id', 32);
            $table->dateTime('liked_at')->useCurrent();
            $table->primary(['news_id', 'visitor_id']);
            $table->foreign('news_id')->references('id')->on('news')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_likes');
        Schema::dropIfExists('news');
    }
};
