<div class="form__group">
	<label class="form__group__label" for="form-input-{{ $field['field'] }}">{{ $field['label'] }}</label>
	<div class="form__group__input-group">
		{{ Form::text(
			$field['field'], 
			(isset($object) ? (! is_string($field['value']) ? $field['value']($object) : $object->{$field['value']}) : ''), 
			[
				'id' => 'form-input-' . $field['field'],
				'class' => 'form__group__input' . 
					(isset($field['size']) ? ' form__group__input--' . $field['size'] : '')
			]
		) }}
		<span class="form__group__input__picked-icon"></span>

		@includeDefault('fields._errors')
	</div>
</div>

@push('js')
	<script>
		$("#form-input-{{ $field['field'] }}").iconpicker({
			component: '.form__group__input__picked-icon, .iconpicker-component',
			templates: {
				search: '<input type="search" class="form-control iconpicker-search" placeholder="{{ __('Поиск') }}" />',
       			iconpickerItem: '<a role="button" href="#" tabindex="-1" class="iconpicker-item"><i></i></a>'
			},
			hideOnSelect: true,
			animation: false,
			fullClassFormatter: function(val) {
				return 'fa ' + val;
			},
			inputSearch: true
		});
        $("#form-input-{{ $field['field'] }}").on('iconpickerSelected', function(event){
			vm.$refs['form-edit'].form.{{ $field['field'] }} = event.iconpickerValue;
        });
	</script>
@endpush