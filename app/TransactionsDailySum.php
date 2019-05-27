<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionsDailySum extends Model
{
    protected $table = 'transactions_daily_sum';
    protected $primaryKey = 'trans_daily_id';

    public $timestamps = false;

    protected $fillable = ['date_from', 'date_to', 'amount' ];

}
