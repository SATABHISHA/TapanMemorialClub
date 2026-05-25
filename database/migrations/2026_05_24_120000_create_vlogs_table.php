<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vlogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('body')->nullable();
            $table->string('video_url')->nullable();
            $table->unsignedBigInteger('image_media_id')->nullable()->index();
            $table->unsignedBigInteger('menu_id')->nullable()->index();
            $table->unsignedBigInteger('submenu_id')->nullable()->index();
            $table->enum('status', ['draft', 'published'])->default('published')->index();
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->unsignedBigInteger('view_count')->default(0);
            $table->boolean('is_featured')->default(false)->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('image_media_id')->references('id')->on('media_libraries')->nullOnDelete();
            $table->foreign('menu_id')->references('id')->on('menus')->nullOnDelete();
            $table->foreign('submenu_id')->references('id')->on('submenus')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vlogs');
    }
};
