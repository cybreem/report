<script src="<?php echo config_item('assets')?>js/ajaxfileupload.js"></script>
<script>
    $(function() {
        $('#myModalform').submit(function(e) {
            e.preventDefault();
            $.ajax({
				type: 'POST',
                url             :'<?php echo site_url('weekly_reports/update_attendance'); ?>', 
                datatype:'json',
				data: $(this).serialize(),
				success: function(data) {
					window.location = "<?php echo site_url('weekly_reports');?>";
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
                <h4 class="modal-title"><i class="fa fa-file-excel-o"></i>Set Member Attendance</h4>
            </div>
			<?php
			?>
            <div class="modal-body">
                <div class="form-group">
                   Attendance 
				   <input type="hidden" name='id_attend' value="<?php if(isset($set['id'])){ echo $set['id'];}else{ echo "";}?>" />
				   <input type="hidden" name='id_user' value="<?php if(isset($set['id_user'])){ echo $set['id_user'];}else{ echo $set[0]['id_user'];}?>" />
				   <input type="hidden" name='date' value="<?php if(isset($set['date'])){ echo $set['date'];}else{ echo $set[0]['date'];}?>" />
                </div>
                <div class="form-group">
                   <?php
					$options = array(
					  'Attend'  => 'Attend',
					  'Permit'    => 'Permit',
					  'Sick'   => 'Sick',
					  'Absent' => 'Absent',
					);
					if(isset($set['attend'])){
						echo form_dropdown('attend', $options, $set['attend'], 'class="form-control"');
					}else{
						echo form_dropdown('attend', $options, '', 'class="form-control"');
					}
					?>
                </div>
                <div class="form-group">
                   Notes
                </div>
                <div class="form-group">
                   <textarea name="description" class="form-control" rows="3"><?php if(isset($set['description'])){ echo $set['description'];}else{ echo  ""; }?></textarea>
                </div>
            </div>            
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->