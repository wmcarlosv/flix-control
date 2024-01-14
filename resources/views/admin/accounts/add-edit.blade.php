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
                            <input type="password" class="form-control @error('passwordemail') is-invalid @enderror" value="{{ @$data->passwordemail }}" name="passwordemail" />
                            @error('passwordemail')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Contraseña de la Cuenta:</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" value="{{ @$data->password }}" name="password" />
                            @error('password')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Expiracion:</label>
                            <input type="date" class="form-control @error('dateto') is-invalid @enderror" value="{{ @$data->dateto }}" name="dateto" />
                            @error('dateto')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        @include('admin.partials.buttons',['cancelRoute'=>'accounts.index'])
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop