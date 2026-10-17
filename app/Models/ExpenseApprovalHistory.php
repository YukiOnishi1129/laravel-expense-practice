<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseApprovalHistory extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    public const ACTION_APPROVED = 'approved';
    public const ACTION_REJECTED = 'rejected';

    protected $fillable = [
        'expense_id',
        'user_id',
        'action',
    ];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
