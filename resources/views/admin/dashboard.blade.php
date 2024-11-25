@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    @php
        $symbol = "$";
        if(!empty($setting->currency)){
            $currency = json_decode($setting->currency, true);
            $symbol = $currency['symbol'];
        }
    @endphp
    <div class="row">
        <div class="col-md-4">
            <div class="info-box bg-success">
              <span class="info-box-icon"><i class="fas fa-sort-numeric-up"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Ingresos</span>
                <span class="info-box-number">{{$symbol}} {{number_format($input,2,',','.')}}</span>
              </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-danger">
              <span class="info-box-icon"><i class="fas fa-sort-numeric-down-alt"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Gastos</span>
                <span class="info-box-number">{{$symbol}} {{number_format($output,2,',','.')}}</span>
              </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box bg-info">
              <span class="info-box-icon"><i class="far fa-money-bill-alt"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Balance</span>
                <span class="info-box-number">{{$symbol}} {{number_format($balance,2,',','.')}}</span>
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
              @if(Auth::user()->role == 'reseller')
                @if(!$setting->disable_s_and_c)
                  <li class="nav-item">
                    <a class="nav-link" id="expirations_subscriptions-tab" data-toggle="tab" href="#expirations_subscriptions" role="tab" aria-controls="expirations_subscriptions"
                      aria-selected="false">Subscripciones por Vencer</a>
                  </li>
                  @endif
              @else
                 <li class="nav-item">
                    <a class="nav-link" id="expirations_subscriptions-tab" data-toggle="tab" href="#expirations_subscriptions" role="tab" aria-controls="expirations_subscriptions"
                      aria-selected="false">Subscripciones por Vencer</a>
                  </li>
              @endif
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
                                        <td>{{$symbol}} {{number_format($mv->amount,2,',','.')}}</td>
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
                                                <img src="{{asset(str_replace('public','storage',@$es->service->cover))}}" class="img-thumbnail" style="width:75px; height:75px;">@endif {{$es->service->name}}        
                                            </td>
                                            <td>{{$es->account->email}}</td>
                                            <td><a href="{{route('customers.edit',$es->customer->id)}}" target="_blank">{{$es->customer->name}}</a></td>
                                            <td>{{$es->last_days}}</td>
                                            <td>{{date('d/m/Y',strtotime($es->date_to))}}</td>
                                            <td>
                                                <a href="#" class="btn btn-info copy_button" data-id='{{$es->id}}' title="Copiar Informacion de Expiracion"><i class="fas fa-copy"></i></a>
                                                <a href="#" class="btn btn-success send-by-whatsapp" data-id='{{$es->id}}' data-phone="{{$es->customer->phone}}" title="Notificar por Whatsapp"><i class="fab fa-whatsapp"></i></a>
                                                
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
                                                    <img src="{{asset(str_replace('public','storage',$acc->service->cover))}}" class="img-thumbnail" style="width:75px; height:75px;">@endif {{@$acc->service->name}}        
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

            $("body").on('click','a.copy_button',function(){
                let id = $(this).attr("data-id");
                $.get("get-data-message/"+id, function(response){
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

            $("body").on('click','a.send-by-whatsapp',function(){
                let id = $(this).attr("data-id");
                let phone = $(this).attr("data-phone");
                $.get("get-data-message/"+id, function(response){
                    let data = response;
                    if(data.success){
                        let link = encodeURI("https://wa.me/"+phone+"?text="+data.message);
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
                $("#myTabContentEdit").append($temp);
                $temp.val(text).select();
                document.execCommand('copy');
                $temp.remove();
            }
        });
    </script>
@stop