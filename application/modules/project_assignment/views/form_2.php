<script src="<?php echo config_item('assets'); ?>js/datepicker.js"></script>
<script>
    $('#myModalform').submit(function() {
        $.ajax({
        	type: 'POST',
            url: "<?php echo site_url('project_assignment/save_status'); ?>",
            datatype:'json',
            data: $(this).serialize(),
            success: function(data) {
                    window.location = "<?php echo site_url('project_assignment');?>";
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
                    <label>Start Date Actual<span class="text-danger">*</span></label>
                    <input type="text" class="form-control datepicker" name="start_date_actual" value="<?php echo set_value('start_date', $start_date_actual); ?>" />
                </div>
                <div class="form-group">
                    <label>End Date Actual<span class="text-danger">*</span></label>
                    <input type="text" class="form-control datepicker" name="end_date_actual" value="<?php echo set_value('end_date', $end_date_actual); ?>" />
                </div>
                <div class="form-group">
			        <input type="hidden" name="id" value="<?php echo $id; ?>" />
			        <input type="hidden" name="id_project" value="<?php echo $id_project; ?>" />
                    <label>Project Status<span class="text-danger">*</span></label>
                    <?php
					$dropdown = array('' => 'Select Status',
										1 => 'Complete',
											2 => 'On Going',
												3 => 'Pending',
													4 => 'Overdue',
														0 => 'Not yet Start');
					echo form_dropdown('status', $dropdown, $status, 'class="form-control"');
					?>
                </div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button class="btn btn-primary" type="submit">Save</button>
			</div>
		</form>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->