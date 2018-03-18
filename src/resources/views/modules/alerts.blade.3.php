<!-- Alerts -->
@if (isset($alerts))
	<div id="alerts">
		@foreach($alerts as $alert)
			<div class="alert alert--{{ $alert['tag'] }}">{{ $alert['message'] }}</div>
		@endforeach
	</div>
@endif

@push('js')