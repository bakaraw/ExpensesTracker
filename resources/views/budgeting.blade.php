<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Budgeting
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
            <div class="flex gap-x-4">
                <div class="w-3/5 bg-gray-100 flex-shrink-0 flex flex-col">
                    <div class="flex gap-x-4">
                        <div
                            class="w-1/2 bg-gradient-to-b from-slate-500 to-gray-700 rounded-lg p-4 shadow-md text-white flex justify-between">
                            <div>
                                <p class="font-thin">
                                    Total Budget
                                </p>
                                <h1 class="font-bold text-ultrablack text-2xl">
                                    PHP
                                    @if ($alloc_budget > 0)
                                        {{ $alloc_budget }}
                                    @else
                                        0.00
                                    @endif
                                </h1>
                            </div>
                            <div>
                                <button x-data x-on:click="$dispatch('open-modal' , {name : 'edit-budget'})"
                                    class="text-gray-300">
                                    <svg class="feather feather-edit" fill="none" height="24"
                                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" viewBox="0 0 24 24" width="20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div
                            class="w-1/2 bg-gradient-to-b from-amber-500 to-orange-600 rounded-lg p-4 shadow-md text-white flex justify-between items-center">
                            <div>
                                <p class="font-thin">
                                    Total Expenses
                                </p>
                                <h1 class="font-bold text-ultrablack text-2xl">
                                    PHP
                                    @if ($total_expenses > 0)
                                        {{ $total_expenses }}
                                    @else
                                        0.00
                                    @endif
                                </h1>
                            </div>
                            <div>
                                <button x-data x-on:click="$dispatch('open-modal' , {name : 'money-out-modal'})"
                                    class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-600 shadow-md hover:bg-transparent hover:border-2 hover:border-white hover:text-white hover:transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-6 h-6"
                                        fill="currentColor">
                                        <path
                                            d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                    </svg>
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="mt-4 w-full bg-white rounded-lg px-5 py-2 flex-grow shadow-md">
                        <div class="flex justify-between items-center mt-3 mb-3">
                            <h1 class="text-2xl">Budget Portions</h1>

                        </div>
                        <div class="scroll-smooth max-h-96 snap-y overflow-y-auto ">
                            @foreach ($budget_portions as $budget_portion)
                                @php
                                    // Find the corresponding category from $categories based on category ID
                                    array_push($portion_categories, $budget_portion->category_id);
                                @endphp
                                <div
                                    class="border border-gray-300 rounded-xl mb-3 py-2 px-4 flex justify-start items-center snap-y gap-4">
                                    <div class="bg-yellow-400 rounded-full w-14 h-10 flex items-center justify-center">
                                        <i class="fa-solid {{ $budget_portion->category->icon }}"></i>
                                    </div>
                                    <div class="flex flex-row justify-between w-full">
                                        <div class="flex-1 items-center justify-between">
                                            <p class="font-bold text-lg">{{ $budget_portion->category->name }}</p>
                                            <p class="text-sm text-gray-700">Remaining: PHP
                                                {{ $budget_portion->portion - $trans_with_categories[$budget_portion->category->name] }}
                                            </p>
                                        </div>
                                        <div class="flex-1 flex flex-col items-end">
                                            <p class="font-bold text-lg">PHP {{ $budget_portion->portion }}</p>
                                            <p class="text-sm text-gray-700">Expenses:
                                                {{ $trans_with_categories[$budget_portion->category->name] }}</p>
                                        </div>

                                    </div>
                                    <div class="flex space-x-2">
                                        <button x-data
                                            x-on:click="$dispatch('open-modal' , {name : 'portion-{{ $budget_portion->portion_id }}'})"
                                            class="bg-orange-400 hover:bg-orange-500 active:bg-orange-600 text-white rounded-lg p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                                                class="w-6 h-6" fill="currentColor">
                                                <path
                                                    d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                            </svg>
                                        </button>
                                        <button x-data
                                            x-on:click="$dispatch('open-modal' , {name : 'edit-portion-{{ $budget_portion->portion_id }}'})"
                                            class="bg-gray-600 hover:bg-gray-700 active:bg-gray-600 text-white rounded-lg p-2">
                                            <svg class="feather feather-edit" fill="none" height="24"
                                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" viewBox="0 0 24 24" width="20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex justify-center w-full mb-3">
                            <button x-data x-on:click="$dispatch('open-modal' , {name : 'add-portion'})"
                                class="bg-gray-800 px-3 py-2 rounded-full text-white mt-3 mr-2 hover:bg-gray-700 active:bg-gray-900 flex justify-center items-center w-64 ">
                                <p>
                                    Add Portion
                                </p>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="w-2/5 bg-gray-100 flex-shrink-0 flex flex-col">
                    <div class="w-full h-full flex flex-col justify-between">
                        <div class="bg-white h-2/3 flex flex-col justify-start rounded-lg mb-4 shadow-md">
                            <div class=" bg-amber-500 rounded-t-lg px-2 pt-2 mb-3">
                                <h1 class="text-2xl mb-2 text-white">
                                    Portion Pie
                                </h1>
                            </div>
                            <div class="flex-1 overflow-hidden flex justify-center items-center m-8">
                                <canvas id="portion-pie" class="w-full h-full max-w-full max-h-full"></canvas>
                            </div>
                        </div>


                        <div class="bg-white h-1/3 rounded-lg shadow-md">
                            <div class=" bg-gray-700 rounded-t-lg px-2 py-1">
                                <h1 class="text-lg text-white">
                                    Status
                                </h1>
                            </div>
                            <div class="flex justify-center items-center">
                                <div class="px-3">
                                    @php
                                        $largest_portion_expense_sum = 0;
                                        $largest_portion_expense_category = '';
                                        foreach ($trans_with_categories as $key => $value) {
                                            if ($largest_portion_expense_sum < $value) {
                                                $largest_portion_expense_sum = $value;
                                                $largest_portion_expense_category = $key;
                                            }
                                        }
                                    @endphp
                                    <p class="text-lg my-7 mx-2">
                                        You have been spending a lot on <strong>{{ $largest_portion_expense_category }}</strong> with a
                                        total expense of <strong>Php {{ number_format($largest_portion_expense_sum) }}</strong>.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @foreach ($budget_portions as $budget_portion)
        <x-my-modal name="portion-{{ $budget_portion->portion_id }}" width="max-w-lg" height="550">
            <x-slot name="header">

                <h2 class="text-xl font-semibold text-gray-900">
                    <i class="fa-solid {{ $budget_portion->category->icon }} mr-3"></i>
                    {{ $budget_portion->category->name }} - Add Expense
                </h2>
            </x-slot>
            <x-slot name="body">
                <form action="{{ route('budgeting.add-expense') }}" method="post">
                    @csrf
                    @safesubmit
                    <input type="hidden" name="category" value="{{ $budget_portion->category->id }}">
                    <input type="hidden" name="is-money-out" value="1">
                    <div class="flex items-center mb-4">
                        <p class="text-black mr-2">Php</p>
                        <input type="number" min="1" name="amount" required
                            class="text-black flex-1 border border-gray-300 rounded-md py-1 px-3">
                    </div>
                    <textarea name="note"
                        class="w-full border border-gray-300 rounded-lg text-black py-2 px-4 resize-none focus:outline-none h-32"
                        placeholder="Note (Optional)"></textarea>
                    <div class="mt-3">
                        <h1 class="font-bold text-lg mb-3 text-gray-800">
                            Previous Transactions
                        </h1>
                        <div class="scroll-smooth max-h-40 snap-y overflow-y-auto mb-3">

                            @foreach ($category_transactions[$budget_portion->category->name] as $category_transaction)
                                @php
                                    $originalDate = $category_transaction->created_at;
                                    $date = new DateTime($originalDate);
                                    $timezone = new DateTimeZone('Asia/Manila');
                                    $date->setTimezone($timezone);
                                @endphp
                                <div
                                    class="border border-gray-200 rounded-md mb-3 py-1 px-4 flex justify-between snap-y">
                                    <div class="">
                                        <h1 class="font-medium"> {{ $date->format('M d, Y h:i A') }}</h1>
                                        <p class="overflow-ellipsis overflow-hidden text-sm text-gray-700">Note:
                                            @if ($category_transaction->note != null)
                                                {{ $category_transaction->note }}
                                            @else
                                                none
                                            @endif

                                        </p>

                                    </div>
                                    <div>
                                        <p class="font-medium">Php
                                            {{ number_format($category_transaction->amount, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach

                            @if (count($category_transactions[$budget_portion->category->name]) == 0)
                                <h1 class="test-sm font-thin text-gray-500">
                                    no transaction
                                </h1>
                            @endif

                        </div>
                    </div>
                    <div class="flex justify-center">
                        <button type="submit"
                            class="bg-orange-800 px-6 py-2 rounded-full text-white mr-2 hover:bg-orange-700 active:bg-orange-900">Save</button>
                    </div>
                </form>
            </x-slot>
        </x-my-modal>
    @endforeach

    @foreach ($budget_portions as $budget_portion)
        <x-my-modal name="edit-portion-{{ $budget_portion->portion_id }}" width="max-w-lg" height="180">
            <x-slot name="header">
                <h1 class="text-black text-2xl">
                    <i
                        class="fa-solid {{ $budget_portion->category->icon }} mr-3"></i>{{ $budget_portion->category->name }}
                    - Edit Portion
                </h1>
            </x-slot>
            <x-slot name="body">
                <form action="{{ route('budgeting.edit-portion') }}" method="post">
                    @csrf
                    @safesubmit
                    <input type="hidden" value="{{ $budget_portion->portion_id }}" name="portion_id">

                    <div class="flex items-center mb-4">
                        <p class="text-black mr-2">Php</p>
                        <input class="text-black flex-1 border border-gray-300 rounded-md py-1 px-3" type="number"
                            value="{{ $budget_portion->portion }}" min="1" name="portion">
                    </div>

                    <div class="flex justify-between">
                        <button type="submit" name="action" x-data x-on:click="$dispatch('close-modal')"
                            class="w-2/3 bg-gray-800 px-6 py-2 rounded-full text-white mr-2 hover:bg-gray-700 active:bg-gray-900"
                            value="save">Save</button>
                        <button type="submit" name="action" x-data x-on:click="$dispatch('close-modal')"
                            class="w-1/3 bg-red-600 px-6 py-2 rounded-full text-white hover:bg-red-500 active:bg-red-800"
                            value="delete">Delete</button>
                    </div>
                </form>
            </x-slot>
        </x-my-modal>
    @endforeach

    <x-my-modal name="money-out-modal" width="max-w-lg" height="310">
        <x-slot name="header">
            <h1 class="text-black text-2xl">
                Add Expense
            </h1>
        </x-slot>
        <x-slot name="body">

            <form action="{{ route('budgeting.add-expense') }}" method="post">
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

    <x-my-modal name="edit-budget" width="max-w-xl" height="230">
        <x-slot name="header">
            <h1 class="text-2xl">
                Edit Budget
            </h1>

        </x-slot>
        <x-slot name="body">
            <form action="{{ route('edit.budget') }}" method="post">
                @csrf
                @safesubmit
                <div class="flex justify-start mb-2">
                    <div class="w-2/3">
                        <h1 class="font-medium">
                            Budget type
                        </h1>
                    </div>
                    <div class="w-1/3 pl-4">
                        <h1 class="font-medium">
                            Budget Allocation
                        </h1>
                    </div>
                </div>

                <div class="flex">
                    <!-- First div (2/3 width) -->
                    <div class="w-2/3 pr-4">
                        <div class="w-full">
                            <select
                                class="w-full text-black border border-gray-300 rounded-md py-2 px-4 mb-3 cursor-pointer"
                                name="budget-type">
                                @foreach ($budget_types as $budget_type)
                                    <option value="{{ $budget_type->id }}"
                                        @if ($budget_type->id == $user_budget->type) selected @endif>
                                        {{ $budget_type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Second div (1/3 width) -->
                    <div class="w-1/3 pl-4">
                        <div class="flex items-center">
                            <p class="text-black mr-2">Php</p>
                            <input type="number" min="1" name="alloc-budget" required
                                class="text-black w-full sm:w-28 border border-gray-300 rounded-md py-2 px-4">
                        </div>
                    </div>
                </div>


                <div class="flex justify-center mt-3">
                    <button type="submit"
                        class="mb-10 bg-gray-800 px-6 py-2 rounded-full text-white mr-2 hover:bg-gray-700 active:bg-gray-900">Save</button>
                </div>

            </form>
        </x-slot>
    </x-my-modal>

    <x-my-modal name="add-portion" width="max-w-xl" height="200">
        <x-slot name="header">
            <h1 class="text-2xl">
                Add Portion

            </h1>
        </x-slot>
        <x-slot name="body">
            <form action="{{ route('budgeting.add-portion') }}" method="post">
                @csrf
                @safesubmit

                <div class="w-full flex">
                    <!-- First div (2/3 width) -->
                    <div class="w-2/3 pr-4">
                        <div class="w-full">
                            <select name="category"
                                class="w-full text-black border border-gray-300 rounded-md py-2 px-4 mb-3 cursor-pointer">
                                @foreach ($categories as $category)
                                    @if (!in_array($category->id, $portion_categories))
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Second div (1/3 width) -->
                    <div class="w-1/3 pl-4">
                        <div class="flex items-center">
                            <p class="text-black mr-2">Php</p>
                            <input class="text-black w-full sm:w-32 border border-gray-300 rounded-md py-2 px-4"
                                type="number" min="1" name="portion" required>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center">
                    <button type="submit"
                        class="w-1/3 bg-gray-800 px-6 py-2 rounded-full text-white mt-3 mr-2 hover:bg-gray-700 active:bg-gray-900"
                        value="save">Add</button>

                </div>
            </form>
        </x-slot>
    </x-my-modal>

    <x-slot name="scripts">

        <script>
            const ctx = document.getElementById('portion-pie');
            const data = {
                labels: <?php echo $pie_labels; ?>,
                datasets: [{
                    label: 'Portion',
                    data: <?php echo $pie_datas; ?>,
                    borderWidth: 5,
                    backgroundColor: [
                        '#EF4444',
                        '#F97316',
                        '#FCD34D',
                        '#84CC16',
                        '#059669',
                        '#06B6D4',
                        '#3B82F6',
                        '#6366F1',
                        '#D946EF',
                    ],
                    borderColor: '#ffffff',
                }]
            };


            const config = {
                type: 'pie',
                data: data,
                options: {
                    // maintainAspectRation: false,
                    cutout: '40%',
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                align: 'center',
                                color: 'black'
                            }
                        },
                        labels: {
                            render: 'percentage',
                            fontColor: '#ffffff',
                            fontSize: 15
                        },
                    }
                }
            };

            new Chart(ctx, config);
        </script>

    </x-slot>
</x-app-layout>
