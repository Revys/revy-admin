<a class="active-panel__button index-pharases"><i class="icon fa fa-refresh"></i>@lang('Проиндексировать фразы')</a>

@push("js")
    <script>
        $(".index-pharases").bind("click", function(){
            $("#translations").request({
                controller: "language",
                action: "index_phrases",
                data: {
                    language: "{{ $object->id }}"
                }
            });
        });
    </script>
@endpush