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
	if($('.status').val()==1){
			$('.time').prop('disabled', true);
		}else{
			$('.time').prop('disabled', false);
		}
	$('.datepicker').datepicker();
	$('.status').on('change', function(e){
		if($(this).val()==1){
			$('.time').prop('disabled', true);
		}else{
			$('.time').prop('disabled', false);
		}
	});
	$('#request_status_child').html('');
	if(typeof $('#request_status').val() != 'undefined'){
		$.ajax({
			type: 'POST',
			url: "<?php echo site_url('user_request/requestChange'); ?>/"+$('#request_status').val(),
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
						var sel = (value.id_status == '<?php echo $data[0]['status_child']?>') ? 'selected' : '';
						$('#request_status_child').append('<option value="'+value.id_status+'" '+sel+'>'+value.status+'</option>');
					});
				}
			}
		});
	}
    $('#myModalform').submit(function(e) {
		e.preventDefault();
        $.ajax({
        	type: 'POST',
            url: "<?php echo site_url('user_request/updateRequest'); ?>/<?php echo $data[0]['urid'];?>",
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
				$('[name=start_time]').attr('disabled',true).attr('readonly',true);
				$('[name=end_time]').attr('disabled',true).attr('readonly',true);
				$('[name=start_date]').attr('disabled',false).attr('readonly',false);
				$('[name=end_date]').attr('disabled',false).attr('readonly',false);
			}else{
				$('[name=start_time]').attr('disabled',false).attr('readonly',false);
				$('[name=end_time]').attr('disabled',false).attr('readonly',false);
				$('[name=start_date]').attr('disabled',true).attr('readonly',true);
				$('[name=end_date]').attr('disabled',true).attr('readonly',true);
			}
		})
		
		
	});
</script>
<?php $check = ($data[0]['leader_status']!=0||$data[0]['manager_status']!=0||$data[0]['ids']==4);

?>
<div class="modal-dialog modal-md">
	<div class="modal-content">
		<form id="myModalform">
		    <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title"><i class="fa fa-edit"></i> Edit Member Request Form</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				    <div class="row">
				        <label class="control-label col-lg-3">Name</label>
                        <div class="col-lg-9">
                            <?php echo $data[0]['name'];?>
                        </div>
				    </div>                    
                </div>
				<div class="form-group">
				    <div class="row">
                        <label class="control-label col-lg-3">Unit</label>
                        <div class="col-lg-9">
                           <?php echo $data[0]['division'];?>
                        </div>
                    </div>  
                </div> 
				<div class="form-group">
				    <div class="row">
                        <label class="control-label col-lg-3">Request</label>
                        <div class="col-lg-3">
                             <div class="form-group">
							   <?php
									
										echo $data[0]['status_parent_name'];
									
								?>
							</div>
                        </div>
						<label class="control-label col-lg-3">Categories</label>
						<div class="col-lg-3">
                             <div class="form-group">
							   <?php
									
										echo $data[0]['status_child_name'];
									
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
							 <?php if($check){
							  echo date('m-d-Y',strtotime($data[0]['start_time']));
							 }else{
								 ?>
								  <input type="text" class="datepicker date form-control" name="start_date" value="<?php echo date('m/d/Y',strtotime($data[0]['start_time']));?>">
								  <?php
							 }?>
							</div>
                        </div>
						 <label class="control-label col-lg-3">Start Time</label>
						<div class="col-lg-3">
                             <div class="form-group">
							  <?php if($check){ 
									echo date('H:i',strtotime($data[0]['start_time']));
							  }else{
								 ?>
							   <input type="text" class="timepicker time form-control" name="start_time" <?php 
									$time = date('H:i:s',strtotime($data[0]['start_time']));
									if($time == '00:00:00'){
										echo 'disabled';
									}
									else{
										echo 'value = "'.date('h:i A',strtotime($data[0]['start_time'])).'"';
									}
							   ?>>
							  <?php 
							  }?>
							</div>
                        </div>
                    </div>  
                </div> 
				<div class="form-group">
				    <div class="row">
                        <label class="control-label col-lg-3">End Date</label>
                        <div class="col-lg-3">
                             <div class="form-group">
							   <?php if($check){
								   echo date('m-d-Y',strtotime($data[0]['end_time']));
							   }else{
									 ?>
							   <input type="text" class="datepicker date form-control" name="end_date" value="<?php echo date('m/d/Y',strtotime($data[0]['end_time']));?>">
							   <?php 
							   } ?>
							</div>
                        </div>
						 <label class="control-label col-lg-3">End Time</label>
						<div class="col-lg-3">
                             <div class="form-group">
							 <?php if($check){
								 echo date('H:i',strtotime($data[0]['end_time']));
								 } else{
								  ?>
							   <input type="text" class="timepicker time form-control" name="end_time" <?php 
									$time = date('H:i:s',strtotime($data[0]['end_time']));
									if($time == '00:00:00'){
										echo 'disabled';
									}
									else{
										echo 'value = "'.date('h:i A',strtotime($data[0]['end_time'])).'"';
									}
							   ?>>
							 <?php 
							 }?>
							</div>
                        </div>
                    </div>  
                </div> 
				<div class="form-group">
				    <div class="row">
                        <label class="control-label col-lg-3">Reason</label>
                        <div class="col-lg-9">
                             <div class="form-group">
							  <?php if($check){
								  echo $data[0]['reason'];
							  }else{
								   ?>
							   <textarea name="reason" class="form-control"><?php echo $data[0]['reason'];?></textarea>
							  <?php 
								  
							  } ?>
							</div>
                        </div>
						
                    </div>  
                </div> 
				<div class="form-group">
				    <div class="row">
                        <label class="control-label col-lg-3">Manager Note</label>
                        <div class="col-lg-9">
                             <div class="form-group">
							  <?php echo ($data[0]['manager_note']) ? $data[0]['manager_note'] : '-';?>
							</div>
                        </div>
						
                    </div>  
                </div> 
				<div class="form-group">
				    <div class="row">
                        <label class="control-label col-lg-3">Leader Note</label>
                        <div class="col-lg-9">
                             <div class="form-group">
							 <?php if($this->session->userdata('level')=='Leader'&&$request==1){?>
								 <textarea name="leader_note" class="form-control"></textarea>
							 <?php }else{?>
							  <?php 
							  echo ($data[0]['leader_note']) ? $data[0]['leader_note'] : '-';
							  
							 }?>
							</div>
                        </div>
						
                    </div>  
                </div> 
			</div>
			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal">Cancel</button>
				<?php if(!($data[0]['leader_status']!=0||$data[0]['manager_status']!=0||$data[0]['ids']==4)){?>
				<button class="btn btn-primary" type="submit">Save</button>
				<?php } ?>
			</div>
		</form>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->