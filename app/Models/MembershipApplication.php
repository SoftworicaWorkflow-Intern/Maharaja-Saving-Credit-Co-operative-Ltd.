<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MembershipApplication extends Model
{
    public const PENDING = 'pending';

    public const UNDER_REVIEW = 'under_review';

    public const DOCUMENTS_REQUIRED = 'additional_documents_required';

    public const APPROVED = 'approved';

    public const REJECTED = 'rejected';

    public const OPEN_STATUSES = [
        self::PENDING,
        self::UNDER_REVIEW,
        self::DOCUMENTS_REQUIRED,
    ];

    protected $fillable = [
        'phone',
        'address',
        'status',
        'review_reason',
        'reviewed_by',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function events(): HasMany
    {
        return $this->hasMany(MembershipApplicationEvent::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(MembershipApplicationDocument::class);
    }

    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }
}
