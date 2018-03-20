@extends('admin::layouts.enter-screen')

@section('content')

	<form-ajax inline-template form-id="login-block">
		<section class="login-block">
			{!! Form::open([
				'route' => 'admin::login::signin',
				'class' => 'form',
				'id' => 'login-block',
                '@submit.prevent' => 'submit'
			]) !!}

				<h1>@lang('Вход в систему')</h1>

				<div class="form__group">
					<label class="form__group__label" for="form-input-id">@lang('Логин') / @lang('Email')</label>
					{{ Form::text('id', null, ['v-model' => 'form.id', 'id' => 'form-input-id', 'class' => 'form__group__input']) }}
				</div>

				<div class="form__group">
					<label class="form__group__label" for="form-input-password">@lang('Пароль')</label>
					{{ Form::password('password', ['v-model' => 'form.password', 'id' => 'form-input-password', 'class' => 'form__group__input']) }}
				</div>

				<div class="form__group form__group--toggler">
					<label class="form__group__label" for="form-input-remember">@lang('Запомнить меня')</label>
					<div class="switcher">
						<input type="checkbox" id="form-input-remember" v-model="form.remember">
						<div class="switcher__lever"></div>
					</div>
				</div>

				<input type="hidden" name="redirect" value="{{ request('redirect') }}" v-model="form.redirect">

				{{ Form::submit(__('Вход'), ['class' => 'button button--primary']) }}

			{!! Form::close() !!}
		</section>
	</form-ajax>

@endsection

@push('js')
	<script>
		// Login Form
		window.LoginForm = new Vue({
			el: '#login-block2',

			data: {
				form: new Form({
					id: '',
					password: '',
					remember: false
				})
			},

			methods: {
				onSubmit(e) {
					let form = this.form;

					form.post(e.target.action)
						.then(response => {
							if (! response.error) {
                                function getUrlVars() {
                                    var vars = {};
                                    window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                                        vars[key] = value;
                                    });
                                    return vars;
                                }

                                if (getUrlVars()['redirect'])
                                    window.location.href = decodeURIComponent(getUrlVars()['redirect']);
                                else
                                    window.location.href = "../";
							} else {
								form.set(response);
							}
						});
				}
			}
		});
	</script>
@endpush