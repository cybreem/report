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
			'iDisplayLength': 25,
         	'fnDrawCallback': function ( oSettings) {
         	    //users status
         	    for (var i=0, iLen=oSettings.aiDisplay.length; i<iLen; i++)
                {
                    var level  = $('td:eq(2)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).text();
                    var status  = $('td:eq(3)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).text();
                    var id  = $('td:eq(4)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).find('.block').data('id');
                    
                    $('td:eq(2)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html('<label class="label label-danger">'+level+'</label>');
                    
                    if(status == '0')
                    {
                        $('td:eq(3)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html('<label class="label label-danger">Inactive</label>');
						$('#block'+id).attr('onClick','unblock_user('+id+')');
						$('#block'+id).attr('title','Unblock');
						$('#block'+id).find('i').attr('class','fa fa-plus-circle');
                    }
                    else
                    {
                        $('td:eq(3)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html('<label class="label label-success">Active</label>');
						$('#block'+id).attr('onClick','block_user('+id+')');
                    }
                }
                //tooltips
          		$('[title]').tooltip();
			},			
        });
        //Search input style
        $('.dataTables_filter input').attr('placeholder','Search');
	});

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
	
	//block user
    function block_user(id) {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('users/block_user'); ?>/'+id,
            success: function(data) {
                window.location = '<?php echo site_url('users'); ?>';
            }
        });
        return false;
    }
	
	//unblock user
    function unblock_user(id) {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('users/unblock_user'); ?>/'+id,
            success: function(data) {
                window.location = '<?php echo site_url('users'); ?>';
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
				$('#myModal').html(data).modal('show');
			}
		});
		return false;
	}
</script>