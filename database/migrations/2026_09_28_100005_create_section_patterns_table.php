<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('section_patterns', function (Blueprint $table) {
            $table->id();
            $table->string('section_slug')->unique(); // e.g., 'newsletter_cta', 'hero', 'footer'
            $table->string('section_name'); // e.g., 'Newsletter CTA Section'
            $table->string('image_path')->nullable(); // Path to uploaded pattern image
            $table->decimal('opacity', 3, 2)->default(0.15); // 0.00 to 1.00
            $table->string('blend_mode')->default('multiply'); // multiply, overlay, screen, normal
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('section_patterns');
    }
};