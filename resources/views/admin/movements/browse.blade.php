@extends('adminlte::page')

@section('title', $title)

@section('content')
    <div class="row" style="margin-top:20px;">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h2><i class="fas fa-calculator"></i> {{ $title }}</h2>
                </div>
                <div class="card-body">
                    @include('admin.partials.table',['columns'=>$columns, 'data'=>$data, 'route'=>'movements'])
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    @include('admin.partials.messages')
    <script>
        $(document).ready(function(){
            currentTable.destroy();
            currentTable = $(".data-table").DataTable({ order: [[0, 'desc']] });
        });
    </script>
@stop