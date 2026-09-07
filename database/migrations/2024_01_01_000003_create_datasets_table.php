<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('datasets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('category');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('opd_id')->constrained('opds')->cascadeOnDelete();
            $table->boolean('is_public')->default(false);
            $table->integer('row_count')->default(0);
            $table->integer('file_size')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('datasets');
    }
};
