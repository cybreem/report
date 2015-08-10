<script>
    $('#myModalform').submit(function() {
        $.ajax({
        	type: 'POST',
            url: "<?php echo site_url('holiday/submit_data'); ?>",
            datatype:'json',
            data: $(this).serialize(),
            success: function(data) {
                jsondata = $.parseJSON(data);
                if(jsondata.status == 0) {
                	gritter_alert(jsondata.alert);
                } else {
                    //gritter_alert(jsondata.alert);
                    //$('#myModal').html(data).modal('toggle');
                    //table.ajax.reload();
                    window.location = "<?php echo site_url('holiday');?>";
                }
            }
        });
        return false;
    });
    $('#holiday_date input').datepicker({
	    format: "yyyy-mm-dd",
	    autoclose: true,
	    todayHighlight: true
	});
    
</script>
<div class="modal-dialog">
	<div class="modal-content">
		<form id="myModalform">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title"><i class="fa fa-edit"></i>Holiday Form</h4>
			</div>
			<div class="modal-body">
			    <div class="form-group">
			        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                </div>
				<div id="holiday_date" class="form-group">
                    <label>Choose date<span class="text-danger">*</span></label>
                    <input type="text" class="form-control input-prepend" name="date" value="<?php echo set_value('date', $date); ?>"/>
                </div>
                <div class="form-group">
                    <label>Holiday name<span class="text-danger">*</span></label>
                    <textarea type="text" class="form-control" name="holiday_desc"><?php echo set_value('holiday_desc', $holiday_desc); ?></textarea>
                </div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button class="btn btn-primary" type="submit">Save</button>
			</div>
		</form>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->