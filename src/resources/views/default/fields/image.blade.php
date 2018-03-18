<div class="form__group">
    <label class="form__group__label" for="form-input-{{ $field['field'] }}">{{ $field['label'] }}</label>
    <div class="form__group__input-group">
        {{ Form::file(
            $field['field'],
            [
                'multiple' => ($field['field'] == 'images' ? true : false),
                'id' => 'form-input-' . $field['field'],
                'class' => 'form__group__input ' .
                           'form__group__image ' .
                           ($errors->any($field['field']) ? 'form__group__input--error form__group__image--error' : '')
            ]
        ) }}

        @includeDefault('fields.images.list')

        @includeDefault('fields._errors')

        @push('js')
            <script>

            </script>
        @endpush
    </div>
</div>