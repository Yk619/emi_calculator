<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Loan List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Client ID</th>
                                <th>No. of Payments</th>
                                <th>First Payment Date</th>
                                <th>Last Payment Date</th>
                                <th>Loan Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($loans as $loan)
                                <tr>
                                    <td>{{ $loan->clientid }}</td>
                                    <td>{{ $loan->num_of_payment }}</td>
                                    <td>{{ $loan->first_payment_date }}</td>
                                    <td>{{ $loan->last_payment_date }}</td>
                                    <td>₹ {{ number_format($loan->loan_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No loan records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
