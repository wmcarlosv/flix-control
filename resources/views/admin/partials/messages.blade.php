<script>
	$(document).ready(function(){

		$(".data-table").DataTable();

		@if(Session::get('success'))
			Swal.fire({
			  title: "Sucess",
			  text: "{{ Session::get('success') }}",
			  icon: "success"
			});
		@endif

		@if(Session::get('error'))
			Swal.fire({
			  title: "Error",
			  text: "{{ Session::get('error') }}",
			  icon: "error"
			});
		@endif

		$("body").on("click","button.delete-record", function(){
			let id = $(this).attr("data-id");

			Swal.fire({
			  title: "Are you sure to perform this action?",
			  icon: "question",
			  showCancelButton: true,
			  confirmButtonText: "Confirm",
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