<script src="<?php echo config_item('assets'); ?>js/bootstrap-timepicker.js"></script>
<script src="<?php echo config_item('assets'); ?>js/bootstrap-datepicker.js"></script>
<script>
	
	$(document).ready(function() {
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
		
		$('#myModalform').submit(function(e) {
			e.preventDefault();
			var val = $("button[type=submit][clicked=true]").data('val');
			$("input[type=hidden][name=decision]").val(val);
			$.ajax({
				type: 'POST',
				url: "<?php echo site_url('user_request/updatePermission'); ?>/<?php echo $data[0]['urid'];?>",
				datatype:'json',
				data: $(this).serialize(),
				success: function(data) {
					jsondata = $.parseJSON(data);
					if(jsondata.status === 1) {
						gritter_alert(jsondata.alert);
						refresh_table($('#member_req'),$('#member_req').data('url'));
						$('#myModal-sm').modal('hide');
					} else {
						gritter_alert(jsondata.alert);
						
					}
				}
			});
			return false;
		});

		

		// DO WORK
		
	});

	$("form button[type=submit]").click(function() {
		$("button[type=submit]", $(this).parents("form")).removeAttr("clicked");
		$(this).attr("clicked", "true");
	});
</script>
<?php 

	$check = ($data[0]['leader_status']!=0&&$data[0]['manager_status']!=0&&$this->session->userdata('level')!='Member') ? TRUE : FALSE;
	$isMine = ($data[0]['id_user'] == $this->session->userdata('id')) ? TRUE : FALSE ;
	echo $data[0]['id_user'] . ' ' . $this->session->userdata('id') ;
?>
<div class="modal-dialog modal-md">
	<div class="modal-content">
		<form id="myModalform">
		    <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title"><i class="fa fa-edit"></i> Details Member Request Form</h4>
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
							 <?php if($request==1){
							  echo date('m-d-Y',strtotime($data[0]['start_time']));
							 }else{
								 echo $isMine;
								 ?>
								  <input type="text" class="datepicker date form-control" name="start_date" value="<?php echo date('m-d-Y',strtotime($data[0]['start_time']));?>">
								  <?php
							 }?>
							</div>
                        </div>
						 <label class="control-label col-lg-3">Start Time</label>
						<div class="col-lg-3">
                             <div class="form-group">
							  <?php if($request==1){ 
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
							   <?php if($request==1){
								   echo date('m-d-Y',strtotime($data[0]['end_time']));
							   }else{
									 ?>
							   <input type="text" class="datepicker date form-control" name="end_date" value="<?php echo date('m-d-Y',strtotime($data[0]['end_time']));?>">
							   <?php 
							   } ?>
							</div>
                        </div>
						 <label class="control-label col-lg-3">End Time</label>
						<div class="col-lg-3">
                             <div class="form-group">
							 <?php if($request==1){
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
							  <?php if($request==1){
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
							  <?php if($this->session->userdata('level')=='Admin'){?>
								 <textarea name="manager_note" class="form-control"><?php echo $data[0]['manager_note']?></textarea>
							 <?php }else{?>
							  <?php 
							  echo ($data[0]['manager_note']) ? $data[0]['manager_note'] : '-';
							  
							 }?>
							</div>
                        </div>
						
                    </div>  
                </div> 
				<div class="form-group">
				    <div class="row">
                        <label class="control-label col-lg-3">Leader Note</label>
                        <div class="col-lg-9">
                             <div class="form-group">
							 <?php if($this->session->userdata('level')=='Leader'){?>
								 <textarea name="leader_note" class="form-control"><?php echo $data[0]['leader_note']?></textarea>
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
				<input type="hidden" name="decision" value="">
				<input type="hidden" name="id_status" value="<?php echo $data[0]['id_status'];?>">
				<button class="btn btn-default" data-dismiss="modal" type="submit" >Close</button>
				<button class="btn btn-danger" type="submit" data-val='2' >Decline</button>
				<button class="btn btn-primary btn-success" type="submit" data-val='1' >Approve</button>
			</div>
		</form>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->