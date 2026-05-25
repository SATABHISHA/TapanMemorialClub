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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('performance_id')->nullable()->constrained('performances')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('achievement_date')->nullable()->index();
            $table->year('year')->nullable()->index();
            $table->string('badge_color', 20)->default('#D4AF37');
            $table->string('icon')->nullable();
            $table->unsignedBigInteger('media_library_id')->nullable()->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
