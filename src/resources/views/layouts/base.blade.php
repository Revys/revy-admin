<!DOCTYPE html>
<html lang="{{ $locale->code }}">
	<head>
		@include('admin::includes.head')
	</head>
	<body>
		<div id="app">
			<loader></loader>

			@include('admin::includes.modules')
			
			@include('admin::blocks.header')
			@include('admin::navigation.left')

			<div id="content">
				@yield('content')
			</div>

			@include('admin::blocks.footer')
		</div>
		
		@include('admin::includes.footer')
	</body>
</html>