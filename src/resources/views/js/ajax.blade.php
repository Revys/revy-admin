<script>
	// Request ajax form
	$(".form--ajax").bind("submit", function(e){
		e.preventDefault();

		$.request({
			url: $(this).attr("action"),
			data: $(this).serialize()
		});
	});
</script>