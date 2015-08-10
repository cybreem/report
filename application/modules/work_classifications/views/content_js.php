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
			'columnDefs' : [{
				'bSortable': false,
				'className' : 'action',
				'targets' : [-1] 
			}],
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
				for (var i=0, iLen=oSettings.aiDisplay.length; i<iLen; i++)
                {
                    var id  = $('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).find('.block').data('id');
					var status = $('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).find('.block').data('status');
					if(status == '2')
                    {
						$('#block'+id).attr('onClick','unblock_data('+id+')');
						$('#block'+id).attr('title','Unblock');
						$('#block'+id).attr('data-original-title','Unblock');
						$('#block'+id).find('i').attr('class','fa fa-plus-circle');
                    }
                    else
                    {
					}	
				}
			},			
        });
        //Search input style
        $('.dataTables_filter input').attr('placeholder','Search');
	});
	
	//block data
    function block_data(id) {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('work_classifications/block_data'); ?>/'+id,
            success: function(data) {
                window.location = '<?php echo site_url('work_classifications'); ?>';
            }
        });
        return false;
    }
	
	//unblock data
    function unblock_data(id) {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('work_classifications/unblock_data'); ?>/'+id,
            success: function(data) {
                window.location = '<?php echo site_url('work_classifications'); ?>';
            }
        });
        return false;
    }
	//call modal form
	function call_modal(id) {
		$.ajax({
			type: 'POST',
			url: '<?php echo site_url('work_classifications/call_form'); ?>/'+id,
			success: function(data) {
				$('#myModal').html(data).modal('show');
			}
		});
		return false;
	}
</script>