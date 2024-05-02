<x-newuser-layout>
    <div class="bg-gray-800">
        <h1>portion budget {{$budget}}</h1>
        <br>

        @foreach ($budget_portions as $budget_portion)
        @php
        // Find the corresponding category from $categories based on category ID
        $matching_category = $categories->firstWhere('id', $budget_portion->category);
        @endphp

        @if ($matching_category)
        <p>{{ $matching_category->name }}</p>
        @else
        <p>Uncategorized</p>
        @endif

        <p>Portion: {{ $budget_portion->portion }}</p>

        <br>
        @endforeach

        <button class="bg-gray-100 px-6 py-2 rounded-full text-black">Add</button>
        <button class="bg-yellow-500 px-6 py-2 rounded-full">Start Tracking</button>
    </div>



</x-newuser-layout>
