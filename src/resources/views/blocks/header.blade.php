<header id="header">
	<div class="header__sitename">
		<a href="/{{ $locale->code }}" class="header__sitename__link" target="_blank">
			{{ Html::image('admin-assets/img/site/logo.svg', false, ['class' => 'logo', 'width' => 32, 'height' => 32]) }}

			{{ env('APP_NAME', __('Веб-сайт')) }}
		</a>
	</div>	
	
	@include("admin::navigation.top")

	<div class="header__language">
		{{ Form::select('language', \Revys\Revy\App\Language::getLanguages()->pluck('title', 'code')->toArray(), $locale->code, ['class' => 'select header__language__select']) }}
	</div>

	@push('js')
		<script>
			document.getElementsByClassName("header__language__select").select({
				staticWidth: true,
				afterChange: function(element) {
					window.location.href = "/{{ $uri }}".replace("/{{ $locale->code }}", "/" + element.value);
				}
			});
		</script>
	@endpush

	<div class="header__translation	@translation_mode header__translation--active @endtranslation_mode" title="{{ __('Режим перевода') }}"><i class="icon icon--language"></i></div>

	<a href="{{ route('admin::settings') }}" class="header__settings"><i class="icon icon--settings"></i></a>

	<div class="header__user">{{ $user->login }}</div>
	
	<a href="{{ route('admin::login::logout') }}" class="header__exit"><i class="icon icon--exit"></i></a>
</header>

@push('js')
	<script>
		$(".header__translation").bind("click", function(e){
			let button = $(this);

			$.request({
				controller: "language",
				action: "toggle_translation_mode",
				complete: function(result) {
					button.toggleClass("header__translation--active");
					window.location.reload();
				}
			});
		});
	</script>
@endpush