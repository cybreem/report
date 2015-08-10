<script>
    $('#myModalform').submit(function() {
        $.ajax({
        	type: 'POST',
            url: '<?php echo site_url('members/submit_data'); ?>',
            datatype:'json',
            data: $(this).serialize(),
            success: function(data) {
            	jsonData = $.parseJSON(data);
                if(jsonData.status == 0) {
                	gritter_alert(jsonData.alert);
                } else {
                    window.location = "<?php echo site_url('members');?>";
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
				<h4 class="modal-title"><i class="fa fa-user"></i> Member Form</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				    <label>NIP<span class="text-danger">*</span></label>
				    <input type="hidden" name="id" value="<?php echo $id; ?>" />
				    <input type="text" class="form-control" name="nip" value="<?php echo set_value('nip', $nip); ?>" />
				</div>
				<div class="form-group">
				    <label>Name<span class="text-danger">*</span></label>
				    <input type="text" class="form-control" name="name" value="<?php echo set_value('name', $name); ?>" />
				</div>
				<div class="form-group">
				    <label>Email<span class="text-danger">*</span></label>
				    <input type="text" class="form-control" name="email" value="<?php echo set_value('email', $email); ?>" />
				</div>
				<div class="form-group">
				    <label>Division<span class="text-danger">*</span></label>
				    <?php echo form_dropdown('division', $opt_division, set_value('division', $id_division), 'class="form-control"'); ?>
				</div>
				<div class="form-group">
				    <label>Level<span class="text-danger">*</span></label>
				    <?php echo form_dropdown('level', $opt_level, set_value('level', $id_user_level), 'class="form-control"'); ?>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal">Cancel</button>
				<button class="btn btn-primary" type="submit">Save</button>
			</div>
		</form>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->