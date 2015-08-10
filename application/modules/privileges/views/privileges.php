<script>
    $('.myForm').submit(function(e) {
	alert("lol");
	e.preventDefault();
        $.ajax({
        	type: 'POST',
            url: '<?php echo site_url('privileges/save_privilege'); ?>',
            data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false
            success: function(data) {
            	jsonData = $.parseJSON(data);
                if(jsonData.status == 0) {
					console.log(data);
                	//gritter_alert(jsonData.alert);
                } else {
                    //window.location = "<?php echo site_url('privileges/show_list');?>";
                }
            }
        });
        return false;
    });
</script>
<div class="box">
    <header>
        <h5><i class="fa fa-user"></i> Users Privileges</h5>
    </header>
    <div class="body">
        <div class="row">
            <div class="col-lg-12">
                <div id="collapse4" class="body table-responsive">
                    <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">                        
                        <tbody>
                           <tr>
                            <?php foreach($generated as $row => $val) : ?>
								<form class="myForm" method="post" action="<?php echo site_url('privileges/save_privilege'); ?>">
								<input type="hidden" name="form_level" value="<?php echo $row; ?>" />
                                <td>
									<div class="panel panel-default">
										<div class="panel-heading clearfix">
											<span class="pull-left"><?php echo $row; ?></span>
											<div class="toolbar">
												<button type="submit" name="save" class="btn btn-primary btn-xs pull-right" ><span class="fa fa-floppy-o" aria-hidden="true" style="color: #ffffff !important;"></span> Save</button>
											</div>
										</div>
										<div class="panel-body">
											<?php
											foreach($val as $key => $show){
												?>
												<ul class="list-group">
													<li class="list-group-item active" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $row.$key; ?>" aria-expanded="true" aria-controls="collapseOne">
													<?php
													echo ucwords(str_replace('_',' ',$key));
													?>
													</li>
													<div id="collapse<?php echo $row.$key; ?>" class="collapse">
													<?php
													foreach($show as $set) : 
													?>
														<li class="list-group-item">
															<div class="checkbox">
																<label>
																<?php
																 $checked = in_array($key."_".$set,$list_check)? "checked" : "";

																?>
																 <input type="checkbox" id="checkboxWarning" <?php echo $checked ?> name="<?php echo $row ?>[]" value="<?php echo $key."_".$set ?>"><?php echo ucwords(str_replace('_',' ',$set)) ?>
																</label>
															</div>
														</li>
													<?php endforeach ?> 
													</div>
												</ul>
												<?php
											}
											?>
										</div>
									</div>
								</td>
								</form>
                            <?php endforeach ?>                             
                           </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>