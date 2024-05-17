<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transactions
        </h2>
        <h1 class="mt-1">
            @php

                $portion_categories = [];
                $type = '';
                switch ($user_budget->type) {
                    case 1:
                        $type = 'this day';
                        break;
                    case 2:
                        $type = 'this week';
                        break;
                    case 3:
                        $type = 'first day of this month';
                        break;
                }
                $firstday = date('M d, D', strtotime($type));
                $current_date = date('M d, D');

            @endphp
            {{ $firstday }}

            @if ($type != 'this day')
                - {{ $current_date }}
            @endif

        </h1>
    </x-slot>

    <div class="py-7">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="">
                <div class="flex justify-between gap-x-4">
                    <div class="w-2/5">
                        <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                            <h1 class="">
                                Balance
                            </h1>
                            <h1 class="font-bold text-2xl">
                                PHP {{ number_format($sum_money_in - $sum_money_out) }}
                            </h1>
                            <div class="mt-2 flex gap-x-6">
                                <div>
                                    <p>Total Income</p>
                                    <p class="text-green-600">PHP {{ $sum_money_in }}</p>
                                </div>
                                <div>
                                    <p>Total Expenses</p>
                                    <p class="text-red-500">PHP {{ $sum_money_out }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-4">
                            <div class="flex justify-between items-end">
                                <h1 class="font-semibold text-lg">
                                    Insights Summary
                                </h1>
                                <select name="" id=""
                                    class="w-36 text-black border border-gray-300 rounded-md py-1 px-4  cursor-pointer">
                                    <option value="1">Daily</option>
                                    <option value="2">Weekly</option>
                                    <option value="3">Monthly</option>
                                </select>
                            </div>
                            <canvas id="insight-summary"></canvas>
                        </div>
                    </div>
                    <div class="w-3/5 bg-white rounded-lg shadow-md overflow-hidden">
                        <div>
                            <div
                                class="flex justify-between items-end bg-gradient-to-b from-yellow-400 to-yellow-500 p-3 rounded-t-lg text-white">
                                <div class="w-5/12">
                                    <form class="max-w-md mx-auto" action="{{ route('search.transaction') }}"
                                        method="get">
                                        <label for="default-search"
                                            class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                <svg class="w-3 h-3 text-gray-500" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 20 20">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                                </svg>
                                            </div>
                                            <input type="search" id="default-search" name="search" value="{{$search}}"
                                                class="block w-full p-2 ps-8 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Search" required />
                                            <button type="submit"
                                                class="text-white absolute end-2 bottom-1 bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-2 py-1">
                                                Search
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="">
                                    <form id="dateRangeForm" action="/your-form-action" method="POST">
                                        <input class="w-30 text-sm bg-gra text-black rounded-lg" type="date" id="startDate" name="startDate">
                                        to
                                        <input class="w-30 text-sm text-black rounded-lg" type="date" id="endDate" name="endDate" disabled>
                                    </form>
                                </div>
                            </div>

                        </div>
                        <div class="scroll-smooth max-h-[500px] snap-y overflow-y-auto overflow-x-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Date</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Note</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Category</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Amount</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if ($searched_trans == null)
                                        @foreach ($trans_based_of_type as $transaction)
                                            @php
                                                $date = new DateTime($transaction->created_at);
                                                $timezone = new DateTimeZone('Asia/Manila');
                                                $date->setTimezone($timezone);
                                            @endphp
                                            <tr class="text-gray-700">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{-- h:i A --}}
                                                    <h1 class="font-semibold">
                                                        {{ $date->format('M d, Y') }}
                                                    </h1>
                                                    <p class="text-sm">
                                                        {{ $date->format('h:i A') }}
                                                    </p>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if ($transaction->note != null)
                                                        {{ $transaction->note }}
                                                    @else
                                                        <p class="text-sm italic">
                                                            none
                                                        </p>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if (isset($transaction->category->name))
                                                        {{ $transaction->category->name }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if ($transaction->is_money_out == 1)
                                                        <p class="text-red-500">-PHP {{ $transaction->amount }}</p>
                                                    @else
                                                        <p class="text-green-600">+PHP {{ $transaction->amount }}</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        @foreach ($searched_trans as $transaction)
                                            @php
                                                $date = new DateTime($transaction->created_at);
                                                $timezone = new DateTimeZone('Asia/Manila');
                                                $date->setTimezone($timezone);
                                            @endphp
                                            <tr class="text-gray-700">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{-- h:i A --}}
                                                    <h1 class="font-semibold">
                                                        {{ $date->format('M d, Y') }}
                                                    </h1>
                                                    <p class="text-sm">
                                                        {{ $date->format('h:i A') }}
                                                    </p>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if ($transaction->note != null)
                                                        {{ $transaction->note }}
                                                    @else
                                                        <p class="text-sm italic">
                                                            none
                                                        </p>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if (isset($transaction->category->name))
                                                        {{ $transaction->category->name }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if ($transaction->is_money_out == 1)
                                                        <p class="text-red-500">-PHP {{ $transaction->amount }}</p>
                                                    @else
                                                        <p class="text-green-600">+PHP {{ $transaction->amount }}</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script>
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');

            startDateInput.addEventListener('change', function() {
                if (this.value) {
                    endDateInput.removeAttribute('disabled');
                    endDateInput.focus(); // Automatically focus on endDate input after startDate is selected
                } else {
                    endDateInput.setAttribute('disabled', true);
                }
            });

            endDateInput.addEventListener('change', function() {
                if (this.value) {
                    document.getElementById('dateRangeForm').submit();
                }
            });
            </script>
    </x-slot>
</x-app-layout>
