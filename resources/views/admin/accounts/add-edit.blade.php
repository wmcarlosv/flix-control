@extends('adminlte::page')

@section('title', $title)

@section('content')
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h2><i class="fas fa-piggy-bank"></i> {{ $title }}</h2>
                </div>
                @include('admin.partials.form', ['element'=>'accounts', 'type'=>$type, 'id'=>@$data->id])
                    <div class="card-body">
                         @if($type == 'edit')
                            <div class="alert alert-warning text-center">Es Importante que le configures los perfiles a las cuentas, para que funcionen de manera Correcta!!</div>
                        @endif
                        <div class="form-group">
                            <label for="">Servicio:</label>
                            <select class="form-control @error('service_id') is-invalid @enderror" value="{{ @$data->service_id }}" name="service_id">
                                <option value="">-</option>
                                @foreach($services as $service)
                                    <option value="{{$service->id}}" @if($service->id == @$data->service_id) selected='selected'  @endif>{{$service->name}}</option>
                                @endforeach
                            </select>
                            @error('service_id')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Email:</label>
                            <input type="text" class="form-control @error('email') is-invalid @enderror" value="{{ @$data->email }}" name="email" />
                            @error('email')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Contraseña del Email:</label>
                            <input type="text" class="form-control @error('passwordemail') is-invalid @enderror" value="{{ @$data->passwordemail }}" name="passwordemail" />
                            @error('passwordemail')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Contraseña de la Cuenta:</label>
                            <input type="text" class="form-control @error('password') is-invalid @enderror" value="{{ @$data->password }}" name="password" />
                            @error('password')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        @if($type=='new')
                            <div class="form-group">
                                <label for="">Precio de Compra:</label>
                                <input type="number" step="0.01" name="amount" class="form-control" />
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="">Precio para Venta:</label>
                            <input type="number" step="0.01" name="sale_price" value="{{@$data->sale_price}}" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="">Precio Venta por Perfil:</label>
                            <input type="number" step="0.01" name="profile_price" value="{{@$data->profile_price}}" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="">Facturacion:</label>
                            <input type="date" @if($type=='edit') readonly @endif class="form-control @error('dateto') is-invalid @enderror" value="{{ @$data->dateto }}" name="dateto" />
                            @error('dateto')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        @if($type == 'edit')
                            <div class="form-group">
                                <label for="">Dias Restanates:</label>
                                <input type="text" class="form-control" id="last_days" readonly value="{{ @$data->last_days }}" />
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="">Vendedor:</label>
                            <select class="form-control @error('user_id') is-invalid @enderror select-2" value="{{ @$data->user_id }}" name="user_id">
                                <option value="">-</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}" @if($user->id == @$data->user_id) selected='selected'  @endif>{{$user->name}}({{$user->role}})</option>
                                @endforeach
                            </select>
                            @error('user_id')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="">Vencimiento Reseller:</label>
                            <input type="date" class="form-control @error('reseller_due_date') is-invalid @enderror" value="{{ @$data->reseller_due_date }}" name="reseller_due_date" />
                            @error('reseller_due_date')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="">Visible en Tienda:</label>
                            <select name="is_store" class="form-control">
                                <option value="0">No</option>
                                <option value="1" @if(@$data->is_store == 1) selected='selected' @endif>Si</option>
                            </select>
                        </div>

                        @php
                            $display = "none";
                            if($type == "edit"){
                                if(!empty($data->is_store)){
                                    if($data->is_store == 1){
                                        $display = "block";
                                    }
                                }
                            }
                        @endphp
                        <div class="form-group" id="div_sale_type" style="display:{{$display}};">
                            <label for="">Como se Vendera?</label>
                            <select name="sale_type" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="complete" @if(@$data->sale_type == 'complete') selected='selected' @endif>Completa</option>
                                <option value="profile" @if(@$data->sale_type == 'profile') selected='selected' @endif>Por Pefiles</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        @include('admin.partials.buttons',['cancelRoute'=>'accounts.index'])
                        @if($type == 'edit')
                            <a href="#" class="btn btn-info" id="open-modal-profiles"><i class="fas fa-users"></i> Perfiles</a>
                            <a href="#" class="btn btn-warning" id="open-modal-extend"><i class="fas fa-external-link-alt"></i> Extender Facturacion</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($type == 'edit')
        <!-- Modal -->
        <div class="modal fade" id="modal-extend" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Extender la Facturacion</h5>
                <button type="button" class="close close-modal-extend" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                    <label for="">Valor de la Facturacion:</label>
                    <input type="number" id="extend_amount" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="">Nueva Fecha Facturacion:</label>
                    <input type="date" id="extend_date_to" value="{{@$data->dateto}}" class="form-control" />
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success" id="save-moda-extend"><i class="fas fa-save"></i> Extender</button>
                <button type="button" class="btn btn-danger close-modal-extend"><i class="fas fa-times"></i> Cancelar</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modal-profiles" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Perfiles de la Cuenta</h5>
                <button type="button" class="close close-modal-extend" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form action="{{ route('add_profiles') }}" autocomplete="off" method="POST">
                @method('POST')
                @csrf
                  <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <th>Perfil</th>
                            <th>Pin</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody>
                            @php 
                                $usados = $data->profiles->count();
                                $totales = $data->service->profiles;
                                $restantes = ($totales - $usados);
                            @endphp

                            @foreach($data->profiles as $p)
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" value="{{$p->name}}" id="edit_name_{{$p->id}}">
                                    </td>
                                    <td><input type="text" id="edit_pin_{{$p->id}}" value="{{$p->pin}}" class="form-control"></td>
                                    <td>
                                        <a href="#" data-id='{{$p->id}}' class="btn btn-success edit-profile"><i class="fas fa-save"></i></a>
                                        @if($p->subscriptions->count() > 0)
                                             <a target="_blank" href="{{route('customers.edit',$p->subscriptions[0]->customer)}}" title="Ver Cliente {{$p->subscriptions[0]->customer->name}}" class="btn btn-warning"><i class="fas fa-user"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            @for($i=0; $i < $restantes; $i++)
                                <tr>
                                    <td>
                                        <input type="hidden" name="account_id" value="{{$data->id}}">
                                        <input type="hidden" name="positions[]" value="{{$i}}"/>
                                        <input type="text" class="form-control" name="profiles[]">
                                    </td>
                                    <td><input type="text" name="pins[]" class="form-control"></td>
                                    <td>-</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                  </div>
                  <div class="modal-footer">
                    @if($restantes > 0)
                        <button type="submit" class="btn btn-success" id="save-modal-profiles"><i class="fas fa-save"></i> Guardar</button>
                    @endif
                    <button type="button" class="btn btn-danger" id="close-modal-profiles"><i class="fas fa-times"></i> Cerrar</button>
                  </div>
              </form>
            </div>
          </div>
        </div>
    @endif
