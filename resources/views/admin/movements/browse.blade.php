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
                    <div>
                        <label class="d-block mb-2">Buscar por rango de Fecha:</label>
                        <form method="GET">
                        <div class="form-inline">
                            <div class="form-group mb-2 mr-2">
                                <label for="desde" class="mr-2">Desde:</label>
                                <input type="date" class="form-control" value="{{$desde}}" id="desde" name="desde">
                            </div>
                            <div class="form-group mb-2 mr-2">
                                <label for="hasta" class="mr-2">Hasta:</label>
                                <input type="date" class="form-control" value="{{$hasta}}" id="hasta" name="hasta">
                            </div>
                            <button type="submit" class="btn btn-primary mb-2">Enviar</button>
                            <a href="{{route('movements.index')}}" style="margin-left: 10px;" class="btn btn-danger mb-2">Limpiar filtros</a>
                        </div>
                        </form>
                    </div>
                    <h3 style="margin: 10px;">Total de Movimientos: {{$total}}</h3>
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
            currentTable = $(".data-table").DataTable({ order: [[0, 'desc']] }, paging: false);
        });
    </script>
@stop