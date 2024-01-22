@if($type == 'edit')
	<form action="{{ route($element.'.update', $id) }}" autocomplete="off" method="POST" enctype="multipart/form-data">
		@method('PUT')
@else
	<form action="{{ route($element.'.store') }}" autocomplete="off" method="POST" enctype="multipart/form-data">
		@method('POST')
@endif
	@csrf