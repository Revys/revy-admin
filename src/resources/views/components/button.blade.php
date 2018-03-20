@php
    $style = (isset($style)) ? $style : 'default';
    $type = $type ?? 'link';
    $method = $method ?? 'GET';
@endphp

@if ($type == 'submit')
    {{ Form::button($label, ['type' => 'submit', 'class' => 'button button--' . $style . ' button--' . $action]) }}
@else
    @if ($method == 'GET')
        <a class="button button--{{ $style }} button--{{ $action }}"
           href="@if (isset($href) && is_callable($href)){{ $href($controller_name, (isset($object) ? $object : null)) }}@else{{ $href }}@endif">{!! $label !!}</a>
    @else
        <a class="button button--{{ $style }} button--{{ $action }}" href="javascript:void(0)"
           id="button-{{ $action }}">{!! $label !!}</a>

        @push ('js')
            <script>
                $("#button-{{ $action }}").click(function () {
                    let button_url = "@if (isset($href) && is_callable($href)){{ $href($controller_name, (isset($object) ? $object : null)) }}@else{{ $href }}@endif";
                    axios['{{ strtolower($method) }}'](button_url, {})
                        .then(response => {
                            if (response.data.redirect)
                                window.location.href = response.data.redirect;
                        });
                });
            </script>
        @endpush
    @endif
@endif