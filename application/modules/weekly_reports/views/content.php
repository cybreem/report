
<div class="box">
    <header>
        <h5><i class="fa fa-edit"></i> Weekly Report</h5>
        <div class="toolbar">
            <div class="btn-group">
               <a class="btn btn-primary btn-sm" onclick="call_modal_upload()"><i class="fa fa-file-excel-o"></i> Import Plan</a>               
               <a class="btn btn-primary btn-sm" href="<?php echo site_url('weekly_reports/export')?>"><i class="fa fa-file-excel-o"></i> Export Plan</a>               
            </div>
        </div>
    </header>
    <div class="body">
        <div class="row">
            <div class="col-lg-12">
			<!-- Nav tabs -->
				
				<ul class="nav nav-tabs" role="tablist">
					<?php 
					$now = date('Y-m-d');
					foreach($date_range as $value){
						$dt = strtotime($value);
						$day = date("l", $dt);
					?>
					<li role="presentation" class="<?php echo ($value==$now) ? 'active' : ''?>"><a href="#tab-<?php echo $value?>"  aria-controls="<?php echo $day?>" role="tab" data-toggle="tab" class="linktab"><?php echo $day.", ".date('d M',$dt)?></a></li>
					<?php } ?>
				</ul>
				<div class="tab-content">
					<?php foreach($date_range as $value){
						$dt = strtotime($value);
						$day = date("l", $dt);
					?>
					<div role="tabpanel" class="tab-pane <?php echo ($value==$now) ? 'active' : ''?>" id="tab-<?php echo $value?>" >
						<div id="collapse4" class="body">
							<table data-date="<?php echo $value;?>" class="dataTable table table-bordered table-condensed table-hover table-striped">
								<thead>
									<tr>
										<th>Name</th>
										<th>Division</th>	
										<th>Action</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
					</div>
					<?php } ?>
				</div>
            </div>
        </div>
    </div>
</div>