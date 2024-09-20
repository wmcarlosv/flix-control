@extends('adminlte::page')

@section('title', $title)

@section('content')
<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
      <div class="card card-success">
            <div class="card-header">
                <h2><i class="fas fa-list"></i> {{$title}}</h2>
            </div>
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Titulo:</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{@$data->title}}">
                        @error('title') 
                            <span class="error invalid-feedback">{{ $message }}</span> 
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Descripcion Corta:</label>
                        <input type="text" class="form-control @error('about') is-invalid @enderror" name="about" value="{{@$data->about}}">
                        @error('about') 
                            <span class="error invalid-feedback">{{ $message }}</span> 
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Logo:</label>
                        <input type="file" class="form-control @error('logo') is-invalid @enderror" name="logo" />
                        @error('logo') 
                            <span class="error invalid-feedback">{{ $message }}</span> 
                        @enderror
                        @if(!empty(@$data->logo))
                            <br />
                            <img src="{{asset(str_replace('public','storage',@$data->logo))}}" class="img-thumbnail" style="width:100px; height:100px;">
                        @endif
                    </div>
                    <!--<div class="form-group">
                        <label for="">Cover:</label>
                        <input type="file" class="form-control @error('cover') is-invalid @enderror" name="cover" />
                        @error('cover') 
                            <span class="error invalid-feedback">{{ $message }}</span> 
                        @enderror
                        @if(!empty(@$data->cover))
                            <br />
                            <img src="{{asset(str_replace('public','storage',@$data->cover))}}" class="img-thumbnail" style="width:100px; height:100px;">
                        @endif
                    </div>-->
                    <div class="form-group">
                        <label for="">Template para Notificar Expiracion de Cuenta:</label>
                        <p><span class="label label-success">Variables que puedes Usar:</span></p>
                        <p>
                            <a href="#" class="btn btn-info variable" data-textarea="expiration_template">#servicio</a>
                            <a href="#" class="btn btn-info variable" data-textarea="expiration_template">#cliente</a>
                            <a href="#" class="btn btn-info variable" data-textarea="expiration_template">#cuenta</a>
                            <a href="#" class="btn btn-info variable" data-textarea="expiration_template">#facturacion</a>
                            <a href="#" class="btn btn-info variable" data-textarea="expiration_template">#dias</a>
                            <a href="#" class="btn btn-info variable" data-textarea="expiration_template">#perfil</a>
                            <a href="#" class="btn btn-info variable" data-textarea="expiration_template">#pin</a>
                            <a href="#" class="btn btn-info variable" data-textarea="expiration_template">#clave_cuenta</a>
                        </p>
                        <textarea class="form-control @error('expiration_template') is-invalid @enderror" style="height: 220px;" name="expiration_template">{{@$data->expiration_template}}</textarea>
                        @error('expiration_template') 
                            <span class="error invalid-feedback">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">Template para enviar datos a Cliente:</label>
                        <p><span class="label label-success">Variables que puedes Usar:</span></p>
                        <p>
                            <a href="#" class="btn btn-info variable" data-textarea="customer_data_template">#servicio</a>
                            <a href="#" class="btn btn-info variable" data-textarea="customer_data_template">#cliente</a>
                            <a href="#" class="btn btn-info variable" data-textarea="customer_data_template">#cuenta</a>
                            <a href="#" class="btn btn-info variable" data-textarea="customer_data_template">#facturacion</a>
                            <a href="#" class="btn btn-info variable" data-textarea="customer_data_template">#dias</a>
                            <a href="#" class="btn btn-info variable" data-textarea="customer_data_template">#perfil</a>
                            <a href="#" class="btn btn-info variable" data-textarea="customer_data_template">#pin</a>
                            <a href="#" class="btn btn-info variable" data-textarea="customer_data_template">#clave_cuenta</a>
                        </p>
                        <textarea class="form-control @error('customer_data_template') is-invalid @enderror" style="height: 220px;" name="customer_data_template">{{@$data->customer_data_template}}</textarea>
                        @error('customer_data_template') 
                            <span class="error invalid-feedback">{{ $message }}</span> 
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Dias de aviso para Expiration de Subscripcion:</label>
                        <input type="text" class="form-control @error('expiration_days_subscriptions') is-invalid @enderror" name="expiration_days_subscriptions" value="{{@$data->expiration_days_subscriptions}}">
                        @error('expiration_days_subscriptions') 
                            <span class="error invalid-feedback">{{ $message }}</span> 
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Dias de aviso para Expiration de Cuentas:</label>
                        <input type="text" class="form-control @error('expiration_days_accounts') is-invalid @enderror" name="expiration_days_accounts" value="{{@$data->expiration_days_accounts}}">
                        @error('expiration_days_accounts') 
                            <span class="error invalid-feedback">{{ $message }}</span> 
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Moneda:</label>
                         @php
                            $cur = [];
                            if(!empty(@$data->currency)){
                                $cur = json_decode(@$data->currency, true);
                            }
                         @endphp
                        <select name="currency" class="form-control">
                            <option value="">Seleccione</option>
                            @foreach($currencies as $key=>$currency)
                                <option value="{{json_encode($currency)}}" @if(@$cur['name'] == $currency['name']) selected='selected' @endif>{{$currency['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Wachat Api Token:</label>
                        <input type="text" class="form-control @error('whatsapp_service_url') is-invalid @enderror" name="whatsapp_service_url" value="{{@$data->whatsapp_service_url}}">
                        @if(@$data->whatsapp_service_url)
                            <!--<br />
                            <button class="btn btn-success" id="button-whatsapp-connect" type="button">Conectar Whatsapp</button>-->
                        @endif
                        @error('whatsapp_service_url') 
                            <span class="error invalid-feedback">{{ $message }}</span> 
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <b>Horario de Notificacion:</b> <span>"Debes seleccionar el rango completo, de lo contrario no se almacenara"</span>
                        </div>
                        @php
                            $from = "";
                            $to = "";
                            if(!empty($data->hours_range_notification)){
                               $hours = explode("-",$data->hours_range_notification);
                                $from = $hours[0];
                                $to = $hours[1]; 
                            }
                        @endphp
                        <div class="col-md-6">
                            <input type="time" name="time_from" class="form-control" value="{{$from}}" />
                        </div>
                        <div class="col-md-6">
                            <input type="time" name="time_to" class="form-control" value="{{$to}}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Url de Ayuda:</label>
                        <input type="text" class="form-control @error('help_url') is-invalid @enderror" name="help_url" value="{{@$data->help_url}}">
                        @error('help_url') 
                            <span class="error invalid-feedback">{{ $message }}</span> 
                        @enderror
                    </div>

                    <a href="{{route('downloadBackup')}}" class="btn btn-success">Descargar Respaldo de Base de Datos</a>
                </div>
                <div class="card-footer">
                    @include('admin.partials.buttons',['cancelRoute'=>'dashboard'])
                </div>
            </form>
        </div>
    </div>
</div>


<!-- The Modal -->
<div class="modal" id="whatsapp-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Conectar con Whatsapp (<span id="whatsapp-status">@if(@$data->isLogged) Conectado @else Desconectado @endif </span>)</h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="col-md-12 text-center" id="status-content">
            @if(@$data->isLogged)
                <img src="{{asset('images/connected.png')}}" style="width:150px; height:150px;">
            @else
                <img src="{{asset('images/disconnect.png')}}" style="width:150px; height:150px;">
            @endif
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button class="btn btn-success" id="connect-whatsapp" @if(@$data->isLogged) style='display:none;' @endif type="button">Conectar</button>
        <button type="button" class="btn btn-danger" id="close-modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>
@stop

@section('js')
    @if(@$data->whatsapp_service_url)
        <script src="{{@$data->whatsapp_service_url}}/socket.io/socket.io.js"></script>
    @endif
    @include('admin.partials.messages')
    <script type="text/javascript">
        $(document).ready(function(){
            $("select[name='currency']").select2();
            var pulseConnected = false;

            @if(@$data->whatsapp_service_url)
                const socket = io('{{@$data->whatsapp_service_url}}');
                socket.on('qrCode', (dataJson)=>{
                    const data = dataJson;
                    if (data.url) {
                        if(pulseConnected){
                            document.getElementById('status-content').innerHTML = `<img src="${data.url}" alt="QR Code" style="width: 200px; height: 200px;">`;
                        }
                    }
                });

                socket.on('isConnect', (data)=>{
                    let value;
                    if(data){
                        value = 1;
                        $("#connect-whatsapp").hide();
                        $("#whatsapp-status").text("Conectado");
                        document.getElementById('status-content').innerHTML = `<img src="/images/connected.png" alt="QR Code" style="width: 150px; height: 150px;">`;
                    }else{
                        value = 0;
                        $("#connect-whatsapp").attr("disabled", false).text("Conectar").show();
                        $("#whatsapp-status").text("Desconectado");
                        pulseConnected = false;
                        document.getElementById('status-content').innerHTML = `<img src="/images/disconnect.png" alt="QR Code" style="width: 150px; height: 150px;">`;
                    }

                    changeStatus(value);
                });
            @endif

            $("#button-whatsapp-connect").click(function(){
                $("#whatsapp-modal").modal({'keyboard': false, 'backdrop':'static'},"show");
            });

            $("#close-modal").click(function(){
                ulseConnected = false;
                $("#connect-whatsapp").attr("disabled", false).text("Conectar");
                $("#whatsapp-modal").modal("hide");
            });

            $("#connect-whatsapp").click(function(){
                pulseConnected = true;
                $("#status-content").html('<img src="{{asset('images/loading.gif')}}" style="width:150px; height:150px;">');
                $(this).attr("disabled", true).text("Conectando...");
                $.get("{{@$data->whatsapp_service_url}}/get-last-qr", function(response){
                    console.log(response)
                 });
            });

            $("a.variable").click(function(){
                var nm = $(this).attr("data-textarea");
                var textarea = $("textarea[name='"+nm+"']");
                var currentPos = textarea.prop('selectionStart');
                var textToAdd = $(this).text();
                var currentText = textarea.val();
                var newText = currentText.substring(0, currentPos) + textToAdd + currentText.substring(currentPos);
                textarea.val(newText);
                textarea.prop('selectionStart', currentPos + textToAdd.length);
                textarea.prop('selectionEnd', currentPos + textToAdd.length);
                textarea.focus();
                return false;
            });

            function changeStatus(data){
                $.post('{{route("whastapp_logged")}}',{ logged: data }, function(response){
                    console.log(response);
                });
            }
        });
    </script>
@stop