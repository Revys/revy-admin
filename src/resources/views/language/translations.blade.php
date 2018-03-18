<section class="card-holder" id="translations">
	@foreach($translations as $group)
		@if (count($group['phrases']) > 0)
			<div class="card card--form translations">
				{!! Form::open([
					'route' => ['admin::path', $controller_name, 'save_translations'],
					'class' => 'form--ajax'
				]) !!}
					<div class="card__header">
						<h2>{{ $group['title'] }}</h2>
					</div>

					<input type="hidden" name="group" value={{ $group['name'] }} />
					<input type="hidden" name="language" value={{ $group['language'] }} />

					<table class="data-table data-table--fixed data-table--auto translations__phrases">
						<thead>
							<tr class="data-table__titles">
								<td class="data-table__titles__title">@lang('Фраза')</td>
								<td class="data-table__titles__title">@lang('Перевод')</td>
							</tr>
						</thead>
						<tbody>
							@foreach($group['phrases'] as $phrase => $translation)
								<tr class="data-table__values translations__phrase">
									<td class="data-table__values__value">
										{{ $phrase }}
									</td>
									<td class="data-table__values__value">
										{{ Form::text(
											'translations[' . $phrase . ']', 
											$translation, 
											[
												'class' => 'form__group__input form__group__input--h-small'
											]
										) }}
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					<div class="card-buttons">
						@include("admin::components.button.save")
					</div>
				{!! Form::close() !!}
			</div>
		@endif
	@endforeach
</div>