<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_id',
        'transaction_debtor',
        'transaction_date',
        'amount',
        'currency',
        'cash',
        'debtor',
        'creditor',
        'issuer',
        'executed',
    ];
}
