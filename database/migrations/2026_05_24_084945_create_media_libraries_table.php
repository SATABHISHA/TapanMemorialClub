<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('media_libraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('module')->nullable()->index();
            $table->string('original_name');
            $table->string('mime_type', 120);
            $table->string('extension', 20)->nullable();
            $table->binary('image_bytes')->nullable();
            $table->binary('thumbnail_bytes')->nullable();
            $table->binary('webp_bytes')->nullable();
            $table->string('file_path')->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->unsignedBigInteger('compressed_size')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->boolean('is_blob')->default(false)->index();
            $table->string('hash', 64)->nullable()->unique();
            $table->timestamp('upload_date')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE media_libraries MODIFY image_bytes LONGBLOB NULL');
            DB::statement('ALTER TABLE media_libraries MODIFY thumbnail_bytes LONGBLOB NULL');
            DB::statement('ALTER TABLE media_libraries MODIFY webp_bytes LONGBLOB NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_libraries');
    }
};
