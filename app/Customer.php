<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;
    protected $fillable = [
        'name', 'email', 'phone', 'created_by', 'updated_by'
    ];

    public $appends = ['created_by_name', 'updated_by_name'];

    // public $timestamps = true; // not needed it's automatic from mySql see migration file



    public function transactions(){
        return $this->hasMany(Transaction::class, 'customer_id', 'customer_id');
    }

    public function getUpdatedByNameAttribute(){
        if (!$this->updated_by) return 'Unknown';
        $user = User::find($this->updated_by);
        return ($user)? $user->name: 'Unknown';
    }

    public function getCreatedByNameAttribute(){
        if (!$this->created_by) return 'Unknown';
        $user = User::find($this->created_by);
        return ($user)? $user->name: 'Unknown';
    }
}
