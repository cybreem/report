<script src="<?php echo config_item('assets'); ?>js/moment.js"></script>
<script src="<?php echo config_item('assets'); ?>js/daterangepicker.js"></script>
<script src="<?php echo config_item('assets'); ?>js/chosen.jquery.min.js"></script>
<script src="<?php echo config_item('assets'); ?>js/jquery.dataTables.js"></script>
<script src="<?php echo config_item('assets'); ?>js/dataTables.bootstrap.js"></script>
<script src="<?php echo config_item('assets'); ?>js/bootstrap-timepicker.js"></script>
<script>
	function clearSelection(that) {
		if(that.selection && that.selection.empty) {
			that.selection.empty();
		} else if(window.getSelection) {
			var sel = window.getSelection();
			sel.removeAllRanges();
		}
	}
    //datatables
	function timepicker(){
		$('.timepicker').timepicker({
			minuteStep: 10,
			showInputs: false,
			disableFocus: true,
			defaultTime: false,
			showMeridian: false
		});
	}
	
    $(document).ready(function () {
		$('#periode').daterangepicker(null, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
        
        $(".members-select").chosen();
		// .each(function(index,element){
			var source = $('#member_req').data('url');
			$('#member_req').dataTable({
				'bPaginate': true,
				'bFilter': true,
				'bSort': false,
				'bInfo': true,
				'bServerSide': true,
				'bProcessing': true,
				'sAjaxSource': source,
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
					if ( oSettings.bSorted || oSettings.bFiltered || oSettings.bDrawing )
					{
						for ( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ )
						{
							$('td:eq(0)', oSettings.aoData[ oSettings.aiDisplay[i] ].nTr ).html( i+1 );
						}
					}
				},
				 "fnCreatedRow": function( nRow, aData, iDataIndex ) {
					if ( aData[6] == 0 )
					{
						$('td:eq(6)', nRow).html( '<p class=\"tooltips\" title=\"On Progress\">-</p>' );
					}else if(aData[6] == 1){
						$('td:eq(6)', nRow).html( '<p class=\"tooltips\" title=\"Accepted By '+aData[9]+'\">&#10004</p>' );
					}else{
						$('td:eq(6)', nRow).html( '<p class=\"tooltips\" title=\"Rejected By '+aData[9]+'\">&#9587</p>' );
					}
					
					if ( aData[7] == 0 )
					{
						$('td:eq(7)', nRow).html( '<p class=\"tooltips\" title=\"On Progress\">-</p>' );
					}else if(aData[7] == 1){
						$('td:eq(7)', nRow).html( '<p class=\"tooltips\" title=\"Accepted By '+aData[10]+'\">&#10004</p>' );
					}else{
						$('td:eq(7)', nRow).html( '<p class=\"tooltips\" title=\"Rejected By '+aData[10]+'\">&#9587</p>' );
					}
					
					$('td:eq(8)', nRow).html( '<p class="btn-group"><button class="btn btn-xs btn-info tooltips" title="Detail" onclick=statusMember('+aData[8]+',1)><i class="fa fa-edit"></i> Detail</button></p>' );
				},
				"aoColumnDefs": [
					{ "bSortable": false, "aTargets": [ 0 ] }
				],
				"aaSorting": [[ 1, 'asc' ]]
			});
			
			var source2 = $('#self_req').data('url');
			$('#self_req').dataTable({
				'bPaginate': true,
				'bFilter': true,
				'bSort': false,
				'bInfo': true,
				'bServerSide': true,
				'bProcessing': true,
				'sAjaxSource': source2,
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
					if ( oSettings.bSorted || oSettings.bFiltered || oSettings.bDrawing )
					{
						for ( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ )
						{
							$('td:eq(0)', oSettings.aoData[ oSettings.aiDisplay[i] ].nTr ).html( i+1 );
						}
					}
				},
				 "fnCreatedRow": function( nRow, aData, iDataIndex ) {
					if ( aData[4] == 0 )
					{
						$('td:eq(4)', nRow).html( '<p class=\"tooltips\" title=\"On Progress\">-</p>' );
					}else if(aData[4] == 1){
						$('td:eq(4)', nRow).html( '<p class=\"tooltips\" title=\"Accepted By '+aData[9]+'\">&#10004</p>' );
					}else{
						$('td:eq(4)', nRow).html( '<p class=\"tooltips\" title=\"Rejected By '+aData[9]+'\">&#9587</p>' );
					}
					
					if ( aData[5] == 0 )
					{
						$('td:eq(5)', nRow).html( '<p class=\"tooltips\" title=\"On Progress\">-</p>' );
					}else if(aData[5] == 1){
						$('td:eq(5)', nRow).html( '<p class=\"tooltips\" title=\"Accepted By '+aData[10]+'\">&#10004</p>' );
					}else{
						$('td:eq(5)', nRow).html( '<p class=\"tooltips\" title=\"Rejected By '+aData[10]+'\">&#9587</p>' );
					}
					
					$('td:eq(6)', nRow).html( '<p class="btn-group"><button class="btn btn-xs btn-info tooltips" title="Detail" onclick=statusMember('+aData[6]+',2)><i class="fa fa-edit"></i> Detail</button></p>' );
				},
				"aoColumnDefs": [
					{ "bSortable": false, "aTargets": [ 0 ] }
				],
				"aaSorting": [[ 1, 'asc' ]]
			});
		// });
        
        //Search input style
       
		
    });
    
	
	//call modal form upload
    function makeRequest() {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('user_request/makeRequest'); ?>',
            success: function(data) {
                $('#myModal-sm').html(data).modal('show');
            }
        });
        return false;
    }
	function callDetail(id){
		 $.ajax({
            type: 'POST',
            url: '<?php echo site_url('user_request/getDetail'); ?>/'+id,
            success: function(data) {
                $('#myModal-sm').html(data).modal('show');
            }
        });
        return false;
	}
	function statusMember(id,rq){
		 $.ajax({
            type: 'POST',
            url: '<?php echo site_url('user_request/statusMember'); ?>/'+id+'/'+rq,
            success: function(data) {
                $('#myModal-sm').html(data).modal('show');
            }
        });
        return false;
	}
	function refresh_table(that,url){
		$.getJSON(url, function( json )
		{
			table = that.dataTable();
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
	
	
	
</script>