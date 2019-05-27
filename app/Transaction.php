<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $table = 'transactions';
    protected $primaryKey = 'transaction_id';
    public $timestamps = false;

    protected $fillable = [
        'customer_id', 'amount', 'created_by', 'updated_by'
    ];

    public $appends = ['created_by_name', 'updated_by_name', 'customer_name'];

    // public $timestamps = true; // mot needed it's automatic from mySql see migration file


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function getUpdatedByNameAttribute(){
        if (!$this->updated_by) return null;
        $user = User::find($this->updated_by);
        return ($user)? $user->name: 'Unknown';
    }

    public function getCreatedByNameAttribute(){
        if (!$this->created_by) return null;
        $user = User::find($this->created_by);
        return ($user)? $user->name: 'Unknown';
    }

    public function getCustomerNameAttribute(){
        return $this->customer->name;
    }
}
