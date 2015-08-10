
<script>
    $('#myModalform').submit(function() {
        $.ajax({
        	type: 'POST',
            url: "<?php echo site_url('job_codes/submit_data'); ?>",
            datatype:'json',
            data: $(this).serialize(),
            success: function(data) {
                    window.location = "<?php echo site_url('job_codes');?>";
            }
        });
        return false;
    });
	$('#myModal').on('shown.bs.modal', function(event) {
		$('.datepicker').datepicker({
				format:'yyyy-mm-dd',
				daysOfWeekDisabled: "0,6",
				autoclose: true
		})
	});
</script>
<div class="modal-dialog">
	<div class="modal-content">
		<form id="myModalform">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title"><i class="fa fa-edit"></i>Job Code Form</h4>
			</div>
			<div class="modal-body">
			    <div class="form-group">
			        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <label>Classification<span class="text-danger">*</span></label>
                    <?php echo form_dropdown('classification', $opt_classification, set_value('classification', $id_classification), 'class="form-control"'); ?>
                </div>
				<div class="form-group">
                    <label>Job Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="job_name" value="<?php echo set_value('job_name', $job_name); ?>" />
                </div>
                <div class="form-group">
                    <label>Job Code<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="job_code" value="<?php echo set_value('job_code', $job_code); ?>" />
                </div>
                <div class="form-group">
                    <label>Start Date<span class="text-danger">*</span></label>
                    <input type="text" class="form-control datepicker" name="start_date" value="<?php echo set_value('start_date', $start_date); ?>" />
                </div>
                <div class="form-group">
                    <label>End Date<span class="text-danger">*</span></label>
                    <input type="text" class="form-control datepicker" name="end_date" value="<?php echo set_value('end_date', $end_date); ?>" />
                </div>
                <div class="form-group">
                    <label>Men Hour<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="man_hour" value="<?php echo set_value('man_hour', $man_hour); ?>" />
                </div>
                <div class="form-group">
			        <input type="hidden" name="id_project" value="<?php echo $id_project; ?>" />
                    <label>Project Status<span class="text-danger">*</span></label>
                    <?php
					$dropdown = array('' => 'Select Status',
										1 => 'Complete',
											2 => 'On Going',
												3 => 'Pending',
													0 => 'Not yet Start');
					echo form_dropdown('status', $dropdown, $status, 'class="form-control"');
					?>
                </div>
                <div class="form-group">
                    <label>Project Management Code</label>
                    <input class="form-control" rows="2" name="project_mng_code" value="<?php echo $project_mng_code; ?>">
                </div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button class="btn btn-primary" type="submit">Save</button>
			</div>
		</form>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->