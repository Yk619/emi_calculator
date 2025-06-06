<?php

namespace App\Services;

use App\Repositories\LoanRepository;

class LoanService
{
    protected $loanRepository;

    public function __construct(LoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    public function getAllLoans()
    {
        return $this->loanRepository->all();
    }

    public function calculateEmi($loanAmount, $months, $interest = 1)
    {
        // $r = ($interest / 100) / 12;
        // $emi = ($loanAmount * $r * pow(1 + $r, $months)) / (pow(1 + $r, $months) - 1);
        $emi = $loanAmount / $months;
        return round($emi, 2);
    }
}
