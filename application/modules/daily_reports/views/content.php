<style>
	.body .row{
		margin-left: 0px;
		margin-right: 0px;
	}
	.timepicker{
		max-width:none !important;
	}
	table.table-bordered tbody td{
		vertical-align: middle;
	}
	table.table-bordered tbody td span{
		top: 7px;
		position: relative;
	}
</style>
<div class="box">
    <header>
        <h5><i class="fa fa-edit"></i> Daily Report</h5>
        <div class="toolbar">
            <div class="btn-group">             
               <a class="btn btn-primary btn-sm" href="<?php echo site_url('daily_reports/export')?>"><i class="fa fa-file-excel-o"></i> Export Plan</a>               
            </div>
        </div>
    </header>
    <div class="body">
        <div class="row">
			<div role="tabpanel" id="mytab">
				<!-- Nav tabs -->
				
				<ul class="nav nav-tabs" role="tablist">
					<?php 
					$now = date('Y-m-d');
					foreach($date_range as $value){
						$dt = strtotime($value);
						$day = date("l", $dt);
					?>
					<li role="presentation" class="<?php echo ($value==$now) ? 'active' : ''?>"><a href="#tab-<?php echo $value?>"  aria-controls="<?php echo $day?>" role="tab" data-toggle="tab" class="linktab"><?php echo $day.", ".date('d M',$dt) ?></a></li>
					<?php } ?>
				</ul>
				
				<!-- Tab panes -->
				
				<div class="tab-content">
					<?php foreach($date_range as $value){
						$dt = strtotime($value);
						$day = date("l", $dt);
					?>
					<div role="tabpanel" class="tab-pane <?php echo ($value==$now) ? 'active' : ''?>" id="tab-<?php echo $value?>" >
						<div class="col-lg-12">
							<div id="collapse4" class="body">
								<form class="report-post">
								<table data-date="<?php echo $value;?>" class="dataTable table table-bordered table-condensed table-hover table-striped">
									<thead>
										<tr>
											<th>Work Classification</th>
											<th>Job Code</th>
											<th>Work Category</th>
											<th>Work Description</th>
											<th>Planned Hours</th>
											<th>Actual Hours</th>
										</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
								<input type="hidden" name="id_user" value="<?php echo $this->session->userdata('id'); ?>" />
								<?php if($value==$now){?>
								<div class="toolbar">
									<div class="btn-group">
									   <a class="btn btn-primary btn-sm" id="save_button" onclick="call_report_post($(this))"><i class="fa fa-floppy-o"></i> Save</a>
									   <a class="btn btn-primary btn-sm" onclick="addrow($(this))"><i class="fa fa-plus-square-o"></i> Add Row</a>
									</div>
								</div>
								<?php } ?>
								</form>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
				
				
			</div>
            
        </div>
		<!--
		<div class="row">
			<br>
			<div class="panel panel-warning">
			  <div class="panel-heading">Note</div>
			  <div class="panel-body">
				If your workload time passed twelve at noon (09:00 to 14:00), please enter the data separately.<br><br>
				Wrong example:<br>
				<p class="text-danger">[workClassification] [jobCode] [WorkCategory] [WorDescription] [WorkCategory] [plannedHours] 09:00 to 14:00</p>
				Correct example:<br>  
				<p class="text-success">
				[workClassification] [jobCode] [WorkCategory] [WorDescription] [WorkCategory] [plannedHours]  09:00 to 12:00<br>
				[workClassification] [jobCode] [WorkCategory] [WorDescription] [WorkCategory] [plannedHours] 13:00 to 14:00 
				</p>
			  </div>
			</div>
		</div>-->
    </div>
</div>