<div class="box">
  	<header>
		<h5><i class="fa fa-cogs"></i> Project Assignment</h5>
	</header>
	<div class="body">
		<div class="row">
			<div class="body">
				<div role="tabpanel">
				<?php
				if($this->uri->segment(3)==2 || $this->uri->segment(3)==""){
					$class_local = "active";
					$class_offshore = "";
				}else{
					$class_local = "";
					$class_offshore = "active";
				}
				?>
					<div class="col-lg-12">
						<div id="collapse4" class="body">
							<div class="table-responsive">
								<table id="jobCodes" class="table table-bordered table-condensed table-hover table-striped">
									<thead>
										<tr>
											<th>Project Management Code</th>
											<th>Job Code</th>
											<th>Job Name</th>
											<th>Job Classification</th>
											<th>Start Date Plan</th>
											<th>End Date Plan</th>
											<th>Man Day Plan</th>
											<th>Start Date Actual</th>
											<th>End Date Actual</th>
											<th>Man Day Actual</th>
											<th>Sum Plan Time</th>
											<th>Sum Actual Time</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
				
									</tbody>
									<tfoot>
										<tr>
											<th></th>
											<th></th>
											<th></th>
											<th>Job Classification</th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th>Sum Plan Time</th>
											<th>Sum Actual Time</th>
											<th></th>
										</tr>
										<tr>
											<td class="text-left"></td>
											<td class="text-left"></td>
											<td class="text-left"></td>
											<td class="text-left" id="footer-classification"></td>
											<td class="text-left"></td>
											<td class="text-left"></td>
											<td class="text-left"></td>
											<td class="text-left"></td>
											<td class="text-left"></td>
											<td class="text-left"></td>
											<td class="text-left" id="footer-plan"></td>
											<td class="text-left" id="footer-actual"></td>
											<td class="text-left"></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>