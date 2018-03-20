@if (count($fieldGroup['actions']))
	<div class="form__group__actions">
		@foreach($fieldGroup['actions'] as $action => $button)
			@if ($button !== false)
				@includeFirst(["admin::components.button." . $action, "admin::components.button"], $button)
			@endif
		@endforeach
	</div>

	{{--@push('js')--}}
		{{--<script>--}}
			{{--$(".form__group__actions .button").bind("click", function(e){--}}
				{{--if ($(this).hasClass('button--loading'))--}}
					{{--return false;--}}

				{{--$(this).width($(this).width()).addClass('button--loading');--}}
			{{--});--}}
		{{--</script>--}}
	{{--@endpush--}}
@endif