@extends('adminlte::page')

@section('title', $title)

@section('content')
<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
      <div class="card card-success">
            <div class="card-header">
                <h2><i class="fas fa-list"></i> {{$title}}</h2>
            </div>
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Titulo:</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{@$data->title}}">
                        @error('title') 
                            <span class="error invalid-feedback">{{ $message }}</span> 
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Descripcion Corta:</label>
                        <input type="text" class="form-control @error('about') is-invalid @enderror" name="about" value="{{@$data->about}}">
                        @error('about') 
                            <span class="error invalid-feedback">{{ $message }}</span> 
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Logo:</label>
                        <input type="file" class="form-control @error('logo') is-invalid @enderror" name="logo" />
                        @error('logo') 
                            <span class="error invalid-feedback">{{ $message }}</span> 
                        @enderror
                        @if(!empty(@$data->logo))
                            <br />
                            <img src="{{asset(str_replace('public','storage',@$data->logo))}}" class="img-thumbnail" style="width:100px; height:100px;">
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Cover:</label>
                        <input type="file" class="form-control @error('cover') is-invalid @enderror" name="cover" />
                        @error('cover') 
                            <span class="error invalid-feedback">{{ $message }}</span> 
                        @enderror
                        @if(!empty(@$data->cover))
                            <br />
                            <img src="{{asset(str_replace('public','storage',@$data->cover))}}" class="img-thumbnail" style="width:100px; height:100px;">
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Template para Notificar Expiracion de Cuenta:</label>
                        <textarea class="form-control @error('expiration_template') is-invalid @enderror" name="expiration_template">{{@$data->expiration_template}}</textarea>
                        @error('expiration_template') 
                            <span class="error invalid-feedback">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">Template para enviar datos a Cliente:</label>
                        <textarea class="form-control @error('customer_data_template') is-invalid @enderror" name="customer_data_template">{{@$data->customer_data_template}}</textarea>
                        @error('customer_data_template') 
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
@stop