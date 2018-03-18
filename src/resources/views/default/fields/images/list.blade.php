<div class="form__images">
    @if(isset($object) and $object->hasImages())
        <images-list :images="{{ json_encode($object->images()) }}"></images-list>

        {{--@foreach($object->images() as $image)--}}
            {{--<div class="form__images__image">--}}
                {{--<img src="{{ (isset($object) ? (! is_string($field['value']) ? $field['value']($object) : $object->{$field['value']}) : '') }}"/>--}}
                {{--<input type="text" disabled="true" style="font-size: 14px" name="{{ $field['field'] }}_positions[]" value="{{ $image->filename }}"/>--}}
                {{--<div class="form__images__image__delete"></div>--}}
            {{--</div>--}}
        {{--@endforeach--}}
    @endif
</div>

@push('js')
    <script>
        // let images = document.getElementsByClassName('form__images')[0];
        // Sortable.create(images);
    </script>
@endpush