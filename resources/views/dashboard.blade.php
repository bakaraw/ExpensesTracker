<x-app-layout>
    @php
        $_budget_type = 0;

        switch ($budget_type) {
            case 1:
                $_budget_type = 'Day';
                break;
            case 2:
                $_budget_type = 'Week';
                break;
            case 3:
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
        <h1 class="mt-3 font-light text-lg text-gray-800 leading-tight">
            This {{ $_budget_type }}'s Expense Summary
        </h1>

    </x-slot>

    <div class="py-7">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Container for the financial data and "hello" div -->
            <div class="flex flex-col sm:flex-row justify-between">
                <!-- Financial data divs -->

                <div
                    class="flex items-center bg-gradient-to-b from-lime-400 to-green-600 overflow-hidden shadow-sm sm:rounded-lg flex-1 mr-4 p-4 text-white">

                    <div class="flex-1">
                        <p>Money In</p>
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
                    class="flex items-center bg-gradient-to-b from-amber-500 to-orange-600 overflow-hidden shadow-sm sm:rounded-lg flex-1 mr-4 p-4 text-white">
                    <div class="flex-1">
                        <p>Money Out</p>
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
                    class="flex items-center bg-gradient-to-b from-yellow-400 to-yellow-600 overflow-hidden shadow-sm sm:rounded-lg flex-1 mr-4 p-4 text-white">
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
                    class="flex flex-col justify-center bg-gradient-to-b from-slate-500 to-gray-700 overflow-hidden shadow-sm sm:rounded-lg flex-1 p-4 text-white">
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
                            {{$largest_spent_cat_name}}
                        @else
                            ---
                        @endif</p>
                </div>
            </div>

            <!-- Separate "hello" div on a new row -->
            <div class="my-5 sm:mt-0 flex flex-col sm:flex-row justify-center sm:justify-between">
                <!-- Chart div -->
                <div class="mt-4 bg-gray-200 p-4 rounded-lg w-full sm:w-2/3">
                    Chart here
                </div>

                <!-- Other div -->
                <div class="mt-4 bg-gray-200 p-4 rounded-lg w-full sm:w-1/3 sm:ml-4">
                    Other div
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
</x-app-layout>
