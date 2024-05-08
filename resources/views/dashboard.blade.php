<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Dashboard
        </h2>
        Welcome {{ Auth::user()->name }}!
    </x-slot>

    <div class="py-7">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Container for the financial data and "hello" div -->
            <div class="flex flex-col sm:flex-row justify-between">
                <!-- Financial data divs -->
                <div class="flex flex-col justify-center bg-gradient-to-b from-lime-400 to-green-600 overflow-hidden shadow-sm sm:rounded-lg flex-1 mr-4 p-4 text-white">
                    <p>Money In</p>
                    <h2 class="font-semibold text-xl text-white leading-tight">Php 0.00</h2>
                </div>
                <div class="flex flex-col justify-center bg-gradient-to-b from-amber-500 to-orange-600 overflow-hidden shadow-sm sm:rounded-lg flex-1 mr-4 p-4 text-white">
                    <p>Money Out</p>
                    <h2 class="font-semibold text-xl text-white leading-tight">Php 0.00</h2>
                </div>
                <div class="flex flex-col justify-center bg-gradient-to-b from-yellow-400 to-yellow-600 overflow-hidden shadow-sm sm:rounded-lg flex-1 mr-4 p-4 text-white">
                    <p>Savings</p>
                    <h2 class="font-semibold text-xl text-white leading-tight">Php 0.00</h2>
                </div>
                <div class="flex flex-col justify-center bg-gradient-to-b from-slate-500 to-gray-700 overflow-hidden shadow-sm sm:rounded-lg flex-1 p-4 text-white">
                    <p>Largest Spent</p>
                    <h2 class="font-semibold text-xl text-white leading-tight">Php 0.00</h2>
                    <p class="font-small">Food</p>
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
    </div>
</x-app-layout>
