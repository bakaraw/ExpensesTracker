<x-newuser-layout>
    <span class="md:text-6xl font-black uppercase bg-clip-text decoration-clone bg-gradient-to-b from-yellow-400 to-red-500 text-transparent">Welcome {{ $user['name'] }}!</span>
    <h1 class="text-xl mt-5 w-[500px]">To start things up, we will ask you just a few questions to set up your expenses tracker</h1>
    <a href="{{ route('new_user.set-up') }}" class="mt-10 bg-yellow-500 py-2 hover:bg-yellow-600 active:bg-yellow-300 rounded-full shadow-md w-52 flex justify-center">Next</a>
</x-newuser-layout>
