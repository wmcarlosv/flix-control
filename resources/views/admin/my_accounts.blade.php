@extends('adminlte::page')

@section('title', 'Mis Cuentas')

@section('css')
    <style>
        span.account_type{
            position: absolute;
            top: 0px;
            z-index: 1000;
            background: green;
            padding: 10px 20px;
            color: white;
            font-weight: bold;
        }
    </style>
@stop

@section('content_header')
    <h1><i class="fas fa-hand-sparkles"></i> Mis Cuentas</h1>
    <br />
    <div class="row">
        @if($accounts->count() > 0)
            @foreach($accounts as $account)
                <div class="col-md-4" style="position: relative;">
                    <span class="account_type">{{($account->sale_type == 'complete' ? "Completa" : "Por Perfil")}}</span>
                    <div class="card">
                      <img class="card-img-top" style="width: 100%; height: 212px;" src="{{ asset(str_replace('public','storage',$account->service->cover)) }}" alt="{{$account->service->title}}">
                      <div class="card-body">
                        <h4 class="card-title">{{$account->service->title}}</h4>
                        <p class="card-text">
                            <ul class="list-group">
                                <li class="list-group-item"><b>Email:</b> {{$account->email}}</li>
                                <li class="list-group-item"><b>Clave Cuenta:</b> {{$account->password}}</li>
                                @if($account->sale_type == "complete")
                                    <li class="list-group-item"><b>Facturacion:</b> {{date('d-m-Y',strtotime($account->reseller_due_date))}}</li>
                                    <li class="list-group-item"><b>Dias Restantes:</b> {{$account->last_days}}</li>
                                    <li class="list-group-item"><b>Perfiles:</b> {{$account->service->profiles}}</li>
                                    </ul>
                                @else
                                    </ul>
                                    <hr />
                                    <h4>Perfiles</h4>
                                    <table class="table table-bordered">
                                        <thead>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Pin</th>
                                            <th>Dias</th>
                                            <th>Vencimiento</th>
                                        </thead>
                                        <tbody>
                                            @foreach($account->profilessaled as $profile)
                                                <tr>
                                                    <td><input type="checkbox" data-price="{{$account->profile_price}}" name="renove_profile_selected[]" value="{{$profile->id}}"></td>
                                                    <td>{{$profile->name}}</td>
                                                    <td>{{$profile->pin}}</td>
                                                    <td>{{$profile->last_days}}</td>
                                                    <td>{{date('d-m-Y',strtotime($profile->due_date))}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            
                        </p>
                      </div>
                      <div class="card-footer">
                        @if($account->sale_type == "complete")
                        <button class="btn btn-success" data-toggle="modal" data-target="#view_profiles_{{$account->id}}"><i class="fas fa-star"></i>Perfiles ({{$account->profiles->count()}})</button>
                          <div class="modal fade" id="view_profiles_{{$account->id}}" data-backdrop="static" data-keyboard="false">
                              <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Perfiles de la Cuenta</h5>
                                    <button type="button" class="close close-modal-extend" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                      <div class="modal-body">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <th>Perfil</th>
                                                <th>Pin</th>
                                                <th>Acciones</th>
                                            </thead>
                                            <tbody>
                                                @php 
                                                    $usados = $account->profiles->count();
                                                    $totales = $account->service->profiles;
                                                    $restantes = ($totales - $usados);
                                                @endphp

                                                @foreach($account->profiles as $p)
                                                    <tr>
                                                        <td>
                                                            <input type="text" readonly class="form-control" value="{{$p->name}}" id="edit_name_{{$p->id}}">
                                                        </td>
                                                        <td><input type="text" readonly id="edit_pin_{{$p->id}}" pattern="\d*" maxlength="4" value="{{$p->pin}}" class="form-control"></td>
                                                        <td>
                                                            <!--<a href="#" data-id='{{$p->id}}' class="btn btn-success edit-profile"><i class="fas fa-save"></i></a>-->
                                                            @if($p->subscriptions->count() > 0)
                                                                 <a target="_blank" href="{{route('customers.edit',$p->subscriptions[0]->customer)}}" title="Ver Cliente {{$p->subscriptions[0]->customer->name}}" class="btn btn-warning"><i class="fas fa-user"></i></a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                @for($i=0; $i < $restantes; $i++)
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="account_id" value="{{$account->id}}">
                                                            <input type="hidden" name="positions[]" value="{{$i}}"/>
                                                            <input type="text" readonly class="form-control" name="profiles[]">
                                                        </td>
                                                        <td><input type="text" readonly name="pins[]" pattern="\d*" maxlength="4" class="form-control"></td>
                                                        <td>-</td>
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                      </div>
                                </div>
                              </div>
                            </div>
                            <button class="btn btn-info renove-reseller-account" data-sales-price="{{$account->sale_price}}" data-account-id="{{$account->id}}" type="button">Renovar Subscripcion</button>
                            @else
                                <button class="btn btn-info renove-reseller-account-profile" data-account-id="{{$account->id}}" type="button">Renovar Subscripcion</button>
                            @endif
                      </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-md-12">
                <div class="alert alert-info" role="alert">
                  No Tienes Cuentas Disponibles!!
                </div>
            </div>
        @endif
    </div>  

<div class="modal fade" id="extend_account_subscription" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Extender Subscripcion</h5>
            <button type="button" class="close close_extend_account_subscription" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{route('extend_reseller_subscription')}}" method="POST">
            @method('POST')
            @csrf
              <div class="modal-body">
                <input type="hidden" name="account_id" id="extend_account_id" />
                <input type="hidden" name="total" />
                <input type="hidden" name="sale_type" />
                <div class="content_renove_profile_selected"></div>
                  <div class="form-group">
                      <label for="">Meses a Extender</label>
                      <input type="number" class="form-control" min="1" value="1" max="12" name="months" />
                  </div>
                  <div class="row">
                      <table class="table">
                          <tr>
                              <td align="right"><b>Total:</b></td>
                              <td id="td_total">0</td>
                          </tr>
                      </table>
                  </div>
              </div>
              <div class="modal-footer">
                  <button class="btn btn-success submit-form" type="button"><i class="fas fa-save"></i> Guardar</button>
                  <a href="#" class="btn btn-danger close_extend_account_subscription"><i class="fas fa-times"></i> Cancelar</a>
              </div>
          </form>
        </div>
    </div>
