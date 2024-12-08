@extends('adminlte::page')

@section('title', $title)

@section('content')
    <div class="row" style="margin-top:20px;">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h2><i class="fas fa-star"></i> {{ $title }}</h2>
                </div>
                <div class="card-body" id="myTabContentEdit">
                    @include('admin.partials.table',['columns'=>$columns, 'data'=>$data, 'route'=>'subscriptions'])
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-extend">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Extender la Facturacion</h5>
            <button type="button" class="close close-modal-extend" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{route('extend_subscriptions')}}" method="POST">
            @method('PUT')
            @csrf
              <div class="modal-body">
                <input type="hidden" name="id" />
                <div class="form-group">
                    <label for="">Valor de la Facturacion:</label>
                    <input type="number" step="0.01" name="amount" required class="form-control" />
                </div>
                <div class="form-group">
                    <label for="">Nueva Fecha Facturacion:</label>
                    <input type="date" name="date_to" required class="form-control" />
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="save-modal-extend"><i class="fas fa-save"></i> Extender</button>
                <button type="button" class="btn btn-danger close-modal-extend"><i class="fas fa-times"></i> Cancelar</button>
              </div>
          </form>
        </div>
      </div>
    </div>
@stop

@section('js')
    @include('admin.partials.messages')
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('click', function(event) {
                if (event.target.classList.contains('page-link')) {
                    // Select all 'a.btn-info' elements
                    document.querySelectorAll('a.btn-info').forEach(function(element) {
                        // Add 'btn-warning' class
                        element.classList.add('btn-warning');

                        // Set the 'title' attribute
                        element.setAttribute('title', 'Extender Facturacion');

                        // Set the HTML content
                        element.innerHTML = '<i class="fas fa-external-link-alt"></i>';
                    });
                    event.preventDefault();
                }
            });
        });

        $(document).ready(function(){

            $(".select-2").select2();

            $("body").on('click','button.move_account_modal', function(){
                let id = $(this).data("id");
                let element_id = $(this).data("element");
                let account_id = $(this).data("account");
                getAccounts(id, element_id, account_id);
            });

            $(".select-profile").change(function(){
                let account_id = $(this).find(":selected").data("id");
                let element = $(this).find(":selected").data("element");
                getProfiles(account_id, element);
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            function getAccounts(id, element_id, account_id){
                $.get('/admin/get-accounts/'+id, function(response){
                    let data = response.data;
                    if(data.length > 0){
                        $("#cuenta-"+element_id).html("<option value='' data-element='"+element_id+"'>-</option>");
                        $.each(data, function(v,e){
                            if(account_id != e.id){
                                $("#cuenta-"+element_id).append("<option data-id='"+e.id+"' data-element='"+element_id+"' value='"+e.id+"'>"+e.email+"</option>");
                            }
                        });
                    }
                });
            }

            function getProfiles(id, element){
                if(id){
                   $.get('/admin/get-profiles/'+id, function(response){
                        let data = response.data;
                        $("#perfil-"+element).html("<option value=''>-</option>");
                        $.each(data, function(v,e){
                            $("#perfil-"+element).append("<option value='"+e.id+"'>"+e.name+" ("+(e.pin ? e.pin : 'Sin Pin')+")</option>");
                        });

                    }); 
               }else{
                $("#perfil-"+element).html("<option value=''></option>");
               }
                
            }

            $("a.btn-info").addClass('btn-warning').attr("title","Extender Facturacion").html('<i class="fas fa-external-link-alt"></i>');
            $("body").on('click','a.btn-info',function(){
                let id = $(this).attr("data-id");
                $("input[name='id']").val(id);
                $("#modal-extend").modal({backdrop: 'static', keyboard:false},'show');
                return false;
            });

            $("button.close-modal-extend").click(function(){
                $("#modal-extend").modal('hide');
            });

            $("body").on('click','a.copy_button',function(){
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

            $("body").on('click','a.send-by-whatsapp',function(){
                let id = $(this).attr("data-id");
                let phone = $(this).attr("data-phone");
                $.get("get-data-message/"+id, function(response){
                    let data = response;
                    if(data.success){
                        let link = encodeURI("https://wa.me/"+phone+"?text="+data.message);
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
        });
    </script>
@stop