@stop

@section('js')
@include('admin.partials.messages')
    <script>

        $(document).ready(function(){

            $(".select-2").select2();

            $("select[name='is_store']").change(function(){
                let value = $(this).val();
               
                if(value == "1"){
                    $("#div_sale_type").show();
                }else{
                     $("#div_sale_type").hide();
                }
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            @if($type == 'edit')
                $("body").on('click','a.edit-profile', function(){
                    let id = $(this).attr("data-id");
                    let name = $("#edit_name_"+id).val();
                    let pin = $("#edit_pin_"+id).val();

                    if(name){
                        $.ajax({
                            type:'PUT',
                            url:'{{route("edit_profile")}}',
                            dataType:'json',
                            data:{
                                id:id,
                                name:name,
                                pin:pin
                            },
                            success: function(response){
                                if(response.success){
                                    Swal.fire({
                                        title:'Notificacion',
                                        text: 'Perfil actualizado con Exito!!',
                                        icon:'success'
                                    });
                                }
                            }
                        })
                    }else{
                        Swal.fire({
                            title: "Notificacion",
                            text:"El campo perfil es Obligatorio!",
                            icon:'error'
                        });
                    }
                });

                $("#open-modal-profiles").click(function(){
                    $("#modal-profiles").modal({backdrop:'static',keyboard:false},'show');
                });

                $("#close-modal-profiles").click(function(){
                    $("#modal-profiles").modal('hide');
                });

                $("#open-modal-extend").click(function(){
                    $("#modal-extend").modal('show');
                });

                $(".close-modal-extend").click(function(){
                    $("#modal-extend").modal('hide');
                });

                $("#save-moda-extend").click(function(){
                    let extend_amount = $("#extend_amount").val();
                    let extend_date_to = $("#extend_date_to").val();

                    if(extend_amount && extend_date_to){
                        $.post("{{route('extend_account')}}", { id: '{{@$data->id}}', amount: extend_amount, date_to: extend_date_to}, function(response){
                            let data = response;
                            console.log(data);
                            Swal.fire({
                                title:"Notificacion",
                                text: data.message,
                                icon: data.type
                            });

                            $("input[name='dateto']").val(data.account.dateto);
                            $("#last_days").val(data.account.last_days);
                            $("#extend_amount, #extend_date_to").val("");
                            $("#modal-extend").modal('hide');
                        });
                    }else{
                        Swal.fire({
                            title: "Notificacion",
                            text: "Los campos valor de facturacion y nueva fecha facturacion son Obligatorios!!",
                            icon: "error"
                        });
                    }
                });
            @endif
        });
    </script>
@stop