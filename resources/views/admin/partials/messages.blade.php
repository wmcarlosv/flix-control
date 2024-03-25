<script>
	var currentTable;
	$(document).ready(function(){

		currentTable = $(".data-table").DataTable();

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