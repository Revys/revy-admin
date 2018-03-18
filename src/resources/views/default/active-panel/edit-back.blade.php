@if (isset($activePanel['buttons']['back']) && $activePanel['buttons']['back'])
    <a class="active-panel__button active-panel__button--back active-panel__button--icon" href="{{ route('admin::list', [$controller_name]) }}"><i class="icon icon--active-panel-back"></i></a>
@endif