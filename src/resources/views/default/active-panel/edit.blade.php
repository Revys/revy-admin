<div class="active-panel active-panel--edit">
	@includeDefault("active-panel/edit-back")

	@if (isset($activePanel['caption']))
		<h1 class="active-panel__caption">{!! $activePanel['caption'] !!}@if(isset($object))<b><small>ID:</small> {{ $object->id }}</b>@endif</h1>
	@endif

	@includeDefault("active-panel/edit-panel")

	@includeDefault("active-panel/edit-actions")
</div>