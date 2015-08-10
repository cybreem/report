<script src="<?php echo config_item('assets'); ?>js/jquery.dataTables.js"></script>
<script src="<?php echo config_item('assets'); ?>js/dataTables.bootstrap.js"></script>
<script>
    //datatables
    $(document).ready(function () {
		
        oTable = $('.dataTable').dataTable({
            'bProcessing': true,
            'bServerSide': true,
            'sAjaxSource': '<?php  if(isset($source)) echo $source; ?>',
			'fnServerParams': function( aodata ){
				aodata.push({'name':'date','value':$(this).data('date')});
			},
            'fnServerData': function (sSource, aoData, fnCallback) {
                $.ajax ({
                    type : 'POST',
                    url : sSource,
                    data : aoData,
                    dataType : 'json',
                    success : fnCallback
                });
            },
			'iDisplayLength': 25,   
			"aoColumnDefs": [{
				"bSortable": false,
				"aTargets": [-1],
				"className": "dt-body-center",
				"sClass": "text-center",
				"sWidth": '150px'
			}
			]      
        });
        //Search input style
        $('.dataTables_filter input').attr('placeholder','Search');
    });
    
	//call modal form
	function call_modal(id, date) {
		$.ajax({
			type: 'POST',
			url: '<?php echo site_url('weekly_reports/call_form'); ?>/'+id+'/'+date,
			success: function(data) {
				$('#myModal-lg').html(data).modal('show');
			}
		});
		return false;
	}
	
	//call modal form
	function call_atendance(id, date) {
		$.ajax({
			type: 'POST',
			url: '<?php echo site_url('weekly_reports/call_atendance'); ?>/'+id+'/'+date,
			success: function(data) {
				$('#myModal-lg').html(data).modal('show');
			}
		});
		return false;
	}
	
	//call modal form upload
    function call_modal_upload() {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('weekly_reports/call_form_upload'); ?>',
            success: function(data) {
                $('#myModal-sm').html(data).modal('show');
            }
        });
        return false;
    }
</script>
