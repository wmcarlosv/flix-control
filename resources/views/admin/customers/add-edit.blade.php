@extends('adminlte::page')

@section('title', $title)

@section('content')
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h2><i class="fas fa-walking"></i> {{ $title }}</h2>
                </div>
                @include('admin.partials.form', ['element'=>'customers', 'type'=>$type, 'id'=>@$data->id])
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Nombre:</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ @$data->name }}" name="name" />
                            @error('name')
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
                            <label for="">Telefono:</label>
                            <div class="alert alert-primary" role="alert">
                                Es importante que el numero comience con el codigo del pais Ejemplo 58 sin el signo de +
                            </div>
                            <input type="text" placeholder="584160000000" class="form-control @error('phone') is-invalid @enderror" value="{{ @$data->phone }}" name="phone" />
                            @error('phone')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        @include('admin.partials.buttons',['cancelRoute'=>'customers.index'])
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop