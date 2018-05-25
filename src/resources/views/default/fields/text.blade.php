<div class="form__group form__group--editor @if(isset($field['size'])) form__group__input--{{ $field['size'] }}@endif">
	<label class="form__group__label" for="form-input-{{ $field['field'] }}">{{ $field['label'] }}</label>
	<div class="form__group__input-group">
		@if (! isset($field['translatable']))
			<input type="hidden" data-name="{{ $field['field'] }}" value="{{ (isset($object) ? (! is_string($field['value']) ? $field['value']($object) : $object->{$field['value']}) : '') }}">

			<froala :tag="'textarea'" :id="'{{ 'form-input-' . $field['field'] }}'" :config="config" v-model="form.text"></froala>

			@includeDefault('fields._errors')
		@else
			@includeDefault('fields.translate.text')
		@endif
	</div>
</div>