</div>


@stop

@section('js')
    @include('admin.partials.messages')
    <script>
        var activePrice=0;
        var symbol = "$";
        $(document).ready(function(){

            $("input[name='renove_profile_selected[]']").click(function(){
                if($(this).prop("checked")){
                    $("div.content_renove_profile_selected").append('<input type="hidden" id="renove_profile_selected_'+$(this).val()+'" name="renove_profile_selected[]" value="'+$(this).val()+'" />');
                }else{
                    $("renove_profile_selected_"+$(this).val()).remove();
                }
            });

            $("body").on('click','button.renove-reseller-account-profile', function(){
                let totalSelected = 0;

                $("input[name='renove_profile_selected[]']").each(function(v,e){
                    if($(this).prop("checked")){
                        totalSelected++;
                    }
                });

                if(totalSelected > 0){
                   $("input[name='sale_type']").val("profile");
                    let account_id = $(this).data('account-id');
                    $("#extend_account_id").val(account_id);
                    activePrice = profilePrice();
                    $("input[name='total']").val(parseFloat(activePrice));
                    $("#td_total").html(activePrice+symbol);
                    $("#extend_account_subscription").modal('show'); 
                }else{
                    alert("Debes seleccionar al menos un perfil para renovar");
                }
                
            });

            $(".submit-form").click(function(){
                if(confirm("Estas seguro de realizar esta compra?")){
                    $("form").submit();
                }
            });
            
            @if(!empty($setting))
                @php
                    $setting = json_decode($setting->currency, true);
                @endphp
                symbol = "{{(!empty($setting['symbol']) ? $setting['symbol'] : '$')}}";
            @endif

            $("button.renove-reseller-account").click(function(){
                let account_id = $(this).data('account-id');
                $("#extend_account_id").val(account_id);
                activePrice = $(this).data('sales-price');
                $("input[name='total']").val(parseFloat(activePrice));
                $("input[name='sale_type']").val("complete");
                $("#td_total").html(activePrice+symbol);
                $("#extend_account_subscription").modal('show');
            });

            $(".close_extend_account_subscription").click(function(){
                $("input[name='months']").val(1);
                $("div.content_renove_profile_selected").empty();

                $("input[name='renove_profile_selected[]']").each(function(v,e){
                    $(this).prop("checked", false);
                });
                $("#extend_account_subscription").modal('hide');
            });

            $("input[name='months']").change(function(){
                let qty = parseInt($(this).val());
                if(qty <= 0 || qty > 12){
                    changePrice(1);
                    $(this).val(1);
                    alert("La cantidad debe ser de 1 a 12 meses");
                    return;
                }

                changePrice(qty);
            });

            const changePrice = (qty) => {
                let total = qty*activePrice;
                $("input[name='total']").val(parseFloat(total));
                $("#td_total").html(parseFloat(total)+symbol);
            };

            const profilePrice = () => {
                let total = 0;
                $("input[name='renove_profile_selected[]']").each(function(v,e){
                    if($(this).prop("checked")){
                        total+=parseFloat($(this).data("price"));
                    }
                });
                return total;
            };

        });
    </script>
@stop