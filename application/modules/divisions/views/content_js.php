<script src="<?php echo config_item('assets'); ?>js/jquery.dataTables.js"></script>
<script src="<?php echo config_item('assets'); ?>js/dataTables.bootstrap.js"></script>
<script src="<?php echo config_item('assets'); ?>js/chart.js"></script>
<script>	
	$(document).ready(function () {
	    //datatables
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
          		$('[title]').tooltip();
				for (var i=0, iLen=oSettings.aiDisplay.length; i<iLen; i++)
                {
                    var id  = $('td:eq(3)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).find('.block').data('id');
					var status = $('td:eq(3)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).find('.block').data('status');
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
			"fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                var iTotalMember = 0;
                for ( var i=0 ; i<aaData.length ; i++ )
                {
                    iTotalMember += aaData[i][2]*1;
                }
                var iPageMember = 0;
                for ( var i=iStart ; i<iEnd ; i++ )
                {
                    iPageMember += aaData[aiDisplay[i]][2]*1;
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[2].innerHTML = 'Total Member ' + parseInt(iPageMember) + '('+ parseInt(iTotalMember) +'total)';
            }
        });
        //Search input style
        $('.dataTables_filter input').attr('placeholder','Search');        
	});
	
	//cart
	window.onload = function() {		
		$.ajax({
            type: "POST",
            url: "<?php  if(isset($chart)) echo $chart; ?>",
            dataType: 'json',
            success: function(data) {  
            	var ctx = document.getElementById("chart-area").getContext("2d");          	
				var pieData = data;
				window.myPie = new Chart(ctx).Pie(pieData, {
				    animationSteps : 100,
				    animationEasing : "none",
				    animateRotate : true				
				});		
            }
        });
	};
	
   	//call modal form
	function call_modal(id) {
		$.ajax({
			type: 'POST',
			url: '<?php echo site_url('divisions/call_form'); ?>/'+id,
			success: function(data) {
				$('#myModal').html(data).modal('show');
			}
		});
		return false;
	}
	//block data
	function block_data(id) {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('divisions/block_data'); ?>/'+id,
            success: function(data) {
                window.location = '<?php echo site_url('divisions'); ?>';
            }
        });
        return false;
    }
	
	//unblock data
    function unblock_data(id) {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('divisions/unblock_data'); ?>/'+id,
            success: function(data) {
                window.location = '<?php echo site_url('divisions'); ?>';
            }
        });
        return false;
    }
	
</script>