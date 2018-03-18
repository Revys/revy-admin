@php
    $label = $label ?? __('admin::buttons.save'); 
    $style = (isset($style)) ? $style : 'success';
    $type = $type ?? 'submit';
@endphp

@if ($type == 'submit')
    {{ Form::button($label, ['type' => 'submit', 'class' => 'button button--' . $style . ' button--' . $action]) }}
@else
    <a class="button button--{{ $style }} button--{{ $action }}" href="@if (isset($link) && is_callable($link)){{ $link($controller_name, (isset($object) ? $object : null)) }}@else{{ $link }}@endif">{!! $label !!}</a>
@endif