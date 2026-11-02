<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name_en', 100);
            $table->string('name_np', 100)->nullable();
            $table->string('slug', 100)->unique();
            $table->string('description', 500)->nullable();
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('fiscal_years', function (Blueprint $table): void {
            $table->id();
            $table->string('label', 40)->unique();
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('reports', function (Blueprint $table): void {
            $table->id();
            $table->string('title_en');
            $table->string('title_np');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained('report_categories')->restrictOnDelete();
            $table->foreignId('fiscal_year_id')->constrained()->restrictOnDelete();
            $table->date('publication_date');
            $table->string('short_description', 500);
            $table->longText('description')->nullable();
            $table->string('document_path');
            $table->unsignedBigInteger('document_size');
            $table->string('featured_image_path')->nullable();
            $table->string('language', 10)->default('both');
            $table->string('status', 20)->default('draft');
            $table->boolean('featured')->default(false);
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->unsignedBigInteger('downloads')->default(0);
            $table->timestamps();

            $table->index(['status', 'publication_date']);
            $table->index(['category_id', 'status']);
            $table->index(['fiscal_year_id', 'status']);
        });

        $now = now();
        DB::table('report_categories')->insert([
            ['name_en' => 'Annual Reports', 'name_np' => 'वार्षिक प्रतिवेदन', 'slug' => 'annual-reports', 'display_order' => 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name_en' => 'Financial Reports', 'name_np' => 'वित्तीय प्रतिवेदन', 'slug' => 'financial-reports', 'display_order' => 2, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name_en' => 'Audit Reports', 'name_np' => 'लेखापरीक्षण प्रतिवेदन', 'slug' => 'audit-reports', 'display_order' => 3, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name_en' => 'AGM Reports', 'name_np' => 'साधारण सभा प्रतिवेदन', 'slug' => 'agm-reports', 'display_order' => 4, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name_en' => 'Progress Reports', 'name_np' => 'प्रगति प्रतिवेदन', 'slug' => 'progress-reports', 'display_order' => 5, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name_en' => 'Other Reports', 'name_np' => 'अन्य प्रतिवेदन', 'slug' => 'other-reports', 'display_order' => 6, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('fiscal_years')->insert([
            ['label' => '२०८०/०८१', 'display_order' => 2080, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['label' => '२०८१/०८२', 'display_order' => 2081, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['label' => '२०८२/०८३', 'display_order' => 2082, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['label' => '२०८३/०८४', 'display_order' => 2083, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
        Schema::dropIfExists('fiscal_years');
        Schema::dropIfExists('report_categories');
    }
};
