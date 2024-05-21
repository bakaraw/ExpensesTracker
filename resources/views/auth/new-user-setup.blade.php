<x-newuser-layout>
    <div class="flex justify-left w-full sm:max-w-md mt-6 px-6 py-4 bg-gray-400 shadow-md overflow-hidden sm:rounded-lg bg-opacity-25">
        <form action="{{ route('new_user.submit') }}" method="post" class="w-full">
            @csrf
            <h1 class="mb-2">How do you want to track your budget?</h1>
            <select name="budget_type" class="rounded-lg text-black flex-initial mb-4">
                <option selected value="1">Daily</option>
                <option value="2">Weekly</option>
                <option value="3">Monthly</option>
            </select>
            <br>
            {{-- <input type="text" name="budget_type" id="" placeholder="type"> --}}
            <h1 class="mb-2">How much budget do you have?</h1>
            <input class="rounded-lg text-black mb-7" type="number" name="alloc_budget" placeholder="alloc budget" min="0" pattern="\d*" required>
            <br>
            <button class="w-full rounded-full py-3 px-6 bg-yellow-500" type="submit">Next</button>
        </form>
    </div>
</x-newuser-layout>
