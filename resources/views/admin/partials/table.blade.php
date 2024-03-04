<a href="{{ route($route.'.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Nuevo</a>
<br />
<br />
<table class="table table-striped table-bordered data-table">
	<thead>
		@foreach($columns as $col)
			<th>{{$col['title']}}</th>
		@endforeach
		@if(Auth::user()->role == 'super_admin')
			<th>Usuario Padre</th>
		@endif
		<th>Actions</th>
	</thead>
	<tbody>
		@foreach($data as $d)
			<tr>
				@foreach($columns as $col)
				@php
					$key = $col['key'];
					$type = "text";
					if(array_key_exists("type", $col)){
						$type = $col['type'];
					}
				@endphp
                  <td>
                  	@switch($type)
                  		@case('text')
                  			{{$d->$key}}
                  		@break

                  		@case('img')
                  			@if(!empty($d->$key))
                  				<img src="{{ asset(str_replace('public','storage',$d->$key)) }}" class="img-thumbnail" style="width:100px; height: 100px;" alt="image">
                  			@else
                  				Sin Imagen
                  			@endif
                  		@break

                  		@case('replace_text')
                  			{{$col['data'][$d->$key]}}
                  		@break

                  		@case('relation')
                  			@php
                  				$format = "plain";
                  				$relation = $col['data']['relation'];
                  				$key = $col['data']['key'];
                  				if(array_key_exists("format",$col['data'])){
                  					$format = $col['data']['format'];
                  				}
                  			@endphp

                  			@if(@$format == 'plain')
                  				{{$d->$relation->$key}}
                  			@else
                  				{!!@$d->$relation->$key!!}
                  			@endif
                  			
                  		@break

                  		@case('date')
                  			@if(!empty($d->$key))
                  				{{ date($col['data']['format'], strtotime($d->$key)) }}
                  			@endif
                  		@break

                  		@case('html')
                  			{!!$d->$key!!}
                  		@break
                  	@endswitch
                  </td>


				@endforeach
				@if(Auth::user()->role == 'super_admin')
					@if(isset($d->user))
                  		<td>{{$d->user->name }} || {{$d->user->email }}</td>
                  	@else
                  		<td>-</td>
                  	@endif
				@endif
				<td>
					@include('admin.partials.actions',[ 'route'=>$route, 'id' => $d->id ])
				</td>
			</tr>
		@endforeach
	</tbody>
</table>