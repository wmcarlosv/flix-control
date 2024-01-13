<a href="{{ route($route.'.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> New</a>
<br />
<br />
<table class="table table-striped table-bordered data-table">
	<thead>
		@foreach($columns as $col)
			<th>{{$col['title']}}</th>
		@endforeach
		<th>Actions</th>
	</thead>
	<tbody>
		@foreach($data as $d)
			<tr>
				@foreach($columns as $col)
				@php
					$key = $col['key'];
				@endphp
                  <td>{{ $d->$key }}</td>
				@endforeach
				<td>
					@include('admin.partials.actions',[ 'route'=>$route, 'id' => $d->id ])
				</td>
			</tr>
		@endforeach
	</tbody>
</table>