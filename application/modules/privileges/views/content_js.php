<script src="<?php echo config_item('assets'); ?>js/jquery.dataTables.js"></script>
<script src="<?php echo config_item('assets'); ?>js/dataTables.bootstrap.js"></script>
<script>
	//datatables
	$(document).ready(function () {
		$('#dataTable').dataTable({
            'bProcessing': true,
            'bServerSide': true,
            'bPaginate': false,
            'bLengthChange': false,
            'bFilter': false,
            'bSort': false,
            'bInfo': false,
           	'sAjaxSource': '<?php  if(isset($source)) echo $source; ?>',
           	'fnServerData': function (sSource, aoData, fnCallback) {
                $.ajax ({
                    type : 'POST',
                	url : sSource,
                	data : aoData,
                    dataType : 'json',
                    success : fnCallback
                });
         	},
         	'fnDrawCallback': function ( oSettings) {
         	    //tooltips
          		$('[title]').tooltip();
			},			
        });
	});

	//call modal form
	function call_modal(id) {
		$.ajax({
			type: 'POST',
			url: '<?php echo site_url('privileges/call_form'); ?>/'+id,
			success: function(data) {
				$('#myModal').html(data).modal('show');
			}
		});
		return false;
	}
</script>