<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanDetail extends Model
{
    protected $fillable = ['clientid', 'num_of_payment', 'first_payment_date', 'last_payment_date', 'loan_amount', 'created_at'];
}
