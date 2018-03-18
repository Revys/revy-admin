@if($items->count())
	{{ Form::select('order', [
		'created' => __('Создан'),
		'updated' => __('Изменён')
	], null, ['class' => 'select', 'id' => $oc . '-order-select']) }}

	@push('js')
		<script>
			document.getElementById('{{ $oc }}-order-select').select({
				staticWidth: true 
			});
		</script>
	@endpush
@endif