<script src="<?php echo config_item('assets'); ?>js/jquery.simple-color.js"></script>
<script>
    $(document).ready(function() {
        //color picker
        $('.colPick').simpleColor();
    });

    $('#myModalform').submit(function() {
        $.ajax({
        	type: 'POST',
            url: "<?php echo site_url('divisions/submit_data'); ?>",
            datatype:'json',
            data: $('#myModalform').serialize(),
            success: function(data) {
                jsondata = $.parseJSON(data);
                if(jsondata.status == 0) {
                	gritter_alert(jsondata.alert);
                } else {
                    window.location = "<?php echo site_url('divisions');?>";
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
				<h4 class="modal-title"><i class="fa fa-edit"></i> Divisions Form</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				     <input type="hidden" name="id" value="<?php echo $id; ?>" />
				    <div class="row">
				        <div class="col-lg-9">
				            <label>Divisions<span class="text-danger">*</span></label>                           
                            <input type="text" class="form-control" name="division" value="<?php echo set_value('division', $division); ?>" />
				        </div> 
				        <div class="col-lg-3">
				            <label>Label</label>
				            <input type="text" class='colPick' name="label" value="<?php echo set_value('label', $color_label); ?>" />
                        </div>        
				    </div>
				</div>
				<div class="form-group">
				    <label>Division<span class="text-danger">*</span></label>
				    <?php echo form_dropdown('leader', $opt_leader, set_value('leader', $leader), 'class="form-control"'); ?>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal">Cancel</button>
				<button class="btn btn-primary" type="submit">Save</button>
			</div>
		</form>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
