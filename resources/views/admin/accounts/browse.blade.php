@extends('adminlte::page')

@section('title', $title)

@section('content')
    <div class="row" style="margin-top:20px;">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h2><i class="fas fa-piggy-bank"></i> {{ $title }}</h2>
                </div>
                <div class="card-body">
                    @include('admin.partials.table',['columns'=>$columns, 'data'=>$data, 'route'=>'accounts'])
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-new" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Nueva Subscripcion</h5>
            <button type="button" class="close close-new-subscription" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="active_free_profile">
            <input type="hidden" id="service_id" />
            <input type="hidden" id="account_id" />
            <div class="form-group">
                <label for="">Cliente:</label>
                <select name="customer_id" id="customer_id" class="form-control">
                    <option value="">-</option>
                    @foreach($customers as $customer)
                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Perfil:</label>
                <input type="text" class="form-control" id="profile" />
            </div>
            <div class="form-group">
                <label for="">Pin:</label>
                <input type="text" class="form-control" id="pin" />
            </div>
            <div class="form-group">
                <label for="">Monto:</label>
                <input type="number" class="form-control" id="amount" />
            </div>
            <div class="form-group">
                <label for="">Facturacion:</label>
                <input type="date" class="form-control" id="date_to" />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" id="save-new-subscription"><i class="fas fa-save"></i> Guardar</button>
            <button type="button" class="btn btn-danger close-new-subscription"><i class="fas fa-times"></i> Cancelar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    @include('admin.partials.modal-edit-customer')
@stop

