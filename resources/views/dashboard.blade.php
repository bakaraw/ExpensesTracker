<x-app-layout>
    @php
        $_budget_type = 0;

        switch ($budget_type_name) {
            case 'daily':
                $_budget_type = 'Day';
                break;
            case 'weekly':
                $_budget_type = 'Week';
                break;
            case 'monthly':
                $_budget_type = 'Month';
                break;

            default:
                $_budget_type = 'Irror';
                break;
        }
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
        <h1 class="mt-1 font-semibold text-2xl text-gray-800 leading-tight">
            This {{ $_budget_type }}'s Expense Summary
        </h1>

    </x-slot>

    <div class="py-7">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Container for the financial data and "hello" div -->
            <div class="flex flex-col sm:flex-row justify-between">
                <!-- Financial data divs -->

                <div
                    class="flex items-center bg-gradient-to-b from-lime-400 to-green-600 overflow-hidden shadow-md sm:rounded-lg flex-1 mr-4 p-4 text-white">

                    <div class="flex-1">
                        <p>Total Income</p>
                        <h2 class="font-semibold text-xl text-white leading-tight">Php
                            @if ($sum_money_in > 0)
                                {{ number_format($sum_money_in) }}
                            @else
                                0.00
                            @endif

                        </h2>
                    </div>

                    <button x-data x-on:click="$dispatch('open-modal' , {name : 'money-in-modal'})"
                        class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-green-600 focus:outline-none focus:ring-2 focus:ring-green-600 shadow-md hover:bg-transparent hover:border-2 hover:border-white hover:text-white hover:transition">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-6 h-6"
                            fill="currentColor">
                            <path
                                d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                        </svg>
                    </button>
                </div>

                <div
                    class="flex items-center bg-gradient-to-b from-amber-500 to-orange-600 overflow-hidden shadow-md sm:rounded-lg flex-1 mr-4 p-4 text-white">
                    <div class="flex-1">
                        <p>Total Expenses</p>
                        <h2 class="font-semibold text-xl text-white leading-tight">Php
                            @if ($sum_money_out > 0)
                                {{ number_format($sum_money_out) }}
                            @else
                                0.00
                            @endif
                        </h2>
                    </div>
                    <button x-data x-on:click="$dispatch('open-modal' , {name : 'money-out-modal'})"
                        class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-600 shadow-md hover:bg-transparent hover:border-2 hover:border-white hover:text-white hover:transition">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-6 h-6"
                            fill="currentColor">
                            <path
                                d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                        </svg>
                    </button>
                </div>
                <div
                    class="flex items-center bg-gradient-to-b from-yellow-400 to-yellow-600 overflow-hidden shadow-md sm:rounded-lg flex-1 mr-4 p-4 text-white">
                    <div class="flex-1">
                        <p>Savings</p>
                        <h2 class="font-semibold text-xl text-white leading-tight">Php
                            @if ($sum_savings > 0)
                                {{ number_format($sum_savings) }}
                            @else
                                0.00
                            @endif
                        </h2>
                    </div>
                    <button x-data x-on:click="$dispatch('open-modal' , {name : 'savings-modal'})"
                        class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-600 shadow-md hover:bg-transparent hover:border-2 hover:border-white hover:text-white hover:transition">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-6 h-6"
                            fill="currentColor">
                            <path
                                d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                        </svg>
                    </button>
                </div>
                <div
                    class="flex flex-col justify-center bg-gradient-to-b from-slate-500 to-gray-700 overflow-hidden shadow-md sm:rounded-lg flex-1 p-4 text-white">
                    <p>Largest Spent</p>
                    <h2 class="font-semibold text-xl text-white leading-tight">Php
                        @if (isset($largest_spent))
                            @if ($largest_spent->amount > 0)
                                {{ number_format($largest_spent->amount) }}
                            @endif
                        @else
                            0.00
                        @endif

                    </h2>
                    <p class="font-small">
                        @if (isset($largest_spent))
                            {{ $largest_spent_cat_name }}
                        @else
                            ---
                        @endif
                    </p>
                </div>
            </div>

            <!-- Separate "hello" div on a new row -->
            <div class="my-5 sm:mt-0 flex flex-col sm:flex-row justify-center sm:justify-between">
                <!-- Chart div -->
                <div class="mt-4 bg-white p-4 rounded-lg w-full sm:w-2/3 shadow-md flex flex-col ">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h1 class="font-semibold text-xl">
                                @php
                                    $date = date('Y-m-d');
                                    $month = date('M', strtotime($date));
                                    $day = date('d', strtotime($date));
                                    $day_w = date('D', strtotime($date));
                                @endphp
                                {{ $month }} {{ $day }}, {{ $day_w }}
                            </h1>
                        </div>
                        <div role="tablist" aria-label="tabs"
                            class="bg-gray-50 relative w-max h-10 grid grid-cols-3 items-center px-[3px] rounded-xl shadow-md overflow-hidden transition">
                            <div
                                class="absolute indicator w-28 bottom-0 top-0 left-0 rounded-xl my-auto h-11 bg-gray-600 shadow-sm">
                            </div>
                            <button role="tab" aria-selected="false" aria-controls="tabpanel-1" id="tab-1"
                                tabindex="0" class="relative block h-10 px-6 tab rounded-xl">
                                <span class="text-white">Daily</span>
                            </button>
                            <button role="tab" aria-selected="false" aria-controls="tabpanel-2" id="tab-2"
                                tabindex="0" class="relative block h-10 px-6 tab rounded-xl">
                                <span class="text-gray-500">Weekly</span>
                            </button>
                            <button role="tab" aria-selected="false" aria-controls="tabpanel-3" id="tab-3"
                                tabindex="0" class="relative block h-10 px-6 tab rounded-xl">
                                <span class="text-gray-500">Monthly</span>
                            </button>
                        </div>
                    </div>
                    <div>

                        <div class="tab-panel " role="tabpanel" id="tabpanel-1">
                            <canvas id="dailyChart"></canvas>
                        </div>
                        <div class="tab-panel hidden opacity-0" role="tabpanel" id="tabpanel-2">
                            <canvas id="weeklyChart"></canvas>
                        </div>
                        <div class="tab-panel hidden opacity-0" role="tabpanel" id="tabpanel-3">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>

                </div>

                <!-- Other div -->
                <div class="mt-4 bg-white p-4 rounded-lg w-full sm:w-1/3 sm:ml-4">
                    <h1 class="font-light text-xl mb-1">Budget Status</h1>
                    <h1 class="font-medium text-2xl">Php {{ $sum_money_out }} / {{ $alloc_budget }}</h1>
                    <div>
                        @foreach ($budget_portions as $budget_portion)
                            <p>{{ $budget_portion->category->name }} - {{ $budget_portion->portion }}</p>
                        @endforeach

                    </div>
                </div>
            </div>

        </div>

    </div>

    <x-my-modal name="money-in-modal" width="max-w-lg" height="310">
        <x-slot name="header">
            <h1 class="text-black text-2xl">
                Money-In
            </h1>
        </x-slot>
        <x-slot name="body">
            <form action="{{ route('add.money-in') }}" method="post">
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

    <x-my-modal name="money-out-modal" width="max-w-lg" height="310">
        <x-slot name="header">
            <h1 class="text-black text-2xl">
                Money-Out
            </h1>
        </x-slot>
        <x-slot name="body">

            <form action="{{ route('add.money-out') }}" method="post">
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

                                @foreach ($user_portion_categories as $user_portion_category)
                                    @foreach ($categories as $category)
                                        @if ($category->id == $user_portion_category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Second div (1/3 width) -->
                    <div class="w-1/3 pl-4">
                        <div class="flex items-center">
                            <p class="text-black mr-2">Php</p>
                            <input type="number" min="1" name="amount"
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

    <x-my-modal name="savings-modal" width="max-w-lg" height="180">
        <x-slot name="header">
            <h1 class="text-black text-2xl">
                Savings
            </h1>
        </x-slot>
        <x-slot name="body">
            <form action="{{ route('add.savings') }}" method="post">
                @csrf
                @safesubmit
                <div class="flex items-center mb-4">
                    <p class="text-black mr-2">Php</p>
                    <input type="number" min="1" name="amount"
                        class="text-black flex-1 border border-gray-300 rounded-md py-1 px-3">
                </div>
                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-yellow-800 px-6 py-2 rounded-full text-white mr-2 hover:bg-yellow-700 active:bg-yellow-900">Save</button>
                </div>
            </form>
        </x-slot>
    </x-my-modal>
    </div>
    <x-slot name="scripts">
        <script type="module">
            // tabbing in daily/weekly/monthly
            let tabs = document.querySelectorAll(".tab")
            let indicator = document.querySelector(".indicator")
            let panels = document.querySelectorAll(".tab-panel")

            indicator.style.width = tabs[0].getBoundingClientRect().width + 'px'
            indicator.style.left = tabs[0].getBoundingClientRect().left - tabs[0].parentElement.getBoundingClientRect().left +
                'px'

            tabs.forEach(tab => {
                tab.addEventListener("click", () => {
                    // Get the clicked tab element
                    const clickedTab = event.currentTarget;
                    const spanElement = clickedTab.querySelector('span');

                    tabs.forEach(otherTab => {
                        if (otherTab !== clickedTab) {
                            const otherSpanElement = otherTab.querySelector('span');
                            if (otherSpanElement) {
                                otherSpanElement.classList.remove('text-white');
                                otherSpanElement.classList.add('text-gray-500');
                            }
                        }
                    });

                    if (spanElement) {
                        // Change the class of the <span> element for the clicked tab
                        spanElement.classList.add('text-white');
                        spanElement.classList.remove('text-gray-500');
                        // You can add or remove other classes as needed
                    }

                    let tabTarget = tab.getAttribute("aria-controls")

                    indicator.style.width = tab.getBoundingClientRect().width + 'px'
                    indicator.style.left = tab.getBoundingClientRect().left - tab.parentElement
                        .getBoundingClientRect().left + 'px'


                    panels.forEach(panel => {
                        let panelId = panel.getAttribute("id")
                        if (tabTarget === panelId) {
                            panel.classList.remove("hidden", "opacity-0")
                        } else {
                            panel.classList.add("hidden", "opacity-0")
                        }
                    })
                })
            })
        </script>

        {{-- for the daily chart --}}
        <script>
            const dailyctx = document.getElementById('dailyChart');
            const dailydata = {
                datasets: [{
                        label: 'Money-in',
                        data: <?php echo $money_in_data; ?>,
                        borderColor: 'rgb(132, 204, 22)',
                        backgroundColor: 'rgba(132, 204, 22, 0.5)',
                        borderWidth: 3,
                        pointRadius: 0,
                        fill: true
                    },
                    {
                        label: 'Money-out',
                        data: <?php echo $money_out_data; ?>,
                        borderColor: 'rgb(234, 88, 12)',
                        backgroundColor: 'rgba(234, 88, 12, 0.3)',
                        borderWidth: 3,
                        pointRadius: 0,
                        fill: true
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
            new Chart(dailyctx, dailyconfig);
        </script>

        {{-- for the weekly chart --}}
        <script>
            const weeklyctx = document.getElementById('weeklyChart');
            const weeklydata = {
                datasets: [{
                        label: 'Money-in',
                        data: <?php echo $w_money_in_data; ?>,
                        borderColor: 'rgb(132, 204, 22)',
                        backgroundColor: 'rgba(132, 204, 22, 0.5)',
                        borderWidth: 3,
                        pointRadius: 0,
                        fill: true
                    },
                    {
                        label: 'Money-out',
                        data: <?php echo $w_money_out_data; ?>,
                        borderColor: 'rgb(234, 88, 12)',
                        backgroundColor: 'rgba(234, 88, 12, 0.3)',
                        borderWidth: 3,
                        pointRadius: 0,
                        fill: true
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
                        label: 'Money-in',
                        data: <?php echo $m_money_in_data; ?>,
                        borderColor: 'rgb(132, 204, 22)',
                        backgroundColor: 'rgba(132, 204, 22, 0.5)',
                        borderWidth: 3,
                        pointRadius: 0,
                        fill: true
                    },
                    {
                        label: 'Money-out',
                        data: <?php echo $m_money_out_data; ?>,
                        borderColor: 'rgb(234, 88, 12)',
                        backgroundColor: 'rgba(234, 88, 12, 0.3)',
                        borderWidth: 3,
                        pointRadius: 0,
                        fill: true
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
