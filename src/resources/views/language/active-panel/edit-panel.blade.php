<a class="active-panel__button index-parases"><i class="icon fa fa-refresh"></i>@lang('Проиндексировать фразы')</a>

@push("js")
    <script>
        $(".index-parases").bind("click", function(){
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