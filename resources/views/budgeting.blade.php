<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Budgeting
        </h2>

    </x-slot>
    <div class="py-7">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-x-4">
                <div class="w-3/5 bg-gray-100 flex-shrink-0 flex flex-col">
                    <div class="flex gap-x-4">
                        <div class="w-1/2 bg-gradient-to-b from-slate-500 to-gray-700 rounded-lg p-4 shadow-md text-white">
                            <h1 class="font-bold text-ultrablack text-2xl">
                                PHP
                                @if($alloc_budget > 0)
                                {{$alloc_budget}}
                                @else
                                0.00
                                @endif
                            </h1>
                            <p class="font-thin">
                                Total Budget
                            </p>
                        </div>
                        <div class="w-1/2 bg-gradient-to-b from-amber-500 to-orange-600 rounded-lg p-4 shadow-md text-white">
                            <h1 class="font-bold text-ultrablack text-2xl">
                                PHP 0.00
                            </h1>
                            <p class="font-thin">
                                Total Expenses
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 w-full bg-white rounded-lg p-4 flex-grow ">
                        <h1 class="font-semibold text-lg">Budget Portions</h1>
                        <div>
                            @foreach ($budget_portions as $budget_portion)
                                <p>{{$budget_portion->category->name}} - {{$budget_portion->portion}}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="w-2/5 bg-gray-100 flex-shrink-0 flex flex-col">
                    <div class="w-full bg-white h-full rounded-lg p-4">
                        wow
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">

    </x-slot>
</x-app-layout>
