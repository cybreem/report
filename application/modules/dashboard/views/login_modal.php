<script>
	date = new Date();
	$('#tm').val(Date.now()/1000 | 0);
	function refresh_list(){
		$.each($('.get_list'),function(key,element){
			element = $(this);
			$.ajax({
				url: 'dashboard/'+$(this).data('url'),
				success: function(value){
					element.html(value);
				}
			});
		});
	}
	$('#login_record').submit(function() {
        $.ajax({
        	type: 'POST',
            url: "<?php echo site_url('auth/loginRecord'); ?>",
            datatype:'json',
            data: $(this).serialize(),
            success: function(data) {
				jsondata = $.parseJSON(data);
                if(jsondata.status === 1) {
					$('#myModal-sm').modal('hide');
                	gritter_alert(jsondata.alert);
					refresh_list();
                } else {
                    gritter_alert(jsondata.alert);
					
                }
            }
        });
        return false;
    });
</script>
<div class="modal-dialog modal-m">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">Attendance Form </h4>
		</div>
		<form id="login_record">
		<div class="modal-body">
			<input type="hidden" id="tm" name="tm" value="">
			
			<?php
			date_default_timezone_set("Asia/Jakarta");
			
			if(strtotime(date('H:i:s'))>strtotime('08:00:00')){
				
				?>
			<p class="alert alert-danger" role="alert">You are late <?php echo $this->session->userdata('name');?>, please fill the form below</p>
			<div class="form-group">
				<div class="row">
					<label class="control-label col-lg-1">Reason</label>
					<div class="col-lg-12">
						<textarea class="form-control" name="reason"></textarea>
					</div>
				</div>                    
			</div>
			<?php } else{
			?>
				<p>Good Morning <?php echo $this->session->userdata('name');?>, You arrive at <?php echo date('H:i:s') ?></p>
			<?php
			}?>
			
			<p class="alert alert-warning">Please click the Attend Button below to set your present time</p>
		</div>
		<div class="modal-footer">
			<button class="btn btn-success " type="submit">Attend</button>
		</div>
		</form>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

