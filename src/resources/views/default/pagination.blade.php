@if($controller::paginated($items) && $items->count())
	<div class="pagination">
		<span class="pagination__now">{{ $items->perPage() * ($items->currentPage() - 1) + 1 }}-{{ $items->perPage() * ($items->currentPage() - 1) + $items->count() }}</span>
		<span class="pagination__total">&nbsp{{ __('из') }}&nbsp{{ $items->total() }}</span>

		<a class="pagination__button pagination__button--prev{{ $items->previousPageUrl() == '' ? ' pagination__button--disabled' : '' }}" href="{{ $items->previousPageUrl() }}"></a>
		<a class="pagination__button pagination__button--next{{ $items->nextPageUrl() == '' ? ' pagination__button--disabled' : '' }}" href="{{ $items->nextPageUrl() }}"></a>
	</div>

	@push('js')
		<script>
			$("#{{ $oc }} .pagination a").bind("click", function(e){
				e.preventDefault();

				if ($(this).attr("href") !== "") {
					$("#{{ $oc }}").request({
						url: $(this).attr("href")
					});
				}
			});
		</script>
	@endpush
@endif