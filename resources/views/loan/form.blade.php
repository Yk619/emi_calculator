<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Loan Form') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('loan.calculate') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="labelStyle">Loan Amount</label>
                        <input type="number" name="loan_amount" class="form-control" value="{{ old('loan_amount', $data['loan_amount'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="labelStyle">Number of Payments (Months)</label>
                        <input type="number" name="num_of_payment" class="form-control" value="{{ old('num_of_payment', $data['num_of_payment'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="labelStyle">Annual Interest Rate (%)</label>
                        <input type="number" step="0.01" name="interest_rate" class="form-control" value="{{ old('interest_rate', $data['interest_rate'] ?? '') }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Calculate EMI</button>
                </form>

                @isset($emi)
                    <div class="alert alert-success mt-3">
                        <strong>Calculated EMI:</strong> ₹ {{ $emi }}
                    </div>
                @endisset
            </div>
        </div>
    </div>
</x-app-layout>
