@extends('adminlte::page')

@section('title', 'Tienda')

@section('content_header')
    <h1><i class="fas fa-store"></i> Tienda</h1>
    <br />
    <div class="row">
        @if($accounts->count() > 0)
            @foreach($accounts as $account)
                <div class="col-md-3">
                    <div class="card">
                      <img class="card-img-top" src="{{ asset(str_replace('public','storage',$account->service->cover)) }}" alt="{{$account->service->title}}">
                      <div class="card-body">
                        <h4 class="card-title">{{$account->service->title}}</h4>
                        <p class="card-text">
                            <ul class="list-group">
                                <li class="list-group-item"><b>Email:</b> {{$account->email}}</li>
                                <li class="list-group-item"><b>Perfiles:</b> {{$account->service->profiles}}</li>
                                <li class="list-group-item"><b>Precio:</b> {{\App\Helpers\Helper::currentSymbol()}} {{number_format($account->sale_price, 2, ',','.')}}</li>
                            </ul>
                        </p>
                        <form action="{{route('buy_account')}}" class="buy-account" style="display:inline;" method="POST">
                            @method('POSt')
                            @csrf
                            <input type="hidden" name="account_id" value="{{$account->id}}">
                            <button type="submit" class="btn btn-success"><i class="fas fa-money-bill-alt"></i> Comprar</button>
                        </form>
                      </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-md-12">
                <div class="alert alert-info" role="alert">
                  No hay cuentas disponibles en este Momento!!
                </div>
            </div>
        @endif
    </div>  
@stop

@section('js')
    @include('admin.partials.messages')
    <script>
        $(document).ready(function(){
            $("form.buy-account").submit(function(){
                if(confirm("Estas Seguro de Comprar este Cuenta?")){
                    return true;
                }

                return false;
            });
        });
    </script>
@stop