<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnnualReport extends Model
{
    public const CHAPTERS = [
        'chairpersons-message' => ['title_en' => "Chairperson's Annual Message", 'title_np' => 'अध्यक्षको वार्षिक सन्देश'],
        'institutional-introduction' => ['title_en' => 'Institutional Introduction', 'title_np' => 'संस्थागत परिचय'],
        'annual-highlights' => ['title_en' => 'Annual Highlights', 'title_np' => 'वार्षिक मुख्य उपलब्धिहरू'],
        'membership-development' => ['title_en' => 'Membership Development', 'title_np' => 'सदस्यता विकास'],
        'financial-performance' => ['title_en' => 'Financial Performance', 'title_np' => 'वित्तीय कार्यसम्पादन'],
        'savings-and-loans' => ['title_en' => 'Savings and Loan Performance', 'title_np' => 'बचत तथा ऋण कार्यसम्पादन'],
        'institutional-activities' => ['title_en' => 'Institutional Activities', 'title_np' => 'संस्थागत गतिविधिहरू'],
        'governance-and-management' => ['title_en' => 'Governance and Management', 'title_np' => 'सुशासन तथा व्यवस्थापन'],
        'audit-and-accountability' => ['title_en' => 'Audit and Accountability', 'title_np' => 'लेखापरीक्षण तथा जवाफदेहिता'],
        'future-plans' => ['title_en' => 'Future Plans and Objectives', 'title_np' => 'भावी योजना तथा उद्देश्यहरू'],
        'acknowledgement' => ['title_en' => 'Acknowledgement', 'title_np' => 'आभार'],
    ];

    public const FINANCIAL_METRICS = [
        'total_members' => ['label_en' => 'Total members', 'label_np' => 'कुल सदस्य', 'unit_en' => 'members', 'unit_np' => 'सदस्य', 'decimal_places' => 0],
        'new_members' => ['label_en' => 'New members', 'label_np' => 'नयाँ सदस्य', 'unit_en' => 'members', 'unit_np' => 'सदस्य', 'decimal_places' => 0],
        'total_savings' => ['label_en' => 'Total savings', 'label_np' => 'कुल बचत', 'unit_en' => 'NPR', 'unit_np' => 'रु.', 'decimal_places' => 2],
        'total_loans' => ['label_en' => 'Total loans', 'label_np' => 'कुल ऋण लगानी', 'unit_en' => 'NPR', 'unit_np' => 'रु.', 'decimal_places' => 2],
        'total_assets' => ['label_en' => 'Total assets', 'label_np' => 'कुल सम्पत्ति', 'unit_en' => 'NPR', 'unit_np' => 'रु.', 'decimal_places' => 2],
        'total_liabilities' => ['label_en' => 'Total liabilities', 'label_np' => 'कुल दायित्व', 'unit_en' => 'NPR', 'unit_np' => 'रु.', 'decimal_places' => 2],
        'share_capital' => ['label_en' => 'Share capital', 'label_np' => 'शेयर पुँजी', 'unit_en' => 'NPR', 'unit_np' => 'रु.', 'decimal_places' => 2],
        'annual_income' => ['label_en' => 'Annual income', 'label_np' => 'वार्षिक आम्दानी', 'unit_en' => 'NPR', 'unit_np' => 'रु.', 'decimal_places' => 2],
        'annual_expenditure' => ['label_en' => 'Annual expenditure', 'label_np' => 'वार्षिक खर्च', 'unit_en' => 'NPR', 'unit_np' => 'रु.', 'decimal_places' => 2],
        'reserve_funds' => ['label_en' => 'Reserve funds', 'label_np' => 'जगेडा कोष', 'unit_en' => 'NPR', 'unit_np' => 'रु.', 'decimal_places' => 2],
    ];

    protected $fillable = ['report_id'];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(AnnualReportChapter::class)->orderBy('display_order')->orderBy('id');
    }

    public function financialStatistics(): HasMany
    {
        return $this->hasMany(AnnualReportFinancialStatistic::class)->orderBy('id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereHas('report', function (Builder $reportQuery): void {
            $reportQuery->published()
                ->whereHas('category', fn (Builder $categoryQuery) => $categoryQuery
                    ->where('slug', 'annual-reports')
                    ->where('is_active', true))
                ->whereHas('fiscalYear', fn (Builder $yearQuery) => $yearQuery->where('is_active', true));
        });
    }
}
