@extends('adminlte::page')

@section('title', $title)

@section('content')
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h2><i class="fas fa-dollar-sign"></i> {{ $title }}</h2>
                </div>
                @include('admin.partials.form', ['element'=>'credits', 'type'=>$type, 'id'=>@$data->id])
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Usuario:</label>
                            <select name="user_id" style="width: 100%; padding: 20px;" class="form-control @error('user_id') is-invalid @enderror">
                                <option value="">Seleccione</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}} - {{$user->email}}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Monto:</label>
                            <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror">
                            @error('amount')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Comentario:</label>
                            <textarea name="comment" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        @include('admin.partials.buttons',['cancelRoute'=>'credits.index'])
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
        });
    </script>
@stop