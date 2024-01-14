@extends('adminlte::page')

@section('title', $title)

@section('content')
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h2><i class="fas fa-user"></i> {{ $title }}</h2>
                </div>
                @include('admin.partials.form', ['element'=>'services', 'type'=>$type, 'id'=>@$data->id])
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Name:</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ @$data->name }}" name="name" />
                            @error('name')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Cover:</label>
                            <input type="file" class="form-control @error('cover') is-invalid @enderror" value="{{ @$data->cover }}" name="cover" />
                            @if($type == 'edit')
                            <br />
                            <img src="{{asset(str_replace('public','storage',@$data->cover))}}" class="img-thumbnail" style="width:200px; height:200px;" alt="{{@$data->name}}">
                            @endif
                            @error('cover')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Profile:</label>
                            <input type="number" class="form-control @error('profiles') is-invalid @enderror" value="{{ @$data->profiles }}" name="profiles" />
                            @error('profiles')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Link:</label>
                            <input type="text" class="form-control @error('link') is-invalid @enderror" value="{{ @$data->link }}" name="link" />
                            @error('link')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        @include('admin.partials.buttons',['cancelRoute'=>'services.index'])
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <!-- <script>
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
        }); -->
    </script>
@stop