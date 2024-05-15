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
        $(document).ready(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $("a.btn-info").addClass('btn-warning').attr("title","Extender Facturacion").html('<i class="fas fa-external-link-alt"></>');
            $("a.btn-info").click(function(){
                let id = $(this).attr("data-id");
                $("input[name='id']").val(id);
                $("#modal-extend").modal({backdrop: 'static', keyboard:false},'show');
                return false;
            });

            $("button.close-modal-extend").click(function(){
                $("#modal-extend").modal('hide');
            });

            $(".copy_button").click(function(){
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

            $(".send-by-whatsapp").click(function(){
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