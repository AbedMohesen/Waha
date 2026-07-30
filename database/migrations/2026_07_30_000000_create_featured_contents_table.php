<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('featured_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section', 40);
            $table->foreignId('martyr_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('story_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('momeries_img_id')->nullable()->constrained('momeries_imgs')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['section', 'created_at']);
            $table->unique(['section', 'martyr_id']);
            $table->unique(['section', 'story_id']);
            $table->unique(['section', 'momeries_img_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('featured_contents');
    }
};
