<link rel="stylesheet" href="{{ asset('admin-assets/css/app.css') }}" />

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Scripts -->
<script>
	window.Laravel = {!! json_encode([
		'csrfToken' => csrf_token(),
	]) !!};
	window.language = '{{ $locale->code }}';
</script>

<link rel="shortcut icon" type="image/png" href="{{ asset('admin-assets/img/site/logo.png') }}"/>