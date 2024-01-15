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
                                <label for="">Valor  de la Cuenta:</label>
                                <input type="number" step="0.01" name="amount" class="form-control" />
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="">Facturacion:</label>
                            <input type="date" readonly class="form-control @error('dateto') is-invalid @enderror" value="{{ @$data->dateto }}" name="dateto" />
                            @error('dateto')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                            @if($type == 'edit')
                                <br />
                                <a href="#" class="btn btn-success" id="open-modal-extend">Extender Facturacion</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        @include('admin.partials.buttons',['cancelRoute'=>'accounts.index'])
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
    @endif
@stop

@section('js')
    <script>
        $(document).ready(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            @if($type == 'edit')
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
                            Swal.fire({
                                title:"Notificacion",
                                text: data.message,
                                icon: data.type
                            });

                            $("input[name='dateto']").val(data.account.dateto);
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