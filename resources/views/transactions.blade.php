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
                <div class="flex flex-col lg:flex-row justify-between gap-4">
                    <div class="w-full lg:w-2/5">
                        <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                            <h1 class="">
                                Balance
                            </h1>
                            <h1 class="font-bold text-2xl">
                                @if (number_format($sum_money_in - $sum_money_out) < 0)
                                    -PHP {{ number_format(($sum_money_in - $sum_money_out) * -1) }}
                                @else
                                    PHP {{ number_format($sum_money_in - $sum_money_out) }}
                                @endif
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
                                <div>
                                    <p>Total Savings</p>
                                    <p class="text-yellow-600">PHP {{ $sum_savings }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-4">
                            <div class="flex justify-between items-end mb-3">
                                <h1 class="font-semibold text-lg">
                                    Insights
                                </h1>
                                <select id="chartSelector" class="w-36 text-black border border-gray-300 rounded-md py-1 px-4 cursor-pointer">
                                    <option value="1">Daily</option>
                                    <option value="2">Weekly</option>
                                    <option value="3">Monthly</option>
                                </select>
                            </div>
                            <div id="dailyChartContainer">
                                <canvas id="dailyChart" class="w-full h-full max-w-full max-h-full"></canvas>
                            </div>
                            <div id="weeklyChartContainer" class="hidden">
                                <canvas id="weeklyChart" class="w-full h-full max-w-full max-h-full"></canvas>
                            </div>
                            <div id="monthlyChartContainer" class="hidden">
                                <canvas id="monthlyChart" class="w-full h-full max-w-full max-h-full"></canvas>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex justify-between gap-x-4 h-10 text-white">
                                <button x-data x-on:click="$dispatch('open-modal' , {name : 'add-income'})" class="w-1/2 bg-green-500 hover:bg-green-600 active:bg-green-700 rounded-lg transition shadow-md">
                                    Add Income
                                </button>
                                <button x-data x-on:click="$dispatch('open-modal' , {name : 'add-expense'})" class="w-1/2 bg-red-500 hover:bg-red-600 active:bg-red-700 rounded-lg transition shadow-md">
                                    Add Expenses
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-3/5 bg-white rounded-lg shadow-md overflow-hidden">
                        <div>
                            <div class="flex flex-col sm:flex-row justify-between items-end bg-gray-600 p-3 rounded-t-lg text-white">
                                <div class="w-full sm:w-5/12 mb-2 sm:mb-0">
                                    <form class="max-w-md mx-auto" action="{{ route('search.transaction') }}" method="get">
                                        <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                <svg class="w-3 h-3 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                                </svg>
                                            </div>
                                            <input type="search" id="default-search" name="search" value="{{ $search }}" class="block w-full p-2 ps-8 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500" placeholder="Search" required />
                                            <button type="submit" class="text-white absolute end-2 bottom-1 bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-2 py-1">
                                                Search
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="flex items-center">
                                    <form id="dateRangeForm" action="{{ route('filter.bydate') }}" method="get" class="flex space-x-2">
                                        <input class="w-20 text-sm bg-gra text-black rounded-lg border border-gray-300 p-1" type="date" id="startDate" name="startDate" value="{{ $start_date }}">
                                        <span>to</span>
                                        <input class="w-20 text-sm bg-gra text-black rounded-lg border border-gray-300 p-1" type="date" id="endDate" name="endDate" value="{{ $end_date }}">
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="scroll-smooth max-h-[500px] snap-y overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Date & Time
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Note
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Category
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if ($searched_trans == null && $searched_trans_by_date == null)
                                        @foreach ($trans_based_of_type as $transaction)
                                            @php
                                                $date = new DateTime($transaction->created_at);
                                                $timezone = new DateTimeZone('Asia/Manila');
                                                $date->setTimezone($timezone);
                                            @endphp
                                            <tr class="text-gray-700">
                                                <td class="px-6 py-4 whitespace-nowrap">
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
                                                        @if ($transaction->category->name == 'Savings')
                                                            <p class="text-yellow-600">+PHP {{ $transaction->amount }}</p>
                                                        @else
                                                            <p class="text-red-500">-PHP {{ $transaction->amount }}</p>
                                                        @endif
                                                    @else
                                                        <p class="text-green-600">+PHP {{ $transaction->amount }}</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @elseif($searched_trans != null)
                                        @foreach ($searched_trans as $transaction)
                                            @php
                                                $date = new DateTime($transaction->created_at);
                                                $timezone = new DateTimeZone('Asia/Manila');
                                                $date->setTimezone($timezone);
                                            @endphp
                                            <tr class="text-gray-700">
                                                <td class="px-6 py-4 whitespace-nowrap">
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
                                                        @if ($transaction->category->name == 'Savings')
                                                            <p class="text-yellow-500">+PHP {{ $transaction->amount }}</p>
                                                        @else
                                                            <p class="text-red-500">-PHP {{ $transaction->amount }}</p>
                                                        @endif
                                                    @else
                                                        <p class="text-green-600">+PHP {{ $transaction->amount }}</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @elseif ($searched_trans_by_date != null)
                                        @foreach ($searched_trans_by_date as $transaction)
                                            @php
                                                $date = new DateTime($transaction->created_at);
                                                $timezone = new DateTimeZone('Asia/Manila');
                                                $date->setTimezone($timezone);
                                            @endphp
                                            <tr class="text-gray-700">
                                                <td class="px-6 py-4 whitespace-nowrap">
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
                                                        @if ($transaction->category->name == 'Savings')
                                                            <p class="text-yellow-500">+PHP {{ $transaction->amount }}</p>
                                                        @else
                                                            <p class="text-red-500">-PHP {{ $transaction->amount }}</p>
                                                        @endif
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

    <x-my-modal name="add-income" width="max-w-lg" height="310">
        <x-slot name="header">
            <h1 class="text-black text-2xl">
                Add Income
            </h1>
        </x-slot>
        <x-slot name="body">
            <form action="{{ route('transactions.money-in') }}" method="post">
                @csrf
                @safesubmit
                <input type="hidden" name="is-money-out" value="0">
                <div class="flex items-center mb-4">
                    <p class="text-black mr-2">Php</p>
                    <input type="number" min="1" name="amount"
                        class="text-black flex-1 border border-gray-300 rounded-md py-1 px-3">
                </div>
                <textarea name="note"
                    class="w-full border border-gray-300 rounded-lg text-black py-2 px-4 resize-none focus:outline-none h-32"
                    placeholder="Note (Optional)"></textarea>
                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-green-800 px-6 py-2 rounded-full text-white mr-2 hover:bg-green-700 active:bg-green-900">Save</button>
                </div>
            </form>
        </x-slot>
    </x-my-modal>

    <x-my-modal name="add-expense" width="max-w-lg" height="310">
        <x-slot name="header">
            <h1 class="text-black text-2xl">
                Add Expense
            </h1>
        </x-slot>
        <x-slot name="body">
            <form action="{{ route('transactions.money-out') }}" method="post">
                @csrf
                @safesubmit
                <input type="hidden" name="is-money-out" value="1">
                <div class="flex">
                    <!-- First div (2/3 width) -->
                    <div class="w-2/3 pr-4">
                        <div class="w-full">
                            <select
                                class="w-full text-black border border-gray-300 rounded-md py-2 px-4 mb-3 cursor-pointer"
                                name="category">
                                @foreach ($budget_portions as $budget_portion)
                                    <option value="{{ $budget_portion->category->id }}">
                                        {{ $budget_portion->category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Second div (1/3 width) -->
                    <div class="w-1/3 pl-4">
                        <div class="flex items-center">
                            <p class="text-black mr-2">Php</p>
                            <input type="number" min="1" name="amount" required
                                class="text-black w-full sm:w-28 border border-gray-300 rounded-md py-2 px-4">
                        </div>
                    </div>
                </div>
                <!-- Textarea (full width with responsive height) -->
                <textarea name="note" id="note"
                    class="w-full border border-gray-300 rounded-lg text-black py-2 px-4 resize-none focus:outline-none h-32"
                    placeholder="Note (Optional)"></textarea>

                <div class="flex justify-center">
                    <button type="submit"
                        class="mb-10 bg-orange-800 px-6 py-2 rounded-full text-white mr-2 hover:bg-orange-700 active:bg-orange-900">Save</button>
                </div>

            </form>
        </x-slot>
    </x-my-modal>

    <x-slot name="scripts">
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const chartSelector = document.getElementById('chartSelector');
                const dailyChartContainer = document.getElementById('dailyChartContainer');
                const weeklyChartContainer = document.getElementById('weeklyChartContainer');
                const monthlyChartContainer = document.getElementById('monthlyChartContainer');

                const showSelectedChart = (selectedValue) => {
                    // Hide all chart containers initially
                    dailyChartContainer.classList.add('hidden');
                    weeklyChartContainer.classList.add('hidden');
                    monthlyChartContainer.classList.add('hidden');

                    // Show the selected chart container
                    if (selectedValue === '1') {
                        dailyChartContainer.classList.remove('hidden');
                    } else if (selectedValue === '2') {
                        weeklyChartContainer.classList.remove('hidden');
                    } else if (selectedValue === '3') {
                        monthlyChartContainer.classList.remove('hidden');
                    }
                };

                chartSelector.addEventListener('change', (event) => {
                    showSelectedChart(event.target.value);
                });

                // Initially show the daily chart
                showSelectedChart('1');
            });
        </script>
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

        <script>
            const dailyctx = document.getElementById('dailyChart');
            const dailydata = {
                datasets: [{
                        label: 'Income',
                        data: <?php echo $money_in_data; ?>,
                        borderColor: 'rgb(132, 204, 22)',
                        backgroundColor: 'rgb(132, 204, 22)',
                        borderWidth: 3,
                        pointRadius: 0.3,
                        // fill: true
                    },
                    {
                        label: 'Expenses',
                        data: <?php echo $money_out_data; ?>,
                        borderColor: 'rgb(234, 88, 12)',
                        backgroundColor: 'rgb(234, 88, 12)',
                        borderWidth: 3,
                        pointRadius: 0.3,
                        // fill: true
                    },
                    {
                        label: 'Savings',
                        data: <?php echo $d_savings; ?>,
                        borderColor: 'rgb(250, 204, 21)',
                        backgroundColor: 'rgba(250, 204, 21)',
                        borderWidth: 3,
                        pointRadius: 0.3,
                        // fill: true
                    },

                ],

            }

            const dailyconfig = {
                type: 'bar',
                data: dailydata,
                options: {
                    tension: 0.4,
                    responsive: true,
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                parser: 'yyyy-MM-dd'
                            },
                        },
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {

                        legend: {
                            position: 'top',
                            align: 'end',
                        },

                    }
                }
            }
            new Chart(dailyctx, dailyconfig);
        </script>

        {{-- for the weekly chart --}}
        <script>
            const weeklyctx = document.getElementById('weeklyChart');
            const weeklydata = {
                datasets: [{
                        label: 'Income',
                        data: <?php echo $w_money_in_data; ?>,
                        borderColor: 'rgb(132, 204, 22)',
                        backgroundColor: 'rgb(132, 204, 22)',
                        borderWidth: 3,
                        pointRadius: 0.3,
                        // fill: true
                    },
                    {
                        label: 'Expenses',
                        data: <?php echo $w_money_out_data; ?>,
                        borderColor: 'rgb(234, 88, 12)',
                        backgroundColor: 'rgb(234, 88, 12)',
                        borderWidth: 3,
                        pointRadius: 0.3,
                        // fill: true
                    },
                    {
                        label: 'Savings',
                        data: <?php echo $w_savings; ?>,
                        borderColor: 'rgb(250, 204, 21)',
                        backgroundColor: 'rgba(250, 204, 21)',
                        borderWidth: 3,
                        pointRadius: 0.3,
                        // fill: true
                    },

                ],

            }

            const weeklyconfig = {
                type: 'bar',
                data: weeklydata,
                options: {
                    tension: 0.4,
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                        },
                    }
                }
            }
            new Chart(weeklyctx, weeklyconfig);
        </script>

        {{-- monthly chart --}}
        <script>
            const monthlyctx = document.getElementById('monthlyChart');
            const monthlydata = {
                datasets: [{
                        label: 'Income',
                        data: <?php echo $m_money_in_data; ?>,
                        borderColor: 'rgb(132, 204, 22)',
                        backgroundColor: 'rgb(132, 204, 22)',
                        borderWidth: 3,
                        pointRadius: 0.3,
                        // fill: true
                    },
                    {
                        label: 'Expenses',
                        data: <?php echo $m_money_out_data; ?>,
                        borderColor: 'rgb(234, 88, 12)',
                        backgroundColor: 'rgb(234, 88, 12)',
                        borderWidth: 3,
                        pointRadius: 0.3,
                        // fill: true
                    },
                    {
                        label: 'Savings',
                        data: <?php echo $m_savings; ?>,
                        borderColor: 'rgb(250, 204, 21)',
                        backgroundColor: 'rgba(250, 204, 21)',
                        borderWidth: 3,
                        pointRadius: 0.3,
                        // fill: true
                    },
                ],

            }

            const monthlyconfig = {
                type: 'bar',
                data: monthlydata,
                options: {
                    tension: 0.4,
                    responsive: true,
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'month',
                                parser: 'yyyy-MM'
                            }
                        },
                        y: {
                            beginAtZero: true

                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                        },
                    }
                }
            }
            new Chart(monthlyctx, monthlyconfig);
        </script>
    </x-slot>
</x-app-layout>
