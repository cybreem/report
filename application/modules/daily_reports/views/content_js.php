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

	$('.timepicker').timepicker({
			minuteStep: 10,
			showInputs: false,
			disableFocus: true,
			defaultTime: false,
			showMeridian: false
		});
	
    $(document).ready(function () {
        $('.dataTable').dataTable({
			'bPaginate': false,
			'bFilter': false,
	        'bSort': false,
	        'bInfo': false,
            'bServerSide': true,
            'bProcessing': true,
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
			"fnCreatedRow": function( nRow, aoData, iDataIndex ) {
				
				$(nRow).attr('data-id', aoData[6]);
				
			},
            'fnDrawCallback': function ( oSettings) {
				
	$('.timepicker').timepicker({
			minuteStep: 10,
			showInputs: false,
			disableFocus: true,
			defaultTime: false,
			showMeridian: false
		});
	
                $('[title]').tooltip();
					
            },
			"aoColumns": [
				{ "sClass": "id_classification" },
				{ "sClass": "id_job" },
				{ "sClass": "id_category" },
				{ "sClass": "description" },
				null,
				null
			],
			"aoColumnDefs": [
				{ "sWidth": "20%", "aTargets": [5] }
			]		
        });
        //Search input style
        $('.dataTables_filter input').attr('placeholder','Search');
		
		$(document).on('change','.form-control.classification',function(){
			set_job_code($(this),$(this).val());
		});
		
		$(document).on('dblclick','.dataTable tr td',function(){
			clearSelection($(this));
			var _this = $(this);
			var _class = $(this).attr('class');
			var _id = $(this).parent().data('id');
			var dates = $(this).closest('table').data('date');
			var _replaced = _id.replace('row-','');
			$.ajax({
				method: 'POST',
				url: '<?php echo site_url('daily_reports/get_ajax_edit/'); ?>',
				data: {id:_replaced, class_row: _class ,date:dates},
				success:function(data){
					jsondata = $.parseJSON(data);
					_this.html(jsondata.input);
					$(_this).children().addClass('editable');
				}
			});
		});
		
		
		$(document).on('blur','.editable',function(e){
			e.stopPropagation();	
			var _this = $(this);
			var _class = $(this).attr('class');
			var _id = $(this).closest('tr').data('id');
			var _replaced = _id.replace('row-','');
			var parent = $(this).parent();
			var head_parent = parent.parent();
			var field=parent.attr('class');
			$.ajax({
				url: '<?php echo site_url('daily_reports/post_inline/'); ?>',
				method: 'POST',
				data: {id: _replaced,value: $(this).val(),field: field,_class:$('.classification').val()},
				success: function(data){
					jsondata = $.parseJSON(data);
					if(jsondata.status==1){location.reload();}
					else if(jsondata.status==2){
						// $('.id_job',parent).trigger('dblclick');
						$('.id_job',head_parent).trigger('dblclick');
						set_job_code($('.id_job select',head_parent),_replaced);
					}
					gritter_alert(jsondata.alert);
				}
			});
		});
    });
    
    //date range picker
    $(document).ready(function() {
        $('#periode').daterangepicker(null, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
        
        $(".members-select").chosen();
    });
    function set_job_code(that,id){
		$.ajax({
			method: 'POST',
			url: '<?php echo site_url('daily_reports/set_job_code/'); ?>',
			data: {job_id:id},
			dataType: 'json',
			success:function(data){
				var parent = that.closest('tr');
				var job_code = $('.job_code',parent);
				job_code.empty();
				var a = '';
				$.each(data,function(index,value){
					a += '<option value="'+index+'">'+value+'</option>';
				});
				job_code.append(a);
			}
		});
	}
	//call modal form
	function call_modal(id) {
		$.ajax({
			type: 'POST',
			url: '<?php echo site_url('daily_reports/call_form'); ?>/'+id,
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
            url: '<?php echo site_url('daily_reports/call_form_upload'); ?>',
            success: function(data) {
                $('#myModal-sm').html(data).modal('show');
            }
        });
        return false;
    }
	function call_report_post(that){
		var parent = that.closest('.tab-pane');
		var dt = $('.dataTable',parent);
		$( document ).ajaxStart(function() {
		  $( "#save_button" ).hide();
		});
		$( document ).ajaxComplete(function() {
		  $( "#save_button" ).show();
		});
		$.ajax({
			url:'<?php echo site_url('daily_reports/save_report'); ?>', 
        	type: 'POST',
            datatype:'json',
            data: new FormData($('.report-post',parent)[0]),
			processData: false,
			contentType: false,
            success: function(data) {
                jsondata = $.parseJSON(data);
                if(jsondata.status === 1) {
                	gritter_alert(jsondata.alert);
					refresh_table(dt);
					
                } else {
                    gritter_alert(jsondata.alert);
					
                }
				
			}
        });
	}
	function refresh_table(that){
		$.getJSON('<?php  if(isset($source)) echo $source; ?>', {'date':that.data('date')}, function( json )
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
	function addrow(that){
		var parent = that.closest('.tab-pane');
		var total_row = $('.dataTable tbody tr',parent).length;
		var class_row = (total_row%2==0) ? 'odd' : 'even';
		$.ajax({
            type: 'POST',
			data:{row:class_row,job_id:1},
            url: '<?php echo site_url('daily_reports/get_new_report/'); ?>',
            success: function(data) {
                $('.dataTable tbody',parent).append(data);
				$('.timepicker').timepicker({
						minuteStep: 10,
						showInputs: false,
						disableFocus: true,
						defaultTime: false,
						showMeridian: false
					});
	
            }
        });
		// alert('a');
	}
	$(document).keydown(function(objEvent) {
    if (objEvent.keyCode == 9) {  //tab pressed
        objEvent.preventDefault(); // stops its action
    }
})
	
</script>