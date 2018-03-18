@extends('admin::layouts.enter-screen')

@section('content')

	<section class="login-block">
		{!! Form::open([
			'route' => 'admin::login::signin',
			'class' => 'form',
			'id' => 'login-block',
			'@submit.prevent' => 'onSubmit($event)',
			'@keydown' => 'form.errors.clear($event.target.name)'
		]) !!}

			<h1>@lang('Вход в систему')</h1>

			<div class="form__group">
				<label class="form__group__label" for="form-input-id">@lang('Логин') / @lang('Email')</label>
				{{ Form::text('id', null, ['v-model' => 'form.id', 'id' => 'form-input-id', 'class' => 'form__group__input']) }}

				 <i class="error-flag" :class="{ visible: form.errors.has('email') }">!</i>
			</div>

			<div class="form__group">
				<label class="form__group__label" for="form-input-password">@lang('Пароль')</label>
				{{ Form::password('password', ['v-model' => 'form.password', 'id' => 'form-input-password', 'class' => 'form__group__input']) }}

				 <i class="error-flag" :class="{ visible: form.errors.has('password') }">!</i>
			</div>

			<div class="form__group form__group--toggler">
				<label class="form__group__label" for="form-input-remember">@lang('Запомнить меня')</label>
				<div class="switcher">
					<input type="checkbox" id="form-input-remember" v-model="form.remember">
					<div class="switcher__lever"></div>
				</div>
			</div>

			{{ Form::submit(__('Вход'), ['class' => 'button button--primary']) }}

		{!! Form::close() !!}
	</section>

@endsection

@push('js')
	<script>
		// Login Form
		window.LoginForm = new Vue({
			el: '#login-block',

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