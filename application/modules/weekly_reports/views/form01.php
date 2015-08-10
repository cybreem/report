<script src="<?php echo config_item('assets'); ?>js/bootstrap-timepicker.js"></script>
<script>
	$('.timepicker').timepicker({
        minuteStep: 10,
        showInputs: false,
        disableFocus: true,
        defaultTime: '00:00',
        showMeridian: false
    });

    $('#myModalform').submit(function() {
        $.ajax({
        	type: 'POST',
            url: "<?php echo site_url('weekly_reports/update_data'); ?>",
            datatype:'json',
            data: $(this).serialize(),
            success: function(data) {
				if(data=="false"){
					alert("Plan time should not be less or more than 8 hour");
				}else if(data=="true"){
					window.location = "<?php echo site_url('weekly_reports');?>";
				}else{
					alert("Please check your data");
				}
            }
        });
        return false;
    });
    
    
    function show_codes(id,to)
    {
        $.ajax({
            url: "<?php echo site_url('weekly_reports/get_match_codes');?>/"+id,
            type: 'POST',
            dataType:"html",
            success: function(data) {
                $('#codes'+to).html(data); 
            }
        });
    }
	
    $(function() {    	
       
        //add activity to report field
        var i = $('#newF div').size() + 1;        	
        $('#addF').click(function() {     
		var id = $(this).attr("id");
            $('<div class="row" id="newF"><hr />'+
					'<input type="hidden" name="id_plan[]" value="" />'+
                    '<div class="col-lg-2">'+'<?php echo trim(preg_replace('/\s+/', ' ', form_dropdown('classification[]', $opt_classifications, '', 'class="form-control new show-codes" onchange="show_codes(this.value)" '))); ?>'+'</div>'+
                    '<div class="col-lg-2" id="codesnew'+i+'"></div>'+
                    '<div class="col-lg-2">'+'<?php echo trim(preg_replace('/\s+/', ' ', form_dropdown('category[]', $opt_categories, '', 'class="form-control"'))); ?>'+'</div>'+
                    '<div class="col-lg-2"><input type="text" class="form-control" name="description[]" value="" /></div>'+
                    '<div class="col-lg-1"><input type="text" class="timepicker form-control" name="plan_from[]"></div><div class="col-lg-1"><input type="text" class="timepicker form-control" name="plan_to[]"></div>'+
                    '<div class="col-lg-1"><input type="text" class="timepicker form-control" name="actual_from[]"></div><div class="col-lg-1"><input type="text" class="timepicker form-control" name="actual_to[]"></div>'+
                    '<span id="ini" class="remove-plan"><a href="#" id="new'+ i +'" onclick=remBtn(\"new'+ i +'\")><i class="fa fa-minus-circle"></i></a></span>'+
                '</div>').appendTo('#repotF');
	            $('.timepicker').timepicker({
			        minuteStep: 10,
			        showInputs: false,
			        disableFocus: true,
			        defaultTime: '00:00',
			        showMeridian: false
			    }); 
			$('#codesnew'+i).siblings().find('select.new').attr('onchange','show_codes(this.value ,"new'+i+'")');
            i++;
                                    
        });
    });
    $(".delete").click(function(e){
		e.preventDefault;
		var id = $(this).data("id");
        $.ajax({
            url: "<?php echo site_url('weekly_reports/delete_data');?>/"+id,
            type: 'POST',
            dataType:"html",
            success: function(data) {
				if(data=="true"){
					gritter_alert("Data deleted");
					$("#row"+id).remove();   
				}else{
					gritter_alert("Unknown error");
				}
            }
        });
	});    
        function remBtn(id) {
            $('#'+id).parents('#newF').remove();             
        }    
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
			    <input type="hidden" name="dateHidden" value="<?php echo $dateHidden ?>" />
				<div class="form-group">
				    <div class="row">
				        <label class="control-label col-lg-1">Date</label>
                        <div class="col-lg-3">
                            <input type="text" name="date" class="form-control" value="<?php echo $date ?>" readonly>
                        </div>
				    </div>                    
                </div>
				<div class="form-group">
				    <div class="row">
                        <label class="control-label col-lg-1">Name</label>
                        <div class="col-lg-3">
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>" readonly>
                        </div>
                    </div>  
                </div>
                <div class="form-group" id="repotF">
					<div class="row">
						<div class="col-lg-12 text-right"><a href="#" id="addF"><i class="fa fa-plus-circle"> Add Planning</i></a></div>
					</div>
                    <div class="row"><hr />
                        <div class="col-lg-2"><strong>Work Classification</strong></div>
                        <div class="col-lg-2"><strong>Job Code</strong></div>
                        <div class="col-sm-2"><strong>Job Categogy</strong></div>
                        <div class="col-lg-2"><strong>Job Description</strong></div>
                        <div class="col-lg-1 text-center"><strong>From</strong></div>
                        <div class="col-lg-1 text-center"><strong>To</strong></div>
                        <div class="col-lg-1 text-center"><strong>From</strong></div>
                        <div class="col-lg-1 text-center"><strong>To</strong></div>
					</div>
				<?php
				$x = 1;
				$z = count($plan);
				foreach($plan as $set){
				?>
                    <div class="row" id="row<?php echo $set->id ?>"><hr />
						<input type="hidden" name="id_plan[]" value="<?php echo $set->id ?>" />
                        <div class="col-lg-2"><?php echo form_dropdown('classification[]', $opt_classifications, ${'classification_id'.$set->id}, 'class="form-control show-codes" onchange="show_codes(this.value, '.$set->id.')"'); ?></div>
                        <div class="col-lg-2" id="codes<?php echo $set->id ?>"><?php echo ${'code'.$set->id }?></div>
                        <div class="col-sm-2"><?php echo form_dropdown('category[]', $opt_categories,  ${'category_id'.$set->id}, 'class="form-control"'); ?></div>
                        <div class="col-lg-2"><input type="text" class="form-control" name="description[]" value="<?php echo set_value('description', $set->description); ?>" /></div>
                        <div class="col-lg-1">
								<input type="text" class="timepicker form-control" name="plan_from[]" value="<?php echo set_value('plan_from', $set->plan_from); ?>">
                        </div>
                        <div class="col-lg-1">
								<input type="text" class="timepicker form-control" name="plan_to[]" value="<?php echo set_value('plan_to', $set->plan_to); ?>">
                        </div>
                        <div class="col-lg-1">
								<input type="text" class="timepicker form-control" name="actual_from[]" value="<?php echo set_value('actual_from', $set->actual_from); ?>">
                        </div>
                        <div class="col-lg-1">
                        	<input type="text" class="timepicker form-control" name="actual_to[]" value="<?php echo set_value('actual_to', $set->actual_to); ?>">
                        </div>
						<span id="ini" class="remove-plan"><a href="javascript:void(0)" data-id="<?php echo $set->id ?>" class="delete"><i class="fa fa-remove"></i></a></span>
                        
					</div>
				<?php
				$x++;
				}
				?>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button class="btn btn-primary" type="submit">Save</button>
			</div>
		</form>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
