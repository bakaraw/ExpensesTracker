<x-newuser-layout>
    <span class="text-6xl font-black uppercase bg-clip-text decoration-clone bg-gradient-to-b from-yellow-400 to-red-500 text-transparent">Welcome {{ $user['name'] }}!</span>
    <h1 class="text-xl mt-5">To start things up, we will ask you just a few questions to set up your expenses tracker</h1>
    <a href="{{ route('new_user.set-up') }}" class="mt-5 bg-yellow-500">Next</a>
    {{-- <form action="{{ route('new_user.submit') }}" method="post">
        @csrf
        <input type="text" name="budget_type" id="" placeholder="type">
        <input type="number" name="alloc_budget" id="" placeholder="alloc budget">
        <button type="submit">Go Go GO</button>
    </form> --}}
</x-newuser-layout>
