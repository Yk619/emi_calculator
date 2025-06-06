<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LoanService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Schema;

class LoanController extends Controller
{
    protected $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    public function list()
    {
        $loans = $this->loanService->getAllLoans();
        return view('loan.index', compact('loans'));
    }

    public function showForm()
    {
        return view('loan.form');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'loan_amount' => 'required|numeric|min:1',
            'num_of_payment' => 'required|integer|min:1',
            'interest_rate' => 'required|numeric|min:0',
        ]);

        $emi = $this->loanService->calculateEmi(
            $request->loan_amount,
            $request->num_of_payment,
            $request->interest_rate
        );

        return view('loan.form', compact('emi'))->with('data', $request->all());
    }

    /**
     *  Show emi list
     */
    public function showEmiPage()
    {
        $columns = [];
        $rows = [];

        if (Schema::hasTable('emi_details')) {
            $columns = DB::getSchemaBuilder()->getColumnListing('emi_details');
            $rows = DB::table('emi_details')->get();
        }

        return view('loan.emi_details', compact('columns', 'rows'));
    }

    public function processEmiData2()
    {
        // Step 1: Drop if exists
        DB::statement('DROP TABLE IF EXISTS emi_details');

        // Step 2: Get date range
        $minDate = DB::table('loan_details')->min('first_payment_date');
        $maxDate = DB::table('loan_details')->max('last_payment_date');

        if (!$minDate || !$maxDate) {
            return back()->with('error', 'No data in loan_details');
        }

        $period = CarbonPeriod::create(
            Carbon::parse($minDate)->startOfMonth(),
            '1 month',
            Carbon::parse($maxDate)->startOfMonth()
        );

        // Step 3: Build dynamic columns
        $columns = ['clientid INT'];
        foreach ($period as $date) {
            $monthCol = $date->format('Y_m');
            $columns[] = "`$monthCol` DECIMAL(10,2) DEFAULT 0";
        }

        // Step 4: Create table using RAW SQL
        $sql = 'CREATE TABLE emi_details (' . implode(', ', $columns) . ')';
        DB::statement($sql);

        return redirect()->route('loan.emi_details')->with('success', 'emi_details table created successfully!');
    }

    /**
     * Process emi data
     */
    public function processEmiData()
    {
        // Step 1: Drop emi_details if exists
        DB::statement('DROP TABLE IF EXISTS emi_details');

        // Step 2: Get min/max date from loan_details
        $minDate = DB::table('loan_details')->min('first_payment_date');
        $maxDate = DB::table('loan_details')->max('last_payment_date');

        $period = CarbonPeriod::create(Carbon::parse($minDate)->startOfMonth(), '1 month', Carbon::parse($maxDate)->startOfMonth());

        // Step 3: Create emi_details table with dynamic columns
        $columns = ['clientid INT'];
        foreach ($period as $date) {
            $monthCol = $date->format('Y_m');
            $columns[] = "`$monthCol` DECIMAL(10,2) DEFAULT 0";
        }

        $sql = 'CREATE TABLE emi_details (' . implode(',', $columns) . ')';
        DB::statement($sql);

        // Step 4: Insert EMI data
        $loans = DB::table('loan_details')->get();
        foreach ($loans as $loan) {
            $monthlyEmi = round($loan->loan_amount / $loan->num_of_payment, 2);

            $loanPeriod = CarbonPeriod::create(Carbon::parse($loan->first_payment_date)->startOfMonth(), '1 month', Carbon::parse($loan->last_payment_date)->startOfMonth());
            $months = iterator_to_array($loanPeriod);

            $emiData = [];
            $total = 0;

            foreach ($months as $i => $month) {
                $col = $month->format('Y_m');

                if ($i === count($months) - 1) {
                    // Last payment - adjust EMI to match exact total
                    $adjustedEmi = round($loan->loan_amount - $total, 2);
                    $emiData[$col] = $adjustedEmi;
                } else {
                    $emiData[$col] = $monthlyEmi;
                    $total += $monthlyEmi;
                }
            }

            $insertData = array_merge(['clientid' => $loan->clientid], $emiData);

            // Build raw insert
            $columns = implode(',', array_map(fn($k) => "`$k`", array_keys($insertData)));
            $values = implode(',', array_map(fn($v) => is_numeric($v) ? $v : "'$v'", array_values($insertData)));

            DB::statement("INSERT INTO emi_details ($columns) VALUES ($values)");
        }
        return redirect()->route('emi.page');
    }
}
