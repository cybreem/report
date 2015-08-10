<script src="<?php echo config_item('assets'); ?>js/bootstrap-timepicker.js"></script>
<script src="<?php echo config_item('assets'); ?>js/bootstrap-datepicker.js"></script>
<script>
	$('.timepicker').timepicker({
        minuteStep: 10,
        showInputs: false,
        disableFocus: true,
        defaultTime: '08:00',
        showMeridian: false
    }); 
	if($('.status').val()==5){
			//$('.time').prop('disabled', true);
			alert('5');
		}else{
			//$('.time').prop('disabled', false);
		}
		var dp = $('.datepicker');
		var nowDate = new Date();
		var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
		dp.datepicker({ daysOfWeekDisabled: [0,6], endDate: today, startDate:'-1y' });
		
	function setChild(element){
		if(element.val()==5){
			//$('.time').prop('disabled', true);
			$('[name=start_time]').attr('disabled',true).attr('readonly',true);
			$('[name=end_time]').attr('disabled',true).attr('readonly',true);
			dp.datepicker('setStartDate','-1y');
			dp.datepicker('setEndDate',today);
			$('[name=end_date]').datepicker('setStartDate', '-1y');
			$('[name=end_date]').datepicker('setEndDate', today);
		}else if(element.val()==6){
			//$('.time').prop('disabled', false);
			$('[name=start_time]').attr('disabled',true).attr('readonly',true);
			$('[name=end_time]').attr('disabled',true).attr('readonly',true);
			dp.datepicker('setStartDate',today);
			dp.datepicker('setEndDate','+1y');
			$('[name=end_date]').datepicker('setStartDate',today);
			$('[name=end_date]').datepicker('setEndDate','+1y');
		}else if(element.val()==7){
			//$('.time').prop('disabled', false);
			$('[name=start_time]').attr('disabled',false).attr('readonly',false);
			$('[name=end_time]').attr('disabled',false).attr('readonly',false);
			dp.datepicker('setStartDate',today);
			dp.datepicker('setEndDate','+1y');
			
			$('[name=end_date]').datepicker('setStartDate', today);
			$('[name=end_date]').datepicker('setEndDate', '+1y');
		}else if(element.val()==8){
			//$('.time').prop('disabled', false);
			$('[name=start_time]').attr('disabled',true).attr('readonly',true);
			$('[name=end_time]').attr('disabled',true).attr('readonly',true);
			dp.datepicker('setStartDate','-1y');
			dp.datepicker('setEndDate',today);
			$('[name=end_date]').datepicker('setStartDate', '-1y');
			$('[name=end_date]').datepicker('setEndDate', today);
		}
	}	
	$('.status').on('change', function(e){
		
		setChild($(this));
	});
	
	if($('#request_status').val()==2){
				$('[name=start_time]').val('08:00').attr('disabled',true).attr('readonly',true);
				$('[name=end_time]').val('17:00').attr('disabled',true).attr('readonly',true);
				$('[name=start_date]').attr('disabled',false).attr('readonly',false);
				$('[name=end_date]').attr('disabled',false).attr('readonly',false);
			}else if($('#request_status').val()==1){
				
				$('[name=start_time]').val('17:00').attr('disabled',false).attr('readonly',false);
				$('[name=end_time]').attr('disabled',false).attr('readonly',false);
				$('[name=start_date]').attr('disabled',false).attr('readonly',false);
				$('[name=end_date]').attr('disabled',false).attr('readonly',false);
			}else{
				
				$('[name=start_time]').val('17:00').attr('disabled',false).attr('readonly',true);
				$('[name=end_time]').attr('disabled',false).attr('readonly',false);
				$('[name=start_date]').attr('disabled',true).attr('readonly',true);
				$('[name=end_date]').attr('disabled',true).attr('readonly',true);
			}
	
    $('#myModalform').submit(function(e) {
		e.preventDefault();
        $.ajax({
        	type: 'POST',
            url: "<?php echo site_url('user_request/createRequest'); ?>",
            datatype:'json',
            data: $(this).serialize(),
            success: function(data) {
				jsondata = $.parseJSON(data);
                if(jsondata.status === 1) {
                	gritter_alert(jsondata.alert);
					refresh_table($('#self_req'),$('#self_req').data('url'));
					$('#myModal-sm').modal('hide');
                } else {
                    gritter_alert(jsondata.alert);
					
                }
            }
        });
        return false;
    });
	
	$(function(){
		
			//$('.time').prop('disabled', false);
			$('[name=start_time]').attr('disabled',false).attr('readonly',false);
			$('[name=end_time]').attr('disabled',false).attr('readonly',false);
			dp.datepicker('setStartDate',today);
			dp.datepicker('setEndDate','+1y');
			$('[name=end_date]').datepicker('setStartDate', today);
			$('[name=end_date]').datepicker('setEndDate', '+1y');
		
		$('#request_status').on('change', function(){
			$.ajax({
				type: 'POST',
				url: "<?php echo site_url('user_request/requestChange'); ?>/"+$(this).val(),
				datatype:'json',
				data: $(this).serialize(),
				success: function(data) {
					jsondata = $.parseJSON(data);
					if(jsondata.length == 0){
						$('#request_status_child').attr('disabled',true).attr('readonly',true);
					}else{
						$('#request_status_child').html('');
						$('#request_status_child').attr('disabled',false).attr('readonly',false);
						$.each(jsondata,function(key,value){
							$('#request_status_child').append('<option value="'+value.id_status+'">'+value.status+'</option>');
						});
					}
					
				}
			});
			
			if($(this).val()==2){
				$('[name=start_time]').val('08:00').attr('disabled',true).attr('readonly',true);
				$('[name=end_time]').val('17:00').attr('disabled',true).attr('readonly',true);
				$('[name=start_date]').attr('disabled',false).attr('readonly',false);
				$('[name=end_date]').attr('disabled',false).attr('readonly',false);
				$('[name=start_time]').attr('disabled',true).attr('readonly',true);
				$('[name=end_time]').attr('disabled',true).attr('readonly',true);
				dp.datepicker('setStartDate','-1y');
				dp.datepicker('setEndDate',today);
				$('[name=end_date]').datepicker('setStartDate', '-1y');
				$('[name=end_date]').datepicker('setEndDate', today);
			}else if($(this).val()==1){
				
				$('[name=start_time]').val('17:00').attr('disabled',false).attr('readonly',false);
				$('[name=end_time]').attr('disabled',false).attr('readonly',false);
				$('[name=start_date]').attr('disabled',false).attr('readonly',false);
				$('[name=end_date]').attr('disabled',false).attr('readonly',false);
			}else{
				
				$('[name=start_time]').val('17:00').attr('disabled',false).attr('readonly',true);
				$('[name=end_time]').attr('disabled',false).attr('readonly',false);
				$('[name=start_date]').attr('disabled',true).attr('readonly',true);
				$('[name=end_date]').attr('disabled',true).attr('readonly',true);
			}
			
		})
		
		
	});
