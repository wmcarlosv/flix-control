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
                <span class="info-box-number">$ {{number_format($input,2,',','.')}}</span>
              </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-danger">
              <span class="info-box-icon"><i class="fas fa-sort-numeric-down-alt"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Gastos</span>
                <span class="info-box-number">$ {{number_format($output,2,',','.')}}</span>
              </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box bg-info">
              <span class="info-box-icon"><i class="far fa-money-bill-alt"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Balance</span>
                <span class="info-box-number">$ {{number_format($balance,2,',','.')}}</span>
              </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="last_movements-tab" data-toggle="tab" href="#last_movements" role="tab" aria-controls="last_movements"
                  aria-selected="true">Ultimos Movimientos</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="expirations_subscriptions-tab" data-toggle="tab" href="#expirations_subscriptions" role="tab" aria-controls="expirations_subscriptions"
                  aria-selected="false">Subscripciones por Vencer</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="expirations_accounts-tab" data-toggle="tab" href="#expirations_accounts" role="tab" aria-controls="expirations_accounts"
                  aria-selected="false">Cuentas por Vencer</a>
              </li>
            </ul>

            <div class="tab-content" id="myTabContent">

                <div class="tab-pane fade show active" id="last_movements" role="tabpanel" aria-labelledby="last_movements-tab">
                     <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="table-last_movements">
                                <thead>
                                    <th>#</th>
                                    <th>Tipo</th>
                                    <th>Descripcion</th>
                                    <th>Monto</th>
                                    <th>Fecha</th>
                                </thead>
                                <tbody>
                                    @foreach($movements as $mv)
                                    <tr>
                                        <td>{{$mv->id}}</td>
                                        <td>
                                            @if($mv->type == 'input')
                                                Entrada
                                            @else
                                                Salida
                                            @endif
                                        </td>
                                        <td>{{$mv->description}}</td>
                                        <td>$ {{number_format($mv->amount,2,',','.')}}</td>
                                        <td>{{date('d-m-Y', strtotime($mv->datemovement))}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div> 
                </div>

                <div class="tab-pane fade" id="expirations_subscriptions" role="tabpanel" aria-labelledby="expirations_subscriptions-tab">
                    <div class="card">
                        <div class="card-body">
                          <table class="table table-bordered table-striped" id="table-expirations_subscriptions">
                               <thead>
                                   <th>Servicio</th>
                                   <th>Cuenta (email)</th>
                                   <th>Cliente</th>
                                   <th>Dias Restantes</th>
                                   <th>Facturacion</th>
                                   <th>Acciones</th>
                               </thead>
                               <tbody>
                                   @if($expirations_subscriptions)
                                    @foreach($expirations_subscriptions as $es)
                                        <tr>
                                            <td>
                                                @if(!empty($es->service->cover))
                                                <img src="{{asset(str_replace('public','storage',$es->service->cover))}}" class="img-thumbnail" style="width:75px; height:75px;">@endif {{$es->service->name}}        
                                            </td>
                                            <td>{{$es->account->email}}</td>
                                            <td><a href="{{route('customers.edit',$es->customer->id)}}" target="_blank">{{$es->customer->name}}</a></td>
                                            <td>{{$es->last_days}}</td>
                                            <td>{{date('d/m/Y',strtotime($es->date_to))}}</td>
                                            <td>
                                                <a href="#" class="btn btn-info copy-data" data-id='{{$es->id}}' title="Copiar Informacion de Expiracion"><i class="fas fa-copy"></i></a>
                                                <a href="#" class="btn btn-success send-by-whatsapp" data-id='{{$es->id}}' data-number="{{$es->customer->phone}}" title="Notificar por Whatsapp"><i class="fab fa-whatsapp"></i></a>
                                                <a href="#" class="btn btn-warning modal-edit" data-subscription='{{json_encode($es)}}' title="Extender Membresia"><i class="fas fa-expand-arrows-alt"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                   @endif
                               </tbody>
                           </table>  
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="expirations_accounts" role="tabpanel" aria-labelledby="expirations_accounts-tab">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="table-expirations_accounts">
                                <thead>
                                    <th>Sercicio</th>
                                    <th>Cuenta (Email)</th>
                                    <th>Dias Restantes</th>
                                    <th>Facturacion</th>
                                </thead>
                                <tbody>
                                     @if($accounts)
                                        @foreach($accounts as $acc)
                                            <tr>
                                                <td>
                                                    @if(!empty($acc->service->cover))
                                                    <img src="{{asset(str_replace('public','storage',$acc->service->cover))}}" class="img-thumbnail" style="width:75px; height:75px;">@endif {{$acc->service->name}}        
                                                </td>
                                                <td><a target="_blank" href="{{route('accounts.edit',$acc->id)}}">{{$acc->email}}</a></td>
                                                <td>{{$acc->last_days}}</td>
                                                <td>{{date('d/m/Y',strtotime($acc->dateto))}}</td>
                                            </tr>
                                        @endforeach
                                     @endif
                                </tbody>
                            </table>  
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

     <!-- Modal -->
    @include('admin.partials.modal-edit-customer')
@stop

@section('js')
    <script>
        $(document).ready(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });


            $("#table-last_movements").DataTable({ order: [[0, 'desc']] });
            $("#table-expirations_subscriptions").DataTable({ order: [[3, 'asc']] });
            $("#table-expirations_accounts").DataTable({ order: [[2, 'asc']] });

            $("body").on('click','a.copy-data', function(){
                let id = $(this).attr("data-id");
                $.get("get-expiration-message/"+id, function(response){
                    let data = response;
                    if(data.success){
                        copyToClipboard(data.message);
                        Swal.fire({
                            title: "Notificacion",
                            text: "Se han copiado los datos correctamente!!",
                            icon: "success",
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }else{
                        Swal.fire({
                            title: "Notificacion",
                            text: data.messasge,
                            icon: "error"
                        });
                    }
                });
            });

            $("body").on('click','a.send-by-whatsapp', function(){
                let id = $(this).attr("data-id");
                let phone = $(this).attr("data-number");

                $.get("get-expiration-message/"+id, function(response){
                    let data = response;
                    if(data.success){
                        let link = "https://wa.me/"+phone+"?text="+data.message;
                        window.open(link, "_blank");
                    }else{
                        Swal.fire({
                            title: "Notificacion",
                            text: data.messasge,
                            icon: "error"
                        });
                    }
                });
            });

            function copyToClipboard(text) {
                var $temp = $("<textarea></textarea>");
                $("body").append($temp);
                $temp.val(text).select();
                document.execCommand('copy');
                $temp.remove();
            }

            $("body").on('click','a.modal-edit', function(){
                let data = JSON.parse($(this).attr("data-subscription"));
                $("#edit_subscription_id").val(data.id);
                $("#edit_active_free_profile").val($(this).attr("id"));
                $("#edit_customer_id").val(data.customer_id);
                $("#edit_profile").val(data.profile);
                $("#edit_pin").val(data.pin);
                $("#edit_date_to").val(data.date_to);
                $("#extend_edit_date_to").val(data.date_to);
                $("#last_days").val(data.last_days);
                $("#modal-edit").modal('show');
            });

            $(".close-edit-subscription").click(function(){
                $("#edit_service_id, #edit_account_id, #edit_customer_id, #edit_amount, #edit_date_to, #edit_profile, #edit_pin, #edit_active_free_profile, #last_days").val("");
                $("#modal-edit").modal('hide');
            });

            $("#save-edit-subscription").click(function(){
                let subscription_id = $("#edit_subscription_id").val();
                let customer_id = $("#edit_customer_id").val();
                let profile = $("#edit_profile").val();
                let pin = $("#edit_pin").val();

                if(customer_id){

                    $.post("{{route('subscriptions.update_data')}}",{
                            id:subscription_id,
                            customer_id:customer_id,
                            profile:profile,
                            pin:pin
                        }, function(response){
                            let data = response;
                            let addClass = "btn-success";

                            Swal.fire({
                                title: "Notificacion",
                                text: data.message+", la pagina se recargara en 3 segundos!!",
                                icon: data.type
                            });

                            setTimeout(function(){
                              location.reload();
                            }, 3000);

                            

                            $("#edit_subscription_id, #edit_customer_id, #edit_date_to, #edit_profile, #edit_pin, #edit_active_free_profile, #last_days").val("");
                            $("#modal-edit").modal('hide');
                        });
                }else{
                    Swal.fire({
                        title: "Notificacion",
                        text: "El cliente es obligatorio",
                        icon: "error"
                    });
                }
            });

            $("#save-extend-subscription").click(function(){

                let subscription_id = $("#edit_subscription_id").val();
                let extend_amount = $("#extend_amount").val();
                let extend_date_to = $("#extend_edit_date_to").val();

                if(extend_amount && extend_date_to){
                    $.post("{{route('subscriptions.extends')}}",{ id:subscription_id, amount: extend_amount, date_to: extend_date_to }, function(response){
                        let data = response;
                        let addClass = "btn-success";
                        Swal.fire({
                            title: "Notificacion",
                            text: data.message+", la pagina se recargara en 3 segundos!!",
                            icon: data.type
                        });

                        setTimeout(function(){
                              location.reload();
                            }, 3000);

                        $("#edit_subscription_id, #edit_customer_id, #edit_date_to, #edit_profile, #edit_pin, #edit_active_free_profile, #extend_amount, #extend_date_to, #last_days").val("");
                        $("#modal-edit").modal('hide');
                    });
                }else{
                    Swal.fire({
                        title: "Notificacion",
                        text: "Los campos de monto y facturacion son Obligatorios!!",
                        icon: "error"
                    });
                }
            });
        });
    </script>
@stop