@extends(RevyAdmin::layout('base'))

@section('content')

	@includeDefault('active-panel/edit')

	@foreach($fieldsMap as $fieldGroup)
		<section class="card card--form">
			@if(isset($fieldGroup['caption']))
				<div class="card__header">
					<h2>{{ $fieldGroup['caption'] }}</h2>
				</div>
			@endif

			{!! Form::open([
				'route' => ['admin::update', $controller_name, $object->id],
				'class' => 'form',
				'files' => true
			]) !!}
				{{ method_field('PUT') }}
				
				@foreach($fieldGroup['fields'] as $field)
					@includeDefault('fields.' . $field['type'])
				@endforeach

				@includeDefault('buttons')

			{!! Form::close() !!}
		</section>
	@endforeach
	
	@includeDefault('modules')

@endsection