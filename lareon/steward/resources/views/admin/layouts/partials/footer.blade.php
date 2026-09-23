<footer>
</footer>
<form action="{{route('admin.auth.logout')}}" method="POST" id="logoutForm">
    @method('DELETE')
    @csrf
</form>
