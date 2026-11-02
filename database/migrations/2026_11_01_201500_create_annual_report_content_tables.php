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
        Schema::create('annual_reports', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('report_id')->unique()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('annual_report_chapters', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('annual_report_id')->constrained()->cascadeOnDelete();
            $table->string('chapter_key', 100);
            $table->string('title_en', 255);
            $table->string('title_np', 255)->nullable();
            $table->longText('content_en')->nullable();
            $table->longText('content_np')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->unique(['annual_report_id', 'chapter_key']);
            $table->index(['annual_report_id', 'is_published', 'display_order']);
        });

        Schema::create('annual_report_financial_statistics', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('annual_report_id')->constrained()->cascadeOnDelete();
            $table->string('metric_key', 60);
            $table->decimal('value', 20, 2);
            $table->decimal('comparison_value', 20, 2)->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->unique(['annual_report_id', 'metric_key']);
            $table->index(['annual_report_id', 'is_published']);
        });

        DB::table('reports')
            ->join('report_categories', 'reports.category_id', '=', 'report_categories.id')
            ->where('report_categories.slug', 'annual-reports')
            ->select('reports.id')
            ->orderBy('reports.id')
            ->get()
            ->each(fn (object $report) => DB::table('annual_reports')->insertOrIgnore([
                'report_id' => $report->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_report_financial_statistics');
        Schema::dropIfExists('annual_report_chapters');
        Schema::dropIfExists('annual_reports');
    }
};
