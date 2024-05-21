<x-newuser-layout>
    <div class="bg-gray-500 bg-opacity-15 p-5 shadow-md rounded-lg grid grid-cols-3">
        <h1 class="text-4xl font-black uppercase col-span-3">Portion Budget</h1>

        <h1 class="text-xl mb-3">Php {{ $budget }}</h1>
        <br>
        @php
            $portion_categories = [];
        @endphp

        @foreach ($budget_portions as $budget_portion)
            @php
                // Find the corresponding category from $categories based on category ID
                array_push($portion_categories, $budget_portion->category_id);
                $matching_category = $categories->firstWhere('id', $budget_portion->category_id);
            @endphp
            <button x-data
                x-on:click="$dispatch('open-modal' , {name : 'portion-modal-{{ $budget_portion->portion_id }}'})"
                class="bg-white drop-shadow-md mb-3 px-7 py-2 rounded-full flex justify-between items-center col-span-3 hover:bg-lime-200 active:bg-lime-400 cursor-pointer">
                @if ($matching_category)
                    <i class="fa-solid {{ $matching_category->icon }}"></i>
                    <p>{{ $matching_category->name }}</p>
                @else
                    <p>Uncategorized</p>
                @endif
                <p>Php {{ $budget_portion->portion }}</p>
            </button>

            <x-my-modal name="portion-modal-{{ $budget_portion->portion_id }}" width="max-w-lg" height="180">

                <x-slot name="header">
                    <h1 class="text-black text-2xl">
                        <i class="fa-solid {{ $matching_category->icon }} pr-3"></i>{{ $matching_category->name }}
                    </h1>
                </x-slot>

                <x-slot name="body">

                    <form action="{{ route('save.edited-portion') }}" method="post">
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

        <x-my-modal name="add-portion" width="max-w-xl" height="200">

            <x-slot name="header">
                <h1 class="text-black text-2xl">
                    Add portion
                </h1>
            </x-slot>

            <x-slot name="body">
                <form action="{{ route('save.added-portion') }}" method="post">
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
                            value="save">Save</button>

                    </div>
                </form>

            </x-slot>

        </x-my-modal>

        <div class="col-span-3">
            <div class="mb-6 flex">
                <button x-data x-on:click="$dispatch('open-modal' , {name : 'add-portion'})"
                    class="w-full bg-gray-600 px-6 py-2 rounded-full hover:bg-gray-700 hover:text-gray-100 shadow-md text-white">
                    <i class="fa-solid fas fa-plus"></i>
                </button>
            </div>
            <div class="flex">
                <a href="{{ route('dashboard') }}"
                    class="w-full bg-yellow-500 hover:bg-yellow-600 active:bg-yellow-700 shadow-md px-6 py-2 rounded-full text-white text-center">Start Tracking</a>
            </div>
        </div>
    </div>


</x-newuser-layout>
