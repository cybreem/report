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
    
    function show_codes(id)
    {
        $.ajax({
            url: "<?php echo site_url('daily_reports/get_match_codes');?>/"+id,
            type: 'POST',
            dataType:"html",
            success: function(data) {
                $('#codes').html(data); 
            }
        });
    }
    
    $(function() {
        //add activity to report field
        var i = $('#newF div').size() + 1;
        
        $('#addF').click(function() {
            $('<div class="row" id="newF"><hr />'+
                    '<div class="col-lg-2">'+'<?php echo trim(preg_replace('/\s+/', ' ', form_dropdown('classification', $opt_classifications, '', 'class="form-control" onchange="show_codes(this.value)"'))); ?>'+'</div>'+
                    '<div class="col-lg-2" id="codes"></div>'+
                    '<div class="col-lg-2">'+'<?php echo trim(preg_replace('/\s+/', ' ', form_dropdown('category', $opt_categories, '', 'class="form-control"'))); ?>'+'</div>'+
                    '<div class="col-lg-3"><input type="text" class="form-control" name="dsd' + i +'" value="" /></div>'+
                    '<div class="col-lg-1"><input type="text" class="form-control" name="dsd' + i +'" value="" /></div>'+
                    '<div class="col-lg-1"><input type="text" class="form-control" name="dsd' + i +'" value="" /></div>'+
                    '<div class="col-lg-1" id="ini"><a href="#" id="tai'+ i +'" onclick="remBTN(this.id)"><i class="fa fa-minus-circle"></i></a></div>'+
                '</div>').appendTo('#repotF');
            i++;                         
        });
        
        function remBTN(id) {
            $('#'+id).click(function() { 
                if( i > 2 ) {
                    $(this).parents('#newF').remove();
                    i--;
                }
                return false;
            });                
        }    
    });
</script>

<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<form id="myModalform">
		    <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title"><i class="fa fa-edit"></i> Daily Report Form</h4>
			</div>
			<div class="modal-body">
			    <input type="hidden" name="id" value="<?php echo $id; ?>" />
				<div class="form-group">
				    <div class="row">
				        <label class="control-label col-lg-1">Date</label>
                        <div class="col-lg-3">
                            <input type="text" name="nip" class="form-control" value="<?php echo date('d/m/y') ?>" readonly>
                        </div>
				    </div>                    
                </div>
				<div class="form-group">
				    <div class="row">
                        <label class="control-label col-lg-1">Name</label>
                        <div class="col-lg-3">
                            <input type="text" name="nip" class="form-control" value="<?php echo $this->session->userdata('name'); ?>" readonly>
                        </div>
                    </div>  
                </div>
                <div class="form-group" id="repotF">
                    <div class="row"><hr />
                        <div class="col-lg-2"><?php echo form_dropdown('classification', $opt_classifications, '', 'class="form-control" onchange="show_codes(this.value)"'); ?></div>
                        <div class="col-lg-2" id="codes"></div>
                        <div class="col-lg-2"><?php echo form_dropdown('category', $opt_categories, '', 'class="form-control"'); ?></div>
                        <div class="col-lg-3"><input type="text" class="form-control" name="category" value="<?php echo set_value('category', $category); ?>" /></div>
                        <div class="col-lg-1"><input type="text" class="form-control" name="category" value="<?php echo set_value('category', $category); ?>" /></div>
                        <div class="col-lg-1"><input type="text" class="form-control" name="category" value="<?php echo set_value('category', $category); ?>" /></div>
                        <div class="col-lg-1"><a href="#" id="addF"><i class="fa fa-plus-circle"></i></a></div>
                    </div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal">Cancel</button>
				<button class="btn btn-primary" type="submit">Save</button>
			</div>
		</form>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
