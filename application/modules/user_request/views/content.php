<style>
	.body .row{
		margin-left: 0px;
		margin-right: 0px;
	}
</style>
<div class="box">
    <header>
        <h5><i class="fa fa-gavel"></i> Member Request</h5>
		<div class="toolbar">
			<?php if($this->session->userdata('level')!='Admin'){?>
            <div class="btn-group">
               <a class="btn btn-primary btn-sm" onclick="makeRequest()"><i class="fa fa-exclamation"></i> Make Request</a>               
            </div>
			<?php } ?>
        </div>
    </header>
    <div class="body">
        <div class="row">
			<div role="tabpanel" id="mytab">
				<!-- Nav tabs -->
				
				<ul class="nav nav-tabs" role="tablist">
					<?php if($this->session->userdata('level')!='Admin'){?>
					<li role="presentation" class="active"><a href="#tab1"  aria-controls="history" role="tab" data-toggle="tab" class="linktab">History</a></li>
					<?php }
					if($this->session->userdata('level')!='Member'){?>
					<li role="presentation" class="<?php 
					echo ($this->session->userdata('level')=='Admin') ? 'active' :'';
					?>"><a href="#tab2"  aria-controls="history" role="tab" data-toggle="tab" class="linktab">Member Request</a></li>
					<?php }?>
				</ul>
				
				<!-- Tab panes -->
				
				<div class="tab-content">
					<?php if($this->session->userdata('level')!='Admin'){?>
					<div role="tabpanel" class="tab-pane active" id="tab1" >
						<div class="col-lg-12">
							<div id="collapse4" class="body">
								
								<table data-url="<?php  if(isset($source)) echo $source; ?>" id="self_req" class="dataTable table table-bordered table-condensed table-hover table-striped">
									<thead>
										<tr>
											<th>No</th>
											<th>Request</th>
											<th>Start Time</th>
											<th>End Time</th>
											<th>Leader Approve</th>
											<th>Manager Approve</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
								
							</div>
						</div>
					</div>
					<?php }
					if($this->session->userdata('level')!='Member'){?>
					<div role="tabpanel" class="tab-pane <?php 
					echo ($this->session->userdata('level')=='Admin') ? 'active' :'';
					?>" id="tab2" >
						<div class="col-lg-12">
							<div id="collapse4" class="body">
								
								<table class="dataTable table table-bordered table-condensed table-hover table-striped" id="member_req" data-url="<?php  if(isset($memberRequest)) echo $memberRequest; ?>">
									<thead>
										<tr>
											<th>No</th>
											<th>Name</th>
											<th>Unit</th>
											<th>Request</th>
											<th>Start Time</th>
											<th>End Time</th>
											<th>Leader Approve</th>
											<th>Manager Approve</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
								
							</div>
						</div>
					</div>
					<?php }?>
				</div>
			</div>
            
        </div>
    </div>
</div>