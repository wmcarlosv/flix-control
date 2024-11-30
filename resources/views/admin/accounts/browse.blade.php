@extends('adminlte::page')

@section('title', $title)

@section('css')
<style>
    a.items {
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
                    <button class="btn btn-info" type="button" data-toggle="modal" data-target="#importModal">
                        <i class="fas fa-file-import"></i> Importar Cuentas
                    </button>
                    @include('admin.partials.table',['columns'=>$columns, 'data'=>$data, 'route'=>'accounts'])
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for CSV Upload -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Importar Cuentas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="csvUploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="csvFile">Cargar Archivo CSV</label>
                            <input type="file" name="csvFile" id="csvFile" class="form-control" accept=".csv" required>
                        </div>
                        <div id="csvPreview" style="margin-top: 20px; display: none;">
                            <h5>Contenido del Archivo:</h5>
                            <div class="table-responsive">
                                <table class="table table-striped" id="csvTable">
                                    <thead>
                                        <tr>
                                            <th>Servicio</th>
                                            <th>Email</th>
                                            <th>Clave Email</th>
                                            <th>Clave Cuenta</th>
                                            <th>Precio Compra</th>
                                            <th>Precio Venta</th>
                                            <th>Precio Venta Perfil</th>
                                            <th>Facturacion</th>
                                            <th>Vendedor</th>
                                            <th>Vencimiento Vendedor</th>
                                            <th>Visible en Tienda</th>
                                            <th>Tipo de Venta</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- CSV content will be inserted here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Cargar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    @include('admin.partials.messages')
    <script>
        $(document).ready(function() {
            // Add CSRF token to every AJAX request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#csvUploadForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: '{{ route("accounts.upload") }}', // Update with your route
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#csvPreview').show();
                            let tableBody = $('#csvTable tbody');
                            tableBody.empty();
                            response.data.forEach(row => {
                                let rowHtml = `<tr>
                                    <td>${row.Servicio}</td>
                                    <td>${row.Email}</td>
                                    <td>${row.ClaveEmail}</td>
                                    <td>${row.ClaveCuenta}</td>
                                    <td>${row.PrecioCompra}</td>
                                    <td>${row.PrecioVenta}</td>
                                    <td>${row.PrecioVentaPerfil}</td>
                                    <td>${row.Facturacion}</td>
                                    <td>${row.Vendedor}</td>
                                    <td>${row.VencimientoVendedor}</td>
                                    <td>${row.VisibleEnTienda}</td>
                                    <td>${row.TipoDeVenta}</td>
                                </tr>`;
                                tableBody.append(rowHtml);
                            });
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Error al cargar el archivo.');
                    }
                });
            });
        });
    </script>
@stop
