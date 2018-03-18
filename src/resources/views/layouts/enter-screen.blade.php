<!DOCTYPE html>
<html lang="{{ $locale->code }}">
	<head>
		@include('admin::includes.head')
	</head>
	<body>
		<div id="app" class="no-sidebar no-header">
			@yield('content')
		</div>

		@include('admin::includes.footer')
	</body>
</html>