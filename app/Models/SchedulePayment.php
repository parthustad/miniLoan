<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedulePayment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'amount',
        'term',
        'client_id',
        'status',
        'loan_id',
        'scheduled_at'
    ];
}
