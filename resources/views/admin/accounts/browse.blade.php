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
                    <div class="modal-body">
                        <form id="csvUploadForm" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="csvFile">Cargar Archivo Excel</label>
                                <input type="file" name="csvFile" id="csvFile" class="form-control" accept=".xlxs" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Cargar</button>
                        </form>
                        <div id="csvPreview" style="margin-top: 20px; display: none;">
                            <h5>Contenido del Archivo:</h5>
                            <div class="table-responsive">
                                <form id="importForm" method="POST" action="{{route('accounts.imports')}}">
                                    @csrf
                                    <table class="table table-striped" id="csvTable">
                                        <thead>
                                            <tr>
                                                <th>Servicio</th>
                                                <th>Email</th>
                                                <th>Clave Email</th>
                                                <th>Clave Cuenta</th>
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
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                         <button type="submit" id="importButton" style="display:none;" class="btn btn-primary">Importar</button>
                        <button type="button" class="btn btn-secondary" id="close-import-modal">Cerrar</button>
                    </div>
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

            $("#close-import-modal").click(function(){
                $("#importModal").modal('hide');
                $("#csvTable tbody").empty();
                $("#csvPreview").hide();
                $("#csvFile").val(null);
            });

            $("#importButton").click(function(){
                $("#importForm").submit();
            });

            $('#csvUploadForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let tableBody = $('#csvTable tbody');
                $.ajax({
                    url: '{{ route("accounts.upload") }}', // Update with your route
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#csvPreview').show();
                            $("#importButton").show();
                            tableBody.empty();
                            response.data.forEach(row => {
                                let rowHtml = `<tr>
                                    <td><input type='hidden' name='data["servicio"]' value='${row.Servicio}' />${row.Servicio}</td>
                                    <td><input type='hidden' name='data["email"]' value='${row.Email}' />${row.Email}</td>
                                    <td><input type='hidden' name='data["clave_email"]' value='${row.ClaveEmail}' />${row.ClaveEmail}</td>
                                    <td><input type='hidden' name='data["clave_cuenta"]' value='${row.ClaveCuenta}' />${row.ClaveCuenta}</td>
                                    <td><input type='hidden' name='data["precio_venta"]' value='${row.PrecioVenta}' />${row.PrecioVenta}</td>
                                    <td><input type='hidden' name='data["precio_venta_perfil"]' value='${row.PrecioVentaPerfil}' />${row.PrecioVentaPerfil}</td>
                                    <td><input type='hidden' name='data["facturacion"]' value='${row.Facturacion}' />${row.Facturacion}</td>
                                    <td><input type='hidden' name='data["vendedor"]' value='${row.Vendedor}' />${row.Vendedor}</td>
                                    <td><input type='hidden' name='data["vencimiento_vendedor"]' value='${row.VencimientoVendedor}' />${row.VencimientoVendedor}</td>
                                    <td><input type='hidden' name='data["visible_tienda"]' value='${row.VisibleEnTienda}' />${row.VisibleEnTienda}</td>
                                    <td><input type='hidden' name='data["tipo_venta"]' value='${row.TipoDeVenta}' />${row.TipoDeVenta}</td>
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
