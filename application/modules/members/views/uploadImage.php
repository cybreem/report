<script src="<?php echo config_item('assets')?>js/ajaxfileupload.js"></script>
<script>
var a = "";
$(document).ready(function(){
	$("#file-3").fileinput({
		showUpload: false,
		showCaption: false,
		showRemove: false,
		browseClass: "btn btn-primary",
		fileType: "any",
        previewFileIcon: "<i class='fa fa-folder-open-o'></i>"
	});
	$("#file-3").on('change',function(){
		var file = this.files[0];
		var default_img = $(this).attr('href');
		var reader = new FileReader();
		reader.onload = imageIsLoaded;
		reader.readAsDataURL(this.files[0]);
		
	});
	function imageIsLoaded(e) {
		$('#changed_img').attr('src', e.target.result);
		a = e.target.result;
	}
	
});
    $('#myModalform').submit(function() {
        $.ajax({
			url:'<?php echo site_url('members/upload_avatar'); ?>', 
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
                    gritter_alert(jsondata.alert);
					$('#myModal-sm').modal('toggle').empty();
					$('.replaced-image.img-<?php echo $this->session->userdata('nip')?>').attr('src',a);
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
					
				</button>
				<h4 class="modal-title"><i class="fa fa-picture-o"></i> Avatar Upload</h4>
			</div>
			<div class="modal-body">
				<div class="image-wrapper">
					<img id="changed_img" src="<?php echo config_item('assets').'img/user_photo/'.$this->session->userdata('image'); ?>?cachebuster=<?php echo time();?>" />
				</div>
				<div class="form-group browse-file">

				   <input type="file" id="file-3" name="userfile" id="userfile" value="" data-show-preview="false"/>

				</div>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal">Cancel</button>
				<button class="btn btn-primary" type="submit">Upload</button>
			</div>
		</form>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->