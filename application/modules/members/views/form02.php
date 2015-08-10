<script src="<?php echo config_item('assets')?>js/ajaxfileupload.js"></script>
<script>

   $('#myModalform').submit(function() {
        $.ajax({
			url:'<?php echo site_url('members/upload_file'); ?>', 
        	type: 'POST',
            datatype:'json',
            mimetype: "multipart/form-data",
            data: new FormData($(this)[0]),
			processData: false,
			contentType: false,
            success: function(data) {
                jsondata = $.parseJSON(data);
                if(jsondata.status == 0) {
                	gritter_alert(jsondata.alert);
                } else {
					$('#myModal-sm').modal('hide');
                    gritter_alert(jsondata.alert);
					$('#myModal').modal('hide');
                }
			}
        });
        return false;
    });
    
</script>
<div class="modal-dialog modal-sm">
	<div class="modal-content">
		<form id="myModalform" enctype="multipart/form-data">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title"><i class="fa fa-file-excel-o"></i> Member List Upload</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
                   <p class="alert alert-warning"><i class="fa fa-info-circle"></i> Use right format to upload weekly plan, here's the <a href="<?php echo base_url('temp_upload/member/member_list.xlsx'); ?>">example</a> plan template.</p>
                </div>
				<div class="form-group">
					
				   <input type="file" name="userfile" id="userfile" value="" />

				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn btn-default" data-dismiss="modal">Cancel</button>
				<button class="btn btn-primary" type="submit">Upload</button>
			</div>
		</form>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->