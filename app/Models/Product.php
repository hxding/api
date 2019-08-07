<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name','domain','key', 'deposit_status', 'withdraw_status', 'deposit_callback', 'withdraw_callback'];
}
