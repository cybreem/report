<script>
    $('#myModalform').submit(function() {
        $.ajax({
        	type: 'POST',
            url: "<?php echo site_url('work_categories/submit_data'); ?>",
            datatype:'json',
            data: $(this).serialize(),
            success: function(data) {
                jsondata = $.parseJSON(data);
                if(jsondata.status == 0) {
                	gritter_alert(jsondata.alert);
                } else {
                    window.location = "<?php echo site_url('work_categories');?>";
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
				<h4 class="modal-title"><i class="fa fa-edit"></i> Work Category Form</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				    <label>Category<span class="text-danger">*</span></label>
				    <input type="hidden" name="id" value="<?php echo $id; ?>" />
				    <input type="text" class="form-control" name="category" value="<?php echo set_value('category', $category); ?>" />
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button class="btn btn-primary" type="submit">Save</button>
			</div>
		</form>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
