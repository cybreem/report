<script src="<?php echo config_item('assets'); ?>js/jquery.dataTables.js"></script>
<script src="<?php echo config_item('assets'); ?>js/dataTables.bootstrap.js"></script>
<script>
	//datatables
	$(document).ready(function () {
		$('#dataTable').dataTable({
            'bProcessing': true,
            'bServerSide': true,
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
          		$('[title]').tooltip();
			},
			'iDisplayLength': 25,			
        });
        //Search input style
        $('.dataTables_filter input').attr('placeholder','Search');
	});
	
	function refresh_table_att() {
		$.getJSON('<?php  if(isset($source)) echo $source; ?>', {'date':$('#dataTable').val()}, function( json )
		{
			table = $('#dataTable').dataTable();
			oSettings = table.fnSettings();

			table.fnClearTable(this);

			for (var i=0; i<json.aaData.length; i++)
			{
			  table.oApi._fnAddData(oSettings, json.aaData[i]);
			}

			oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
			table.fnDraw();
		});
	}
	
	//call modal form
	function call_modal(id) {
		$.ajax({
			type: 'POST',
			url: '<?php echo site_url('members/call_form'); ?>/'+id,
			success: function(data) {
				$('#myModal').html(data).modal('show');
			}
		});
		return false;
	}
	
	//call modal form upload
	function call_modal_upload() {
		$.ajax({
			type: 'POST',
			url: '<?php echo site_url('members/call_form_upload'); ?>',
			success: function(data) {
				$('#myModal-sm').html(data).modal('show');
			}
		});
		return false;
	}
	
	$('#changePSWD').click(function() {
	    $.ajax({
            type: 'POST',
            url: '<?php echo site_url('members/call_form_03'); ?>',
            success: function(data) {
                $('#myModal-sm').html(data).modal('show');
            }
        });
        return false;
	});
	
	$('#btn-cancel').click(function() {		
	    $('.form-control, #btn-cancel').prop('disabled', true);
	    $('#btn-save').remove();
	    $('#btn-update').show();
	});
	
	$('#btn-update').click(function() {
        $('.form-control, #btn-cancel').prop('disabled', false);
        $(this).hide();        
        $('<button class="btn btn-primary" type="submit" id="btn-save">Save</button>').appendTo('#dd');
    });
</script>