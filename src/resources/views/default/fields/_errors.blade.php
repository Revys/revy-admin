<div class="form__group__errors" v-show="form.errors.any()">
	<div class="form__group__error" v-for="(error, index) in form.errors.get('{{ $field['field'] }}')" :key="index" v-text="error"></div>
</div>

@if ($errors->any($field['field']))
	<div class="form__group__errors">
		@foreach($errors->get($field['field']) as $error)
			<div class="form__group__error">{{ $error }}</div>
		@endforeach
	</div>
@endif