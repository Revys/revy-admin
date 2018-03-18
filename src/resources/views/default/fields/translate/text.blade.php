<div class="form__group__translation">
	@php
		$baseField = $field['field'];
	@endphp
	@foreach($languages as $language)
		@php
			$field['field'] = \Revys\RevyAdmin\App\RevyAdmin::getTranslationFieldName($baseField, $language->code);
		@endphp

		<div class="form__group__translation__input">
			<div class="form__group__translation__locale">{{ $language->code }}</div>
			{{ Form::textarea(
				$field['field'], 
				((isset($object) && $object->translate($language->code)) ? $object->translate($language->code)->{$field['value']} : ''), 
				[
					'id' => 'form-input-' . $field['field'], 
					'class' => 'form__group__input form__group__textarea editor' . 
						(isset($field['size']) ? ' form__group__input--' . $field['size'] : '') .
						($errors->any($field['field']) ? ' form__group__input--error' : '')
				]
			) }}
		</div>

		@includeDefault('fields._errors')
	@endforeach
</div>