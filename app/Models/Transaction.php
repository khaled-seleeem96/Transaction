<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'paidAmount',
        'Currency',
        'statusCode',
        'parentIdentification',
        'paymentDate',
        'parentEmail'
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'parentEmail','email' );
    }
}
