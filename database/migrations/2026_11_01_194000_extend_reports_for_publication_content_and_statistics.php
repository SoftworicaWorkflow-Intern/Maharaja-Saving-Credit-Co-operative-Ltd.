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
        Schema::table('fiscal_years', function (Blueprint $table): void {
            $table->string('label_en', 40)->nullable()->after('label');
        });

        Schema::table('reports', function (Blueprint $table): void {
            $table->string('reference_number')->nullable();
            $table->string('published_by')->nullable();
            $table->text('short_description_np')->nullable();
            $table->longText('description_np')->nullable();
            $table->longText('executive_summary_en')->nullable();
            $table->longText('executive_summary_np')->nullable();
            $table->longText('highlights_en')->nullable();
            $table->longText('highlights_np')->nullable();
            $table->longText('key_findings_en')->nullable();
            $table->longText('key_findings_np')->nullable();
            $table->longText('achievements_en')->nullable();
            $table->longText('achievements_np')->nullable();
            $table->longText('financial_summary_en')->nullable();
            $table->longText('financial_summary_np')->nullable();
            $table->longText('observations_en')->nullable();
            $table->longText('observations_np')->nullable();
            $table->longText('future_plans_en')->nullable();
            $table->longText('future_plans_np')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 500)->nullable();
            $table->string('document_visibility', 20)->default('private');
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('document_views')->default(0);
        });

        DB::table('fiscal_years')->orderBy('id')->get()->each(function (object $year): void {
            DB::table('fiscal_years')->where('id', $year->id)->update([
                'label_en' => $this->englishFiscalYear($year->label),
            ]);
        });

        DB::table('report_categories')->where('slug', 'other-reports')->update([
            'name_en' => 'Other Publications',
            'name_np' => 'अन्य प्रकाशनहरू',
            'slug' => 'other-publications',
            'display_order' => 8,
        ]);
        DB::table('report_categories')->insertOrIgnore([
            ['name_en' => 'Policies & Guidelines', 'name_np' => 'नीति तथा निर्देशिका', 'slug' => 'policies-guidelines', 'display_order' => 6, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name_en' => 'Meeting Decisions', 'name_np' => 'बैठकका निर्णयहरू', 'slug' => 'meeting-decisions', 'display_order' => 7, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Schema::table('fiscal_years', function (Blueprint $table): void {
            $table->unique('label_en');
        });

        Schema::create('report_attachments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('report_id')->constrained()->cascadeOnDelete();
            $table->string('original_name');
            $table->string('file_path');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size');
            $table->boolean('is_public')->default(false);
            $table->timestamps();

            $table->index(['report_id', 'is_public']);
        });

        Schema::create('report_statistics', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('fiscal_year_id')->constrained()->restrictOnDelete();
            $table->string('label_en', 150);
            $table->string('label_np', 150)->nullable();
            $table->decimal('value', 20, 2);
            $table->string('unit_en', 40)->nullable();
            $table->string('unit_np', 40)->nullable();
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->index(['is_published', 'fiscal_year_id', 'display_order']);
        });

        DB::table('report_statistics')->update([
            'is_published' => false,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_statistics');
        Schema::dropIfExists('report_attachments');
        DB::table('report_categories')->whereIn('slug', [
            'policies-guidelines',
            'meeting-decisions',
        ])->delete();
        DB::table('report_categories')->where('slug', 'other-publications')->update([
            'name_en' => 'Other Reports',
            'name_np' => 'अन्य प्रतिवेदन',
            'slug' => 'other-reports',
            'display_order' => 6,
        ]);
        Schema::table('fiscal_years', function (Blueprint $table): void {
            $table->dropUnique(['label_en']);
        });

        Schema::table('reports', function (Blueprint $table): void {
            $table->dropColumn([
                'reference_number',
                'published_by',
                'short_description_np',
                'description_np',
                'executive_summary_en',
                'executive_summary_np',
                'highlights_en',
                'highlights_np',
                'key_findings_en',
                'key_findings_np',
                'achievements_en',
                'achievements_np',
                'financial_summary_en',
                'financial_summary_np',
                'observations_en',
                'observations_np',
                'future_plans_en',
                'future_plans_np',
                'seo_title',
                'seo_description',
                'document_visibility',
                'views',
                'document_views',
            ]);
        });
        Schema::table('fiscal_years', function (Blueprint $table): void {
            $table->dropColumn('label_en');
        });
    }

    private function englishFiscalYear(string $label): string
    {
        $english = strtr($label, [
            '०' => '0',
            '१' => '1',
            '२' => '2',
            '३' => '3',
            '४' => '4',
            '५' => '5',
            '६' => '6',
            '७' => '7',
            '८' => '8',
            '९' => '9',
        ]);

        [$startYear, $endYear] = array_pad(explode('/', $english, 2), 2, '');

        return $startYear.'/'.ltrim($endYear, '0');
    }
};
