@extends('adminlte::page')

@section('title', 'Mis Cuentas')

@section('content_header')
    <h1><i class="fas fa-hand-sparkles"></i> Mis Cuentas</h1>
    <br />
    <div class="row">
        @if($accounts->count() > 0)
            @foreach($accounts as $account)
                <div class="col-md-3">
                    <div class="card">
                      <img class="card-img-top" style="width: 377px; height: 212px;" src="{{ asset(str_replace('public','storage',$account->service->cover)) }}" alt="{{$account->service->title}}">
                      <div class="card-body">
                        <h4 class="card-title">{{$account->service->title}}</h4>
                        <p class="card-text">
                            <ul class="list-group">
                                <li class="list-group-item"><b>Email:</b> {{$account->email}}</li>
                                <li class="list-group-item"><b>Clave Cuenta:</b> {{$account->password}}</li>
                                <li class="list-group-item"><b>Facturacion:</b> {{date('d-m-Y',strtotime($account->reseller_due_date))}}</li>
                                <li class="list-group-item"><b>Dias Restantes:</b> {{$account->last_days}}</li>
                                <li class="list-group-item"><b>Perfiles:</b> {{$account->service->profiles}}</li>
                            </ul>
                        </p>
                      </div>
                      <div class="card-footer">
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
                            <!--<button class="btn btn-info renove-reseller-account" data-account-id="{{$account->id}}" type="button">Renovar Subscripcion</button>-->
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
@stop