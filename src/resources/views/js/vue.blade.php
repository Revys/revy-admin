@push('vue')
	<script>
		window.vm = new Vue({
			el: '#app',

			components: {
				// RevySelect
			},

			mounted() {
                $("#content").addClass('ready');
			}
		});
	</script>
@endpush