@if (isset($activePanel['buttons']['export']) && $activePanel['buttons']['export'])
    <a class="active-panel__button active-panel__button--export active-panel__button--icon"><i class="icon icon--active-panel-export"></i></a>
@endif
@if (isset($activePanel['buttons']['copy']) && $activePanel['buttons']['copy'])
    <a class="active-panel__button active-panel__button--copy active-panel__button--icon"><i class="icon icon--active-panel-copy"></i></a>
@endif