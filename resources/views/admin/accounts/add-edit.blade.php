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
                            <label for="">Email:</label>
                            <input type="text" class="form-control @error('email') is-invalid @enderror" value="{{ @$data->email }}" name="email" />
                            @error('email')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Password Email:</label>
                            <input type="password" class="form-control @error('passwordemail') is-invalid @enderror" value="{{ @$data->passwordemail }}" name="passwordemail" />
                            @error('passwordemail')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Password:</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" value="{{ @$data->password }}" name="password" />
                            @error('password')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Date to:</label>
                            <input type="date" class="form-control @error('dateto') is-invalid @enderror" value="{{ @$data->dateto }}" name="dateto" />
                            @error('dateto')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Services:</label>
                            <input type="text" class="form-control @error('service_id') is-invalid @enderror" value="{{ @$data->service_id }}" name="service_id" />
                            @error('service_id')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Status:</label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status">
                                <option value="1">true</option>
                                <option value="0" @if(@$data->is_active == 0) selected='selected' @endif>false</option>
                            </select>
                            @error('is_active')
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