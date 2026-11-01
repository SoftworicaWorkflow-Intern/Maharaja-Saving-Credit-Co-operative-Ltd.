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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('category')->index(); // 'savings', 'loan', 'member_support'
            $table->string('title_en');
            $table->string('title_np')->nullable();
            $table->text('short_description_en');
            $table->text('short_description_np')->nullable();
            $table->longText('full_description_en')->nullable();
            $table->longText('full_description_np')->nullable();
            $table->string('icon')->nullable();
            $table->json('benefits_en')->nullable();
            $table->json('benefits_np')->nullable();
            $table->text('eligibility_en')->nullable();
            $table->text('eligibility_np')->nullable();
            $table->json('required_documents_en')->nullable();
            $table->json('required_documents_np')->nullable();
            $table->text('process_en')->nullable();
            $table->text('process_np')->nullable();
            $table->text('purpose_en')->nullable();
            $table->text('purpose_np')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->integer('display_order')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
