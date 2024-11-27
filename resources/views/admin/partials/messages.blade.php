<script>
	var currentTable;
	var inputs;
	$(document).ready(function(){

		let params = $("input[name='multi_select[]']");
		if(params.length > 0 ){
			inputs = params;
		}

		$("#select_all").click(function(){
			if($(this).prop("checked")){
				checkAll();
			}else{
				checkAll(false);
			}
		});

		$("#delete_massive").click(function(){
			let cont = 0;
			let ids="";
			$.each(inputs, function(v,e){
				if($(this).prop("checked")){
					ids+=e.value+",";
					cont++;
				}
			});

			console.log(ids);

			if(cont > 0){
				ids = ids.slice(0, -1);
				$("#selected_rows").val(ids);
				if(confirm("Estas seguro de eliminar los registros seleccionados?")){
					$("#massive_form").submit();
				}
			}else{
				alert("Debes Seleccionar al menos un Registro!!");
			}
		});

		function checkAll(ischeck = true){
			$.each(inputs, function(v,e){
				$(this).prop("checked", ischeck);
			});
		}

		currentTable = $(".data-table").DataTable({
			layout: {
		        topStart: {
		            buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5']
		        }
		    }
		});

		@if(Session::get('success'))
			Swal.fire({
			  title: "Notificacion",
			  text: "{{ Session::get('success') }}",
			  icon: "success"
			});
		@endif

		@if(Session::get('error'))
			Swal.fire({
			  title: "Notificacion",
			  text: "{{ Session::get('error') }}",
			  icon: "error"
			});
		@endif

		$("body").on("click","button.delete-record", function(){
			let id = $(this).attr("data-id");

			Swal.fire({
			  title: "Estas seguro de realizar esta Accion?",
			  icon: "question",
			  showCancelButton: true,
			  cancelButtonText: 'Cancelar',
			  confirmButtonText: "Confirmar",
			  confirmButtonColor: "green",
			  cancelButtonColor: "red",
			}).then((result) => {
			  if (result.isConfirmed) {
			    $("#form_"+id).submit();
			  }
			});
		});
		
	});
</script>