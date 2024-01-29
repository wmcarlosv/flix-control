@extends('adminlte::page')

@section('title', $title)

@section('content')
<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
      <div class="card card-success">
            <div class="card-header">
                <h2><i class="fas fa-list"></i> {{$title}}</h2>
            </div>
            <form action="{{ route('config.update') }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="card-body">
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
                        <textarea class="form-control @error('expiration_template') is-invalid @enderror" style="height: 200px;" name="expiration_template">{{@$data->expiration_template}}</textarea>
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
                        <textarea class="form-control @error('customer_data_template') is-invalid @enderror" style="height: 200px;" name="customer_data_template">{{@$data->customer_data_template}}</textarea>
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
                </div>
                <div class="card-footer">
                    @include('admin.partials.buttons',['cancelRoute'=>'dashboard'])
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
    @include('admin.partials.messages')
        <script type="text/javascript">
        $(document).ready(function(){
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
            })
        });
    </script>
@stop