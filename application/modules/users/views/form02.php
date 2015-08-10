<script>
    $('#myModalform').submit(function() {
        $.ajax({
        	type: 'POST',
            url: "<?php echo site_url('members/submit_data'); ?>",
            datatype:'json',
            data: $('#myModalform').serialize(),
            success: function(result) {
                jsondata = $.parseJSON(result);
                if(jsondata.status == 0) {
                	gritter_alert(jsondata.alert);
                } else {
                	gritter_alert(jsonData.alert);
					$('#myModal').modal('hide');
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
				<h4 class="modal-title"><i class="fa fa-file-excel-o"></i> Member List Uploads</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				    <label>Member List File<span class="text-danger">*</span></label>
				    <input type="file" name="file_list" value="" />
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal">Cancel</button>
				<button class="btn btn-primary" type="submit">Upload</button>
			</div>
		</form>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->