<a href="{{ route($route.'.edit', $id) }}" data-id='{{$id}}' class="btn btn-info"><i class="fas fa-edit"></i></a>
<form style="display:inline;" method="POST" action="{{ route($route.'.destroy', $id) }}" id="form_{{$id}}">
	@method('DELETE')
	@csrf
	<button type="button" class="btn btn-danger delete-record" data-id="{{$id}}"><i class="fas fa-times"></i></button>
</form>