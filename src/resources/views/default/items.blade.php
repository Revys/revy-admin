@include("admin::cards.list", [
	'selectable' => true,
	'filters' => true,
	'order' => true,
	'oc' => 'items-' . rand(0, 1000)
])