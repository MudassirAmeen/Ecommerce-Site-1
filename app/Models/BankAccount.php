<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'account_number',
        'account_name',
        'bank_name',
        'balance',
    ];
}
