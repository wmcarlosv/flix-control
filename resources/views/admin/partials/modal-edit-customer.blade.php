<div class="modal fade" id="modal-edit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Editar Subscripcion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="sub-data-tab" data-toggle="tab" href="#sub-data" role="tab" aria-controls="sub-data"
              aria-selected="true">Datos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="extend-tab" data-toggle="tab" href="#extend" role="tab" aria-controls="extend"
              aria-selected="false">Extender Subscripcion</a>
          </li>
        </ul>

        <div class="tab-content" id="myTabContentEdit">

            <div class="tab-pane fade show active" id="sub-data" role="tabpanel" aria-labelledby="sud-data-tab">
               <input type="hidden" id="edit_active_free_profile">
                <input type="hidden" id="edit_subscription_id" />
                <div class="form-group">
                    <label for="">Cliente:</label>
                    <select name="customer_id" id="edit_customer_id" class="form-control">
                        <option value="">-</option>
                        @foreach($customers as $customer)
                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Perfil:</label>
                    <input type="text" class="form-control" id="edit_profile" />
                </div>
                <div class="form-group">
                    <label for="">Pin:</label>
                    <input type="text" class="form-control" id="edit_pin" />
                </div>
                <div class="form-group">
                    <label for="">Facturacion:</label>
                    <input type="date" readonly class="form-control" id="edit_date_to" />
                </div>
                <div class="form-group">
                    <label for="">Dias Restantes:</label>
                    <input type="text" class="form-control" readonly id="last_days" />
                </div>
                <button type="button" class="btn btn-success" id="save-edit-subscription"><i class="fas fa-save"></i> Actualizar</button>
                <a href="#" class="btn btn-info copy-data" id="copy_button" title="Copiar Informacion de Subscripcion"><i class="fas fa-copy"></i></a>
                <a href="#" class="btn btn-success send-by-whatsapp" id="send-by-whatsapp" title="Notificar por Whatsapp"><i class="fab fa-whatsapp"></i></a>
            </div>
            
            <div class="tab-pane fade" id="extend" role="tabpanel" aria-labelledby="extend-tab">
                <div class="form-group">
                    <label for="">Monto:</label>
                    <input type="number" class="form-control" id="extend_amount" />
                </div>
                <div class="form-group">
                    <label for="">Nueva Facturacion:</label>
                    <input type="date" class="form-control" id="extend_edit_date_to" />
                </div>
                <button type="button" class="btn btn-success" id="save-extend-subscription"><i class="fas fa-save"></i> Extender</button>
            </div>

        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger close-edit-subscription"><i class="fas fa-times"></i> Cancelar</button>
      </div>
    </div>
  </div>
</div>