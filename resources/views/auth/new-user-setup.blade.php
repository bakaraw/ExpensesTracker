<h1>Hello new user</h1>
<form action="{{route('new_user.submit')}}" method="post">
    @csrf
    <input type="text" name="budget_type" id="" placeholder="type">
    <input type="number" name="alloc_budget" id="" placeholder="alloc budget">
    <button type="submit">Go Go GO</button>
</form>
