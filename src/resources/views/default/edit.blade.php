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

            <form-ajax inline-template form-id="form-edit" ref="form-edit">
                {!! Form::open([
                    'route' => ['admin::update', $controller_name, $object->id],
                    'class' => 'form',
                    'files' => true,
                    'id'=> 'form-edit',
                    '@submit.prevent' => 'submit'
                ]) !!}

                    {{ method_field('POST') }}

                    @foreach($fieldGroup['fields'] as $field)
                        @includeDefault('fields.' . $field['type'])
                    @endforeach

                    @includeDefault('buttons')

                {!! Form::close() !!}
            </form-ajax>
        </section>
    @endforeach

    @includeDefault('modules')

@endsection