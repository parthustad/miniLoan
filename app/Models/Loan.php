<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Installments;
class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'amount',
        'term',
        'loan_status',
        'min_payment',
        'reviewer_id'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
    public function installments(){
        return $this->hasMany(Installments::class,'loan_id','id');
    }
}
