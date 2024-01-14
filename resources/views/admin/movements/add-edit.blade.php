@extends('adminlte::page')

@section('title', $title)

@section('content')
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h2><i class="fas fa-calculator"></i> {{ $title }}</h2>
                </div>
                @include('admin.partials.form', ['element'=>'movements', 'type'=>$type, 'id'=>@$data->id])
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Tipo:</label>
                            <select class="form-control @error('role') is-invalid @enderror" name="type">
                                <option value="input">Entrada</option>
                                <option value="output">Salida</option>
                            </select>
                            @error('type')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Descripción:</label>
                            <input type="text" class="form-control @error('description') is-invalid @enderror" value="{{ @$data->description }}" name="description" />
                            @error('description')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Fecha del Movimiento:</label>
                            <input type="date" class="form-control @error('datemovement') is-invalid @enderror" value="{{ @$data->datemovement }}" name="datemovement" />
                            @error('datemovement')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Monto:</label>
                            <input type="numeric" class="form-control @error('amount') is-invalid @enderror" value="{{ @$data->amount }}" name="amount" />
                            @error('amount')
                               <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        @include('admin.partials.buttons',['cancelRoute'=>'movements.index'])
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop