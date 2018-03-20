<div class="form__group">
	<label class="form__group__label" for="form-input-{{ $field['field'] }}">{{ $field['label'] }}</label>
	<div class="form__group__input-group">
		{{ Form::select(
			$field['field'],
			$field['values']((isset($object) ? $object : null)), 
			(isset($object) ? (! is_string($field['value']) ? $field['value']($object) : $object->{$field['value']}) : ''), 
			[
				'id' => 'form-input-' . $field['field'], 
				'class' => 'select select--full-width',
				'v-model' => 'form.' . $field['field']
			]
		) }}
		@includeDefault('fields._errors')
	</div>
</div>

@push('js')
	<script>
		let el = document.getElementById("form-input-{{ $field['field'] }}");

		el.select({
			staticWidth: false,
			search: true
		});
	</script>
@endpush