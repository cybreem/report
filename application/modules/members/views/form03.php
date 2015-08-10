<script>
    $('#myModalform').submit(function() {
        $.ajax({
            type: 'POST',
            url: "<?php echo site_url('members/update_pass'); ?>",
            datatype:'json',
            data: $('#myModalform').serialize(),
            success: function(result) {
                jsondata = $.parseJSON(result);
                if(jsondata.status == 0) {
                    gritter_alert(jsondata.alert);
                } else {
                    gritter_alert(jsondata.alert);
					$('#myModal-sm').modal('hide');                
				}
            }
        });
        return false;
    });
</script>
<div class="modal-dialog modal-sm">
    <div class="modal-content">
        <form id="myModalform">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title"><i class="fa fa-lock"></i> Change Password</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>New Password<span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="new-pass" value="" />
                </div>
                <div class="form-group">
                    <label>Confirm Password<span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="cof-pass" value="" />
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->