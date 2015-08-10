<script>
    $('#myModalform').submit(function() {
        $.ajax({
        	type: 'POST',
            url: '<?php echo site_url('privileges/submit_data'); ?>',
            datatype:'json',
            data: $(this).serialize(),
            success: function(data) {
            	jsonData = $.parseJSON(data);
                if(jsonData.status == 0) {
                	gritter_alert(jsonData.alert);
                } else {
                    window.location = "<?php echo site_url('privileges');?>";
                }
            }
        });
        return false;
    });
</script>
<div class="modal-dialog">
	<div class="modal-content">
		<form id="myModalform">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title"><i class="fa fa-cog"></i> Group Form</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				    <label>Group Name<span class="text-danger">*</span></label>
				    <input type="hidden" name="id" value="<?php echo $id; ?>" />
				    <input type="text" class="form-control" name="level" value="<?php echo set_value('level', $level); ?>" />
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal">Cancel</button>
				<button class="btn btn-primary" type="submit">Save</button>
			</div>
		</form>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->