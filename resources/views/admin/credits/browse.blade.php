@extends('adminlte::page')

@section('title', $title)

@section('content')
    <div class="row" style="margin-top:20px;">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h2><i class="fas fa-dollar-sign"></i> {{ $title }}</h2>
                </div>
                <div class="card-body">
                    @include('admin.partials.table',['columns'=>$columns, 'data'=>$data, 'route'=>'credits'])
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    @include('admin.partials.messages')
    <script>
        $(document).ready(function(){
            let parent = $("a.btn-info").parent();
            $("a.btn-info").remove();
            $("form").remove();
            parent.text("Sin Acciones");
        });
    </script>
@stop