@section('js')
    <script>
        var eds = parseInt(-1);
        @if($setting)
            eds = parseInt('{{$setting->expiration_days_subscriptions}}');
        @endif
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $("#copy_button").click(function(){
                let id = $(this).attr("data-id");
                $.get("get-data-message/"+id, function(response){
                    let data = response;
                    if(data.success){
                        copyToClipboard(data.message);
                        Swal.fire({
                            title: "Notificacion",
                            text: "Se han copiado los datos correctamente!!",
                            icon: "success",
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }else{
                        Swal.fire({
                            title: "Notificacion",
                            text: data.messasge,
                            icon: "error"
                        });
                    }
                });
            });

            $("#send-by-whatsapp").click(function(){
                let id = $(this).attr("data-id");
                let phone = $(this).attr("data-number");
                $.get("get-data-message/"+id, function(response){
                    let data = response;
                    if(data.success){
                        let link = "https://wa.me/"+phone+"?text="+data.message;
                        window.open(link, "_blank");
                    }else{
                        Swal.fire({
                            title: "Notificacion",
                            text: data.messasge,
                            icon: "error"
                        });
                    }
                });
            });

            function copyToClipboard(text) {
                var $temp = $("<textarea></textarea>");
                $("#myTabContentEdit").append($temp);
                $temp.val(text).select();
                document.execCommand('copy');
                $temp.remove();
            }

            $("body").on('click','a.modal-new', function(){
                let ids = $(this).attr("data-ids").split(",");
                $("#service_id").val(ids[0]);
                $("#account_id").val(ids[1]);
                $("#active_free_profile").val($(this).attr("id"));
                $("#modal-new").modal('show');
            });

            $(".close-new-subscription").click(function(){
                $("#service_id, #account_id, #customer_id, #amount, #date_to, #profile, #pin, #active_free_profile").val("");
                $("#modal-new").modal('hide');
            });

            $("#save-new-subscription").click(function(){
                let service_id = $("#service_id").val();
                let account_id = $("#account_id").val();
                let customer_id = $("#customer_id").val();
                let amount = $("#amount").val();
                let date_to = $("#date_to").val();
                let profile = $("#profile").val();
                let pin = $("#pin").val();

                if( customer_id && date_to && amount ){
                    $.post("{{route('subscriptions.store')}}", 
                            { 
                                service_id:service_id,
                                account_id: account_id, 
                                customer_id: customer_id, 
                                profile: profile,
                                pin: pin, 
                                date_to:date_to,
                                amount: amount 
                            }, 
                    function(response){
                        let data = response;
                        let addClass = "btn-success";

                        Swal.fire({
                            title:"Notificacion",
                            text: data.message,
                            icon: data.type
                        });

                        if(parseInt(eds) > -1){
                            if(parseInt(data.subscription.last_days) <= eds){
                                addClass = "btn-warning";
                            }

                            if(parseInt(data.subscription.last_days) == 0){
                                addClass = "btn-danger";
                            }
                        }

                        $("#"+$("#active_free_profile").val()).attr("class","modal-edit btn "+addClass).addClass(addClass).attr("data-subscription", JSON.stringify(data.subscription)).removeClass('modal-new').addClass('modal-edit').attr("data-customer",JSON.stringify(data.subscription.customer));

                        $("#service_id, #account_id, #customer_id, #amount, #date_to, #profile, #pin, #active_free_profile").val("");
                        $("#modal-new").modal('hide');
                    });
                }else{
                    Swal.fire({
                        title:"Error",
                        text:"Los Campos: Cliente, Facturacion y Monto son  Obligatorios!!",
                        icon:"error"
                    });

                    return false;
                }
            });

            $("body").on('click','a.modal-edit', function(){
                let data = JSON.parse($(this).attr("data-subscription"));
                let data_customer = JSON.parse($(this).attr("data-customer"));
                $("#edit_subscription_id").val(data.id);
                $("#copy_button").attr("data-id",data.id);
                $("#send-by-whatsapp").attr("data-id",data.id);
                $("#send-by-whatsapp").attr("data-number",data_customer.phone);
                $("#edit_active_free_profile").val($(this).attr("id"));
                $("#edit_customer_id").val(data.customer_id);
                $("#edit_profile").val(data.profile);
                $("#edit_pin").val(data.pin);
                $("#edit_date_to").val(data.date_to);
                $("#extend_edit_date_to").val(data.date_to);
                $("#last_days").val(data.last_days);
                $("#modal-edit").modal('show');
            });

            $(".close-edit-subscription").click(function(){
                $("#edit_service_id, #edit_account_id, #edit_customer_id, #edit_amount, #edit_date_to, #edit_profile, #edit_pin, #edit_active_free_profile, #last_days").val("");
                $("#modal-edit").modal('hide');
            });

            $("#save-edit-subscription").click(function(){
                let subscription_id = $("#edit_subscription_id").val();
                let customer_id = $("#edit_customer_id").val();
                let profile = $("#edit_profile").val();
                let pin = $("#edit_pin").val();

                if(customer_id){

                    $.post("{{route('subscriptions.update_data')}}",{
                            id:subscription_id,
                            customer_id:customer_id,
                            profile:profile,
                            pin:pin
                        }, function(response){
                            let data = response;
                            let addClass = "btn-success";

                            Swal.fire({
                                title: "Notificacion",
                                text: data.message,
                                icon: data.type
                            });

                            if(parseInt(eds) > -1){
                                if(parseInt(data.subscription.last_days) <= eds){
                                    addClass = "btn-warning";
                                }

                                if(parseInt(data.subscription.last_days) == 0){
                                    addClass = "btn-danger";
                                }
                            }

                            $("#"+$("#edit_active_free_profile").val()).attr("class","modal-edit btn "+addClass).addClass(addClass).attr("data-subscription", JSON.stringify(data.subscription)).attr("data-customer",JSON.stringify(data.subscription.customer));;

                            $("#edit_subscription_id, #edit_customer_id, #edit_date_to, #edit_profile, #edit_pin, #edit_active_free_profile, #last_days").val("");
                            $("#modal-edit").modal('hide');
                        });
                }else{
                    Swal.fire({
                        title: "Notificacion",
                        text: "El cliente es obligatorio",
                        icon: "error"
                    });
                }
            });

            $("#save-extend-subscription").click(function(){

                let subscription_id = $("#edit_subscription_id").val();
                let extend_amount = $("#extend_amount").val();
                let extend_date_to = $("#extend_edit_date_to").val();

                if(extend_amount && extend_date_to){
                    $.post("{{route('subscriptions.extends')}}",{ id:subscription_id, amount: extend_amount, date_to: extend_date_to }, function(response){
                        let data = response;
                        let addClass = "btn-success";
                        Swal.fire({
                            title: "Notificacion",
                            text: data.message,
                            icon: data.type
                        });

                        if(parseInt(eds) > -1){
                            if(parseInt(data.subscription.last_days) <= parseInt(eds)){
                                addClass = "btn-warning";
                            }

                            if(parseInt(data.subscription.last_days) == 0){
                                addClass = "btn-danger";
                            }
                        }

                        $("#"+$("#edit_active_free_profile").val()).attr("class","modal-edit btn "+addClass).attr("data-subscription", JSON.stringify(data.subscription));

                        $("#edit_subscription_id, #edit_customer_id, #edit_date_to, #edit_profile, #edit_pin, #edit_active_free_profile, #extend_amount, #extend_date_to, #last_days").val("");
                        $("#modal-edit").modal('hide');
                    });
                }else{
                    Swal.fire({
                        title: "Notificacion",
                        text: "Los campos de monto y facturacion son Obligatorios!!",
                        icon: "error"
                    });
                }
            });
        });
    </script>
    @include('admin.partials.messages')
@stop