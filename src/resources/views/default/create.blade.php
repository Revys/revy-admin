@extends(RevyAdmin::layout('base'))

@section('content')

	@includeDefault('active-panel/create')

	@foreach($fieldsMap as $fieldGroup)
		<section class="card card--form">
			@if(isset($fieldGroup['caption']))
				<div class="card__header">
					<h2>{{ $fieldGroup['caption'] }}</h2>
				</div>
			@endif

			<form-ajax inline-template form-id="form-create" ref="form-create">
				{!! Form::open([
					'route' => ['admin::insert', $controller_name],
					'class' => 'form',
					'files' => true,
                    'id'=> 'form-create',
                    '@submit.prevent' => 'submit'
				]) !!}

					@foreach($fieldGroup['fields'] as $field)
						@includeDefault('fields.' . $field['type'])
					@endforeach

					@includeDefault('buttons')

				{!! Form::close() !!}
			</form-ajax>
		</section>
	@endforeach

@endsection