@php
	$settings = \App\Models\Setting::first();
	if(!$settings){
		$settings = [];
	}
@endphp
@if($route == 'movements' && Auth::user()->role == 'reseller')
	@if($settings->allow_reseller_ae_movements == 1)
		<a href="{{ route($route.'.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Nuevo</a>
	@endif
@else
	<a href="{{ route($route.'.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Nuevo</a>
@endif
@if(array_key_exists('type', $columns[0]) and $columns[0]['type'] == 'check')
	<form method="POST" style="display:inline;" id="massive_form" action="{{route($route.'.delete_massive')}}">
		@method('POST')
		@csrf
		<input type="hidden" id="selected_rows" name="selected_rows" />
		<button type="button" id="delete_massive" class="btn btn-danger"><i class="fas fa-times"></i> Eliminar Masivo</button>
	</form>
@endif
<br />
<br />
<div class="table-responsive">
	<table class="table table-striped table-bordered data-table">
		<thead>
			@foreach($columns as $col)
				<th>
					@if(array_key_exists('type', $col) and $col['type'] == 'check')
						<input type="checkbox" id="select_all" />
					@else
						{{$col['title']}}
					@endif
				</th>
			@endforeach
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
	                  			@if($d->$key)
	                  				{{$d->$key}}
	                  			@else
	                  				-
	                  			@endif
	                  		@break

	                  		@case('currency')
	                  			{{$col['data']['symbol']}} {{number_format($d->$key,2,',','.')}}
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
	                  				@if(!empty($d->$relation))
	                  					{{@$d->$relation->$key}}
	                  				@else
	                  					@if(array_key_exists("default_text", $col['data']))
	                  						{{$col['data']['default_text']}}
	                  					@endif
	                  				@endif
	                  			@else
	                  				@if(!empty($d->$relation))
	                  					{!!@$d->$relation->$key!!}
	                  				@else
	                  					@if(array_key_exists("default_text", $col['data']))
	                  						{{$col['data']['default_text']}}
	                  					@endif
	                  				@endif
	                  			@endif
	                  			
	                  		@break

	                  		@case('date')
	                  			@if(!empty($d->$key))
	                  				{{ date($col['data']['format'], strtotime($d->$key)) }}
	                  			@else
	                  				-
	                  			@endif
	                  		@break

	                  		@case('html')
	                  			{!!$d->$key!!}
	                  		@break
	                  		@case('check')
	                  			<input type="checkbox" name="multi_select[]" value="{!!$d->$key!!}">
	                  		@break
	                  	@endswitch
	                  </td>
					@endforeach
					<td>
						@if($route == 'movements' && Auth::user()->role == 'reseller')
							@if($settings->allow_reseller_ae_movements == 1)
								@include('admin.partials.actions',[ 'route'=>$route, 'id' => $d->id ])
							@endif
						@else
							@include('admin.partials.actions',[ 'route'=>$route, 'id' => $d->id ])
						@endif 
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>