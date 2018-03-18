<div class="actions">
	<div class="actions__action button button--round button--danger" data-action="delete">
		<i class="icon icon--delete"></i>
	</div>
	
	<div class="actions__action button button--round" data-action="publish">
		<i class="fa fa-eye"></i>
	</div>

	<div class="actions__action button button--round" data-action="hide">
		<i class="fa fa-eye-slash"></i>
	</div>
</div>

@push('js')
	<script>
		// Change checkbox state by clicking on the parent <td>
		$(".actions__action").bind("click", function(e){
			let action = $(this).data("action");

			if (action == "delete") {
				if (confirm("@lang('Подтвердить удаление элементов?')"))
					$("#{{ $oc }}").request({
						controller: '{{ $controller_name }}',
						action: 'fast_delete',
						data: $(".data-table__values .checkbox__input").serialize()
					});
			} else if (action == "publish") {
				$("#{{ $oc }}").request({
					controller: '{{ $controller_name }}',
					action: 'fast_publish',
					data: $(".data-table__values .checkbox__input").serialize()
				});
			} else if (action == "hide") {
				$("#{{ $oc }}").request({
					controller: '{{ $controller_name }}',
					action: 'fast_hide',
					data: $(".data-table__values .checkbox__input").serialize()
				});
			}
		});
	</script>
@endpush