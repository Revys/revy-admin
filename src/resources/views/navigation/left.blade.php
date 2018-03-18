<aside id="sidebar">
	@if(count($items))
		<div class="sidebar">
			@foreach($items as $item)
				<a 
					href="{{ route('admin::path', [$item->controller, $item->action]) }}" 
					class="
						sidebar__item
						{{ count($item['children']) ? ' sidebar__item--parent' : '' }}
						{{ ($item->controller == $controller_name) ? ' sidebar__item--active' : '' }}
						" 
					title="{{ $item->title }}"
				>
					{{--  <i class="icon icon--{{ $item->icon ? $item->icon : 'point' }}"></i>  --}}
					<i class="icon fa {{ $item->icon ? $item->icon : 'fa-circle' }} fa-lg"></i>
				</a>

				<div class="sidebar__children">
					@foreach($item['children'] as $child)
						<a href="{{ route('admin::path', [$child->controller, $child->action]) }}"  class="sidebar__children__item{{ ($child->controller == $controller_name) ? ' sidebar__item--active' : '' }}" title="{{ $child->title }}">
							{{-- <i class="icon icon--{{ $child->icon ? $child->icon : 'point' }}"></i> --}}
							{{ $child->title }}
						</a>
					@endforeach
				</div>
			@endforeach
		</div>
	@endif
</aside>