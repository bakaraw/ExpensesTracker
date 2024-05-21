<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-start items-center gap-x-5">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Insights
            </h2>
            <select class="rounded-lg border-gray-300" name="" id="chartSelector">
                <option value="1">Daily</option>
                <option value="2">Weekly</option>
                <option value="3">Monthly</option>
            </select>
        </div>

    </x-slot>

    <div class="py-7">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="w-full flex justify-between gap-x-4">
                <div class="w-3/5 bg-white rounded-lg shadow-md p-4">
                    <div>
                        <h1 class="font-semibold text-xl">
                            Income & Expenses
                        </h1>
                        <div>
                            <div class="tab-panel" role="tabpanel" id="dailyChartDiv">
                                <canvas class="w-full h-64" id="dailyChart"></canvas>
                            </div>
                            <div class="tab-panel hidden" role="tabpanel" id="weeklyChartDiv">
                                <canvas class="w-full h-64" id="weeklyChart"></canvas>
                            </div>
                            <div class="tab-panel hidden" role="tabpanel" id="monthlyChartDiv">
                                <canvas class="w-full h-64" id="monthlyChart"></canvas>
                            </div>
                        </div>
                        <div>

                        </div>
                    </div>


                </div>
                <div class="w-2/5">
                    <div class="bg-white rounded-lg shadow-md p-4">
                    <h1 class="font-semibold ">
                        Increase in Expense
                    </h1>

                    </div>
                    <div>
                        <h1 class="font-semibold bg-white rounded-lg shadow-md p-4 mt-4">
                            Decrease in Expense
                        </h1>
                    </div>
                    <div>
                        <h1 class="font-semibold bg-white rounded-lg shadow-md p-4 mt-4">
                            Largest Money In
                        </h1>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <x-slot name="scripts">
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const chartSelector = document.getElementById('chartSelector');
                const dailyChart = document.getElementById('dailyChartDiv');
                const weeklyChart = document.getElementById('weeklyChartDiv');
                const monthlyChart = document.getElementById('monthlyChartDiv');

                const showSelectedChart = (selectedValue) => {
                    // Hide all chart containers initially
                    dailyChart.classList.add('hidden');
                    weeklyChart.classList.add('hidden');
                    monthlyChart.classList.add('hidden');

                    // Show the selected chart container
                    if (selectedValue === '1') {
                        dailyChart.classList.remove('hidden');
                    } else if (selectedValue === '2') {
                        weeklyChart.classList.remove('hidden');
                    } else if (selectedValue === '3') {
                        monthlyChart.classList.remove('hidden');
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
                type: 'line',
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
                type: 'line',
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
                type: 'line',
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