</script>

<div class="modal-dialog modal-md">
	<div class="modal-content">
		<form id="myModalform">
		    <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title"><i class="fa fa-edit"></i> Member Request Form</h4>
			</div>
			<div class="modal-body">
			    <input type="hidden" name="id" value="<?php echo $this->session->userdata('id'); ?>" />
				<div class="form-group">
				    <div class="row">
				        <label class="control-label col-lg-3">Name</label>
                        <div class="col-lg-9">
                            <?php echo $this->session->userdata('name'); ?>
                        </div>
				    </div>                    
                </div>
				<div class="form-group">
				    <div class="row">
                        <label class="control-label col-lg-3">Unit</label>
                        <div class="col-lg-9">
                           <?php echo ($this->session->userdata('division')) ? $this->session->userdata('division') : '-'?>
                        </div>
                    </div>  
                </div> 
				<div class="form-group">
				    <div class="row">
                        <label class="control-label col-lg-3">Request</label>
                        <div class="col-lg-3">
                            <div class="form-group">
							   <?php
									echo form_dropdown('status', $request_status_parent,NULL, 'class="form-control status" id="request_status"');
								?>
							</div>
                        </div>
						<label class="control-label col-lg-3">Categories</label>
						<div class="col-lg-3">
                            <div class="form-group">
							   <?php
									echo form_dropdown('status', $request_status_child,NULL, 'class="form-control status" id="request_status_child"');
								?>
							</div>
                        </div>
                    </div>  
                </div> 
				<div class="form-group">
				    <div class="row">
                        <label class="control-label col-lg-3">Start Date</label>
                        <div class="col-lg-3">
                             <div class="form-group">
							   <input type="text" class="datepicker date form-control" name="start_date" >
							</div>
                        </div>
						 <label class="control-label col-lg-3">Start Time</label>
						<div class="col-lg-3">
                             <div class="form-group">
							   <input type="text" class="timepicker time form-control" name="start_time">
							</div>
                        </div>
                    </div>  
                </div> 
				<div class="form-group">
				    <div class="row">
                        <label class="control-label col-lg-3">End Date</label>
                        <div class="col-lg-3">
                             <div class="form-group">
							   <input type="text" class="datepicker date form-control" name="end_date">
							</div>
                        </div>
						 <label class="control-label col-lg-3">End Time</label>
						<div class="col-lg-3">
                             <div class="form-group">
							   <input type="text" class="timepicker time form-control" name="end_time">
							</div>
                        </div>
                    </div>  
                </div> 
				<div class="form-group">
				    <div class="row">
                        <label class="control-label col-lg-3">Reason</label>
                        <div class="col-lg-9">
                             <div class="form-group">
							   <textarea name="reason" class="form-control"></textarea>
							</div>
                        </div>
						
                    </div>  
                </div> 
			</div>
			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button class="btn btn-primary" type="submit">Save</button>
			</div>
		</form>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
