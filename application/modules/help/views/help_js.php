<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="<?php echo config_item('assets'); ?>fancybox/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo config_item('assets'); ?>fancybox/jquery.fancybox.css?v=2.1.5" media="screen" />
<script>
	$('.collapse').on('shown.bs.collapse', function(){
		$(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
	}).on('hidden.bs.collapse', function(){
		$(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
	});
	
	$(document).ready(function() {
			

			// Change title type, overlay closing speed
			$(".fancybox").fancybox({
				helpers: {
					title : {
						type : 'outside'
					},
					overlay : {
						speedOut : 0
					}
				}
			});


		});
</script>