<script src="<?php echo config_item('assets')?>js/ajaxfileupload.js"></script>
<script>
    $(function() {
        $('#myModalform').submit(function(e) {
            e.preventDefault();
            $.ajaxFileUpload({
                url             :'<?php echo site_url('weekly_reports/upload_file'); ?>', 
                secureuri       : false,
                fileElementId   :'userfile',
                datatype        : 'json',
                success : function (data) {
                    if(data.status==1){
						gritter_alert(data.alert);
					}else{
						gritter_alert(data.alert);
					}
                }
            });
            return false;
        });
    });
</script>
<div class="modal-dialog modal-sm">
    <div class="modal-content">
        <form id="myModalform" enctype="multipart/form-data">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title"><i class="fa fa-file-excel-o"></i> Weekly Plan Upload</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                   <p class="alert alert-warning"><i class="fa fa-info-circle"></i> Use right format to upload weekly plan, here's the <a href="<?php echo base_url('weekly_reports/get_sample_data'); ?>">example</a> plan template.</p>
                </div>
                <div class="form-group">
                   <input type="file" name="userfile" id="userfile" value="" />
                </div>
            </div>            
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" type="submit">Upload</button>
            </div>
        </form>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->