@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="info-box bg-success">
              <span class="info-box-icon"><i class="fas fa-sort-numeric-up"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Ingresos</span>
                <span class="info-box-number">0</span>
              </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-danger">
              <span class="info-box-icon"><i class="fas fa-sort-numeric-down-alt"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Gastos</span>
                <span class="info-box-number">0</span>
              </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box bg-info">
              <span class="info-box-icon"><i class="far fa-money-bill-alt"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Balance</span>
                <span class="info-box-number">0</span>
              </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
            <div class="card-header">
                <h3>Ultimos Movimientos</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <th>Tipo</th>
                        <th>Descripcion</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function(){
            $("table").DataTable();
        });
    </script>
@stop