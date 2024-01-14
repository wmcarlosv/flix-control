@extends('adminlte::page')

@section('title', $title)

@section('content')
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h2><i class="fas fa-user"></i> {{ $title }}</h2>
                </div>
                @include('admin.partials.form', ['element'=>'users', 'type'=>$type, 'id'=>@$data->id])
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Name:</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ @$data->name }}" name="name" />
                            @error('name')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Email:</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{ @$data->email }}" name="email" />
                            @error('email')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Role:</label>
                            <select class="form-control @error('role') is-invalid @enderror" name="role">
                                <option value="super_admin">Super Admin</option>
                                <option value="admin" @if(@$data->role == 'admin') selected='selected' @endif>Admin</option>
                            </select>
                            @error('role')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Active:</label>
                            <select class="form-control @error('is_active') is-invalid @enderror" name="is_active">
                                <option value="1">Yes</option>
                                <option value="0" @if(@$data->is_active == 0) selected='selected' @endif>No</option>
                            </select>
                            @error('is_active')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Date To:</label>
                            <input type="date" class="form-control @error('date_to') is-invalid @enderror" value="{{ @$data->date_to }}" name="date_to" />
                            @error('date_to')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        @if($type=='new')
                            <div class="input-group">
                                <!--<label for="">Password:</label>-->
                                <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="password" value="{{ @$data->password }}" name="password" />
                        
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="button" id="view-password" data-view="n"><i class="fas fa-lock"></i></button>
                                </div>
                                @error('password')
                                   <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                    </div>
                    <div class="card-footer">
                        @include('admin.partials.buttons',['cancelRoute'=>'users.index'])
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
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
        });
    </script>
@stop