@extends('adminlte::page')

@section('title', $title)

@section('css')
<style>
    a.items{
        margin: 5px !important;
    }
</style>
@stop

@section('content')
    <div class="row" style="margin-top:20px;">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h2><i class="fas fa-piggy-bank"></i> {{ $title }}</h2>
                </div>
                <div class="card-body">
                    @include('admin.partials.table',['columns'=>$columns, 'data'=>$data, 'route'=>'accounts'])
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    @include('admin.partials.messages')
@stop