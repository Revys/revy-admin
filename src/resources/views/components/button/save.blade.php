@php
    $label = $label ?? __('admin::buttons.save'); 
    $style = (isset($style)) ? $style : 'success';
    $type = $type ?? 'link';
@endphp

@if ($type == 'submit')
    {{ Form::button($label, ['type' => 'submit', 'class' => 'button button--' . $style . ' button--' . $action]) }}
@else
    <a class="button button--{{ $style }} button--{{ $action }}" href="javascript:void(0)"
       id="button-{{ $action }}">{!! $label !!}-save-l</a>
@endif