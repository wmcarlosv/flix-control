@extends('adminlte::page')

@section('title', 'Tienda')

@section('css')
<style>
    .tab-pane{
        padding: 10px;
    }
</style>
@stop

@section('content_header')
    <h1><i class="fas fa-store"></i> Tienda</h1>
    <br />
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Cuentas Completas</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Cuentas por Perfil</button>
      </li>
    </ul>
    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        @if($accounts->count() > 0)
            <div class="row">
                @foreach($accounts as $account)
                    @if($account->sale_type == 'complete')
                        <div class="col-md-3">
                            <div class="card">
                              <img class="card-img-top" style="width:100%; height: 300px;" src="{{ asset(str_replace('public','storage',$account->service->cover)) }}" alt="{{$account->service->title}}">
                              <div class="card-body">
                                <h4 class="card-title">{{$account->service->title}}</h4>
                                <p class="card-text">
                                    <ul class="list-group">
                                        <!--<li class="list-group-item"><b>Email:</b> {{$account->email}}</li>-->
                                        <li class="list-group-item"><b>Perfiles:</b> {{$account->service->profiles}}</li>
                                        <li class="list-group-item"><b>Precio:</b> {{\App\Helpers\Helper::currentSymbol()}} {{number_format($account->sale_price, 2, ',','.')}}</li>
                                    </ul>
                                </p>
                                <form action="{{route('buy_account')}}" class="buy-account" style="display:inline;" method="POST">
                                    @method('POSt')
                                    @csrf
                                    <input type="hidden" name="sale_type" value="complete" />
                                    <input type="hidden" name="account_id" value="{{$account->id}}">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-money-bill-alt"></i> Comprar</button>
                                </form>
                              </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="col-md-12">
                <div class="alert alert-info" role="alert">
                  No hay cuentas disponibles en este Momento!!
                </div>
            </div>
        @endif
      </div>
      <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        @if($accounts->count() > 0)
            <div class="row">
                @foreach($accounts as $account)
                    @if($account->sale_type == 'profile')
                        @if($account->profilesbuyed->count() > 0)
                        <div class="col-md-3">
                            <div class="card">
                              <img class="card-img-top" style="width:100%; height: 300px;" src="{{ asset(str_replace('public','storage',$account->service->cover)) }}" alt="{{$account->service->title}}">
                              <div class="card-body">
                                <h4 class="card-title">{{$account->service->title}}</h4>
                                <form action="{{route('buy_account')}}" class="buy-account" style="display:inline;" method="POST">
                                    @method('POSt')
                                    @csrf
                                    <p class="card-text">
                                        <table class="table table-bordered">
                                            <thead>
                                                <th>#</th>
                                                <th>Nombre</th>
                                                <th>Precio</th>
                                            </thead>
                                            <tbody>
                                            @if($account->profilesbuyed->count() > 0)
                                                @foreach($account->profilesbuyed as $profile)
                                                    <tr>
                                                        <td><input type="checkbox" name="profile_selected[]" data-account-id="{{$account->id}}" data-price="{{$account->profile_price}}" value="{{$profile->id}}"></td>
                                                        <td>{{$profile->name}}</td>
                                                        <td>{{$account->profile_price}}{{\App\Helpers\Helper::currentSymbol()}}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                            <tr>
                                                <td colspan="3" align="center">Sin Perfiles</td>
                                            </tr>
                                            @endif
                                            </tbody>
                                            <tr>
                                                <td colspan="2" align="right"><b>Total</b></td>
                                                <td id="td_total_{{$account->id}}">0{{\App\Helpers\Helper::currentSymbol()}}</td>
                                            </tr>
                                        </table>
                                    </p>
                                    <input type="hidden" name="sale_type" value="profile" />
                                    <input type="hidden" name="account_id" value="{{$account->id}}">
                                    <input type="hidden" name="total" />
                                    <button type="submit" class="btn btn-success"><i class="fas fa-money-bill-alt"></i> Comprar</button>
                                </form>
                              </div>
                            </div>
                        </div>
                        @endif
                    @endif
                @endforeach
            </div>
        @else
            <div class="col-md-12">
                <div class="alert alert-info" role="alert">
                  No hay cuentas disponibles en este Momento!!
                </div>
            </div>
        @endif
      </div>
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

            $("body").on('click',"input[name='profile_selected[]']", function(){
                updatePrice();
            });

            const updatePrice = () => {
                let selecteds = $("input[name='profile_selected[]']");
                let account_id;
                let total = 0;
                $.each(selecteds, function(v,e){
                    if($(this).prop("checked")){
                        account_id = $(this).data("account-id");
                        total+=parseFloat($(this).data("price"));
                    }
                });

                $("#td_total_"+account_id).html(total+"{{\App\Helpers\Helper::currentSymbol()}}");
                $("input[name='total']").val(total);
            }
        });
    </script>
@stop