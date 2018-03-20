<!DOCTYPE html>
<html lang="{{ $locale->code }}">
	<head>
		@include('admin::includes.head')
	</head>
	<body>
		<div id="app" class="no-sidebar no-header">
			<loader></loader>

			@include('admin::includes.modules')

			@yield('content')

			@include('admin::blocks.footer')
		</div>

		@include('admin::includes.footer')
	</body>
</html>