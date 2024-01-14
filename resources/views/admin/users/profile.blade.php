@extends('adminlte::page')

@section('title', $title)

@section('content')
<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
      <div class="card card-success">
            <div class="card-header">
                <h2><i class="fas fa-user"></i> {{$title}}</h2>
            </div>
            <form action="{{ route('update_profile') }}" method="POST">
                @method('PUT')
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Name:</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" required name="name" value="{{Auth::user()->name}}">
                        @error('name') 
                            <span class="error invalid-feedback">{{ $message }}</span> 
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Email:</label>
                        <input type="email" class="form-control @error('name') is-invalid @enderror" required name="email" value="{{Auth::user()->email}}">
                        @error('email') 
                            <span class="error invalid-feedback">{{ $message }}</span> 
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    @include('admin.partials.buttons',['cancelRoute'=>'dashboard'])
                </div>
            </form>
        </div> 

        <div class="card card-success">
            <div class="card-header">
                <h2><i class="fas fa-key"></i> Actualizar Contraseña</h2>
            </div>
            <form action="{{ route('update_password') }}" method="POST">
                @method('PUT')
                @csrf
                <div class="card-body">
                    <div class="input-group">
                        <input type="password" name="password" placeholder="Password" required class="form-control @error('password') is-invalid @enderror" />
                        <div class="input-group-append">
                            <button class="btn btn-success" type="button" id="view-password" data-view="n"><i class="fas fa-lock"></i></button>
                        </div>
                        @error('password')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <br />
                    <div class="input-group">
                        <input type="password" name="password_confirmation" placeholder="Repeat Password" required class="form-control @error('password_confirmation') is-invalid @enderror" />

                        <div class="input-group-append">
                            <button class="btn btn-success" type="button" id="view-password-repeat" data-view="n"><i class="fas fa-lock"></i></button>
                        </div>
                        @error('password_confirmation')
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
     <script>
        $(document).ready(function(){
            $("#view-password").click(function(){
                let view = $(this).attr("data-view");
                if(view == 'n'){
                    $("input[name='password']").attr("type","text");
                    $(this).attr("data-view","y");
                    $(this).html('<i class="fas fa-lock-open"></i>');
                }else{
                    $("input[name='password']").attr("type","password");
                    $(this).attr("data-view","n");
                    $(this).html('<i class="fas fa-lock"></i>');
                }
            });

            $("#view-password-repeat").click(function(){
                let view = $(this).attr("data-view");
                if(view == 'n'){
                    $("input[name='password_confirmation']").attr("type","text");
                    $(this).attr("data-view","y");
                    $(this).html('<i class="fas fa-lock-open"></i>');
                }else{
                    $("input[name='password_confirmation']").attr("type","password");
                    $(this).attr("data-view","n");
                    $(this).html('<i class="fas fa-lock"></i>');
                }
            });
        });
    </script>
@stop