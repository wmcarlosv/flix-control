@extends('adminlte::page')

@section('title', $title)

@section('content')
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h2><i class="fas fa-star"></i> {{ $title }}</h2>
                </div>
                @include('admin.partials.form', ['element'=>'subscriptions', 'type'=>$type, 'id'=>@$data->id])
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Servicio:</label>
                            <select name="service_id" class="form-control @error('service_id') is-invalid @enderror">
                                <option value="-1">Seleccione</option>
                                @foreach($services as $service)
                                    <option value="{{$service->id}}">{{$service->name}}</option>
                                @endforeach
                            </select>
                            @error('service_id')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Cuenta:</label>
                            <select name="account_id" class="form-control @error('account_id') is-invalid @enderror">
                                <option value="-1">Seleccione</option>
                            </select>
                            @error('account_id')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                         <div class="form-group">
                            <label for="">Perfil:</label>
                            <select name="profile_id" class="form-control @error('profile_id') is-invalid @enderror">
                                <option value="-1">Seleccione</option>
                            </select>
                            @error('profile_id')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Cliente:</label>
                            <select name="customer_id" class="form-control @error('customer_id') is-invalid @enderror">
                                <option>Seleccione</option>
                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}">{{$customer->name}} ({{$customer->phone}})</option>
                                @endforeach
                            </select>
                            @error('customer_id')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Monto:</label>
                            <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" />
                            @error('amount')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Vencimiento:</label>
                            <input type="date" name="date_to" class="form-control @error('date_to') is-invalid @enderror" />
                            @error('date_to')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        @include('admin.partials.buttons',['cancelRoute'=>'subscriptions.index'])
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function(){
            $("select").select2();
            $("select[name='service_id']").change(function(){
                let id = $(this).val();
                if(id != '-1'){
                    $.get('/admin/get-accounts/'+id, function(response){
                        let data = response.data;
                        if(data.length > 0){
                            $("select[name='account_id']").html("<option value='-1'>Seleccione</option>");
                            $.each(data, function(v,e){
                                $("select[name='account_id']").append("<option value='"+e.id+"'>"+e.email+"</option>");
                            });
                        }
                    })
                }else{
                    $("select[name='account_id']").html("<option>Seleccione</option>");
                }
            });

            $("select[name='account_id']").change(function(){
                let id = $(this).val();
                if(id != '-1'){
                    $.get('/admin/get-profiles/'+id, function(response){
                        let data = response.data;
                        if(data.length > 0){
                            $("select[name='profile_id']").html("<option value='-1'>Seleccione</option>");
                            $.each(data, function(v,e){
                                $("select[name='profile_id']").append("<option value='"+e.id+"'>"+e.name+" ("+(e.pin ? e.pin : 'Sin Pin')+")</option>");
                            });
                        }
                    })
                }else{
                    $("select[name='profile_id']").html("<option value='-1'>Seleccione</option>");
                }
            });
        });
    </script>
@stop