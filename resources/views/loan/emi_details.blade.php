<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Process EMI Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="overflow:scroll;">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @elseif(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('emi.process') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary mb-3">Process Data</button>
                        </form>

                        @if (!empty($rows))
                            <h4>EMI Details Table</h4>
                            <table class="table table-bordered" style = "overflow:scroll;">
                                <thead>
                                    <tr>
                                        @foreach($columns as $col)
                                            <th>{{ $col }}</th>
                                        @endforeach
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rows as $row)
                                    <?php $sum = 0; $count = 0; ?>
                                        <tr>
                                            @foreach($columns as $col)
                                            <?php if ($count > 0) {
                                                $sum = $sum + $row->$col;
                                            }
                                            $count++; ?>
                                                <td>{{ $row->$col ?? '' }}</td>
                                            @endforeach
                                            <td> {{ $sum }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No EMI data. Click "Process Data" to generate the table.</p>
                        @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
