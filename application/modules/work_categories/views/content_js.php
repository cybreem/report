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
         		for (var i=0, iLen=oSettings.aiDisplay.length; i<iLen; i++)
                {
                    //var level  = $('td:eq(2)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).text();
                    var flag  = $('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).text();
                    var id  = $('td:eq(2)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).find('.block').data('id');

                    if(flag == '2')
                    {
                        $('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html('<label class="label label-danger">Inactive</label>');
						$('#block'+id).attr('onClick','unblock_category('+id+')');
						$('#block'+id).attr('title','Unblock');
						$('#block'+id).find('i').attr('class','fa fa-plus-circle');
                    }
                    else
                    {
                        $('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html('<label class="label label-success">Active</label>');
						$('#block'+id).attr('onClick','block_category('+id+')');
                    }
                }
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
			url: '<?php echo site_url('work_categories/call_form'); ?>/'+id,
			success: function(data) {
				$('#myModal').html(data).modal('show');
			}
		});
		return false;
	}
	
	function delete_category(id) {
		$.ajax({
			type: 'POST',
			url: '<?php echo site_url('work_categories/delete_data'); ?>/'+id,
			success: function(data) {
				$('#myModal').html(data).modal('show');
			}
		});
		return false;
	}
	
	function block_category(id) {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('work_categories/block_category'); ?>/'+id,
            success: function(data) {
                window.location = '<?php echo site_url('work_categories'); ?>';
            }
        });
        return false;
    }
	
    function unblock_category(id) {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('work_categories/unblock_category'); ?>/'+id,
            success: function(data) {
                window.location = '<?php echo site_url('work_categories'); ?>';
            }
        });
        return false;
    }
	
</script>