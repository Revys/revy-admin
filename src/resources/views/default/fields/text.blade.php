<div class="form__group form__group--editor @if(isset($field['size'])) form__group__input--{{ $field['size'] }}@endif">
	<label class="form__group__label" for="form-input-{{ $field['field'] }}">{{ $field['label'] }}</label>
	<div class="form__group__input-group">
		@if (! isset($field['translatable']))
			{{ Form::textarea(
				$field['field'], 
				(isset($object) ? (! is_string($field['value']) ? $field['value']($object) : $object->{$field['value']}) : ''), 
				[
					'id' => 'form-input-' . $field['field'], 
					'class' => 'form__group__input form__group__textarea editor' . 
						(isset($field['size']) ? ' form__group__input--' . $field['size'] : '') .
						($errors->any($field['field']) ? ' form__group__input--error' : ''),
					'v-model' => 'form.' . $field['field']
				]
			) }}
			@includeDefault('fields._errors')
		@else
			@includeDefault('fields.translate.text')
		@endif
	</div>
</div>