<!-- -->
<div class="row">
	<div class="col-lg-8" id="attendance_view">
		<div class="box">
			<header>
				<h5><i class="fa fa-area-chart"></i>&emsp;Attendance Information</h5>
			</header>
			<div class="body">
			
			<h4>&nbsp;&nbsp;&nbsp;<?php echo date('M 01, Y')." - ".date('M d, Y') ; ?></h4><br>
			
			<div class="col-md-6 pull-left">
				<div class="panel panel-warning ">
				  <div class="panel-heading"><strong>Late</strong></div>
				  <div class="panel-body get_list" data-url='late'>
					
				  </div>
				</div>
			</div>
			
			<div class="col-md-6 pull-left">
				<div class="panel panel-danger">
				  <div class="panel-heading"><strong>Absent</strong></div>
				  <div class="panel-body get_list" data-url='absent'>
					
				  </div>
				</div>
			</div>
			
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="box">
			<header>
				<h5><i class="fa fa-bar-chart"></i>&emsp; Operating Rate</h5>
			</header>
			<div class="body">
				
				<div class="text-center" style="padding:0 20px;">
					<h4>Daily Operating Rate ( <?php echo $operating_day[2];?> )</h4>
					<h1 style="color:#4183D7;font-size:2.8em;"><?php echo $operating_day[0];?>%</h1>
					<h5>(<?php echo $operating_day[1];?> Hours)</h5>
					<hr>
					<h4>Weekly Operating Rate ( <?php echo $operating_weeks[2];?> )</h4>
					<h1 style="color:#4183D7;font-size:2.8em;"><?php echo $operating_weeks[0];?>%</h1>
					<h5>(<?php echo $operating_weeks[1];?> Hours)</h5>
				</div>
				
			</div>
		</div>
	</div>
	<?php if($this->session->userdata('level')=="Admin" || $this->session->userdata('level')=="Leader"){ ?>
	<div class="col-lg-12">
		<div class="box">
			<header>
				<h5><i class="fa fa-bar-chart"></i>&emsp; Global Chart</h5>
			</header>

			<div class="body">
				<div class="body">
					<div>
						<canvas id="global_line_chart" height="450" width="600"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	<div class="col-lg-12">
		<div class="box">
			<header>
				<h5><i class="fa fa-stack-overflow "></i>&emsp; Workload Report</h5>
			</header>
			<div class="body">
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			<?php
			foreach($check_privileges as $get => $set){
				$id = "workLoad".$get;
				$division = $set!=""? $set : "All Unit";
			?>
			
				<!-- All / Unit Workload Report -->
				<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="<?php echo $id ?>headingOne">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $id ?>collapseOne" aria-expanded="true" aria-controls="<?php echo $id ?>collapseOne">
							  <?php echo $division; ?>  Workload Report
							</a>
						</h4>
					</div>
					<div id="<?php echo $id ?>collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="<?php echo $id ?>headingOne">
						<div class="col-lg-12">
							<div id="<?php echo $id ?>_all" class="show_daterange"></div>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-12">
									<div role="tabpanel">
										<ul class="nav nav-tabs" role="tablist">
											<li role="presentation"  class="active"><a href="#<?php echo $id ?>workload" id="<?php echo $id ?>_workload_hr" aria-controls="<?php echo $id ?>workload" role="tab" data-toggle="tab">Work Category</a></li>
											<li role="presentation"><a href="#<?php echo $id ?>detail" id="<?php echo $id ?>_detail_hr" aria-controls="<?php echo $id ?>detail" role="tab" data-toggle="tab">Project Detail</a></li>
										</ul>
										<div class="tab-content">
										    <div role="tabpanel" class="tab-pane active" id="<?php echo $id ?>workload">
													<div class="col-lg-6">
													<div class="box">
														<header>
															<h5><i class="fa fa-table"></i>&emsp;Tabel </h5>
														</header>
														<div class="body">
															<table id="<?php echo $id ?>" class="table table-bordered table-condensed table-hover table-striped">
																<thead>
																	<tr>
																		<th>Date</th>
																		<th>Total User</th>
																		<th>Category</th>
																		<th>Planned Time</th>
																		<th>Actual Time</th>
																		<th>Notes</th>
																	</tr>
																</thead>
																<tbody>
											
																</tbody>
															</table>
										                    
														</div>
													</div>
										            <div>
										                <header>
															<h5>Search All Work Load Report</h5>
															<div class="toolbar">
																<div class="btn-group">
																	<a class="btn btn-primary btn-sm export" data-division="<?php echo $get ?>"><i class="fa fa-download"></i> Export to Excel</a>           
																</div>
															</div>
														</header>

										                <div id="<?php echo $id ?>picker" class="body">
										                    <div class="col-lg-6">
										                        <label for="from_dt">Start Date</label>
										                        <div class="input-group">
										                            <input type="date" class="datepicker form-control from_dt" aria-describedby="sizing-addon2" value="<?php echo date('Y-m-d', strtotime('first day of this month'));?>">
										                            <span class="input-group-addon" id="sizing-addon2"><i class="fa fa-calendar fa-fw"></i></span>
										                        </div>
										                    </div>
										                    <div class="col-lg-6">
										                        <label for="to_dt">End Date</label>
										                        <div class="input-group">
										                          	<input type="date" class="datepicker form-control allwork to_dt" aria-describedby="sizing-addon2" value="<?php echo date('Y-m-d');?>">
										                          <span class="input-group-addon" id="sizing-addon2"><i class="fa fa-calendar fa-fw"></i></span>
										                        </div>
										                    </div>
										                </div>
										            </div>
												</div>
												<div class="col-lg-6">
													<div class="col-lg-6">
														<div class="box">
															<header>
																<h5><i class="fa fa-bar-chart"></i>&emsp; Planned Time Chart</h5>
															</header>
															<div class="body">
																<div id="<?php echo $id ?>_canvas-holder-plan">
																	<canvas id="<?php echo $id ?>_plan" width="300" height="300"></canvas>
																</div>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="box">
															<header>
																<h5><i class="fa fa-bar-chart"></i>&emsp; Actual Time Chart</h5>
															</header>
															<div class="body">
																<div id="<?php echo $id ?>_canvas-holder-actual">
																	<canvas id="<?php echo $id ?>_actual" width="300" height="300"></canvas>
																</div>
															</div>
														</div>
													</div>
													<div class="col-lg-12">
														<div class="box">
															<div class="row">
																<div class="col-lg-12">
																<div class="col-lg-8">
																	<div class="box text-center">
																		<header class="col-lg-12"><h4>Operating Rate</h4></header>
																		<div id="<?php echo $id ?>_OR" class="body">
																			<h1 style="color:#4183D7;font-size:4em;"></h1>
																			<h5></h5>
																		</div>
																	</div>
																</div>
																<div class="col-lg-4">
																	<div class="box">
																		<div class="body">
																			<div id="<?php echo $id ?>_chart_legend"></div>
																		 </div>
																	</div>
																</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										    <div role="tabpanel" class="tab-pane" id="<?php echo $id ?>detail">
										    	<div class="col-lg-6">
										    		<div class="box">
														<div class="body">
															<table id="<?php echo $id ?>_detail" class="table table-bordered table-condensed table-hover table-striped">
																<thead>
																	<tr>
																	    <th>Total User</th>
																		<th>Work Classification</th>
																		<th>Planned Time</th>
																		<th>Actual Time</th>
																	</tr>
																</thead>
																<tbody>
											
																</tbody>
															</table>
										                    
														</div>
														<div class="body">
															<table id="<?php echo $id ?>_detail2" class="table table-bordered table-condensed table-hover table-striped">
																<thead>
																	<tr>
																		<th>Job Codes</th>
																		<th>Planned Time</th>
																		<th>Actual Time</th>
																	</tr>
																</thead>
																<tbody>
											
																</tbody>
															</table>
										                    
														</div>
													</div>
												</div>
												<div class="col-lg-6">
													<div class="col-lg-6">
														<div class="box">
															<header>
																<h5><i class="fa fa-bar-chart"></i>&emsp; Work Classification Chart</h5>
															</header>
															<div class="body">
																<div id="<?php echo $id ?>_canvas-holder-plan_detail">
																	<canvas id="<?php echo $id ?>_plan_detail" width="300" height="300"></canvas>
																</div>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="box">
															<header>
																<h5><i class="fa fa-bar-chart"></i>&emsp; Job code Chart</h5>
															</header>
															<div class="body">
																<div id="<?php echo $id ?>_canvas-holder-actual_detail">
																	<canvas id="<?php echo $id ?>_actual_detail" width="300" height="300"></canvas>
																</div>
															</div>
														</div>
													</div>
													<div class="col-lg-12">
														<div class="box">
															<div class="row">
																<div class="col-lg-12">
																<div class="col-lg-6">
																	<div class="box">
																		<div class="body">
																			<div id="<?php echo $id ?>_chart_detail_legend"></div>
																		 </div>
																	</div>
																</div>
																<div class="col-lg-6">
																	<div class="box">
																		<div class="body">
																			<div id="<?php echo $id ?>_chart_detail2_legend"></div>
																		 </div>
																	</div>
																</div>
																</div>
															</div>
														</div>
													</div>
												</div>
										    </div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- / All / Unit Workload Report  -->

				<?php
				}
				?>				
				  <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading6">
							<h4 class="panel-title"><a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse6" aria-expanded="false" aria-controls="collapse6"> Search Individual Workload Report </a></h4>
						</div>
						<div id="collapse6" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading6">
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-12">
										<div role="tabpanel">
											<div class="tab-content">
												<div role="tabpanel" class="tab-pane active" id="workload_backend">
													<div class="col-lg-6">
														<div class="box">
															<header>
																<h5>Individual WorkLoad Report</h5>
															</header>
															<div id="individual_date" class="body">
																<div class="col-lg-12">
																	<div class="input-group">
																		<span class="input-group-addon">															
																			<?php
																			$id_user = $this->session->userdata('id');
																			if($this->session->userdata('level')=="Member"){
																				$members = array($this->session->userdata('id') =>$this->session->userdata('name'));
																			}
																			echo form_dropdown('members', $members, $id_user, 'class="form-control" id="cumcum" style="width: 100%" onChange="refresh_individual();inTable();detail2();"'); ?>
																		</span>
																	</div>
																</div>
																<div class="col-lg-6">
																	<label for="from_dt">Start Date</label>
																	<div class="input-group">
																		<input type="date" class="form-control from_dt" aria-describedby="sizing-addon2">
																		<span class="input-group-addon" id="sizing-addon2"><i class="fa fa-calendar fa-fw"></i></span>
																	</div>
																</div>
																<div class="col-lg-6">
																	<label for="to_dt">End Date</label>
																	<div class="input-group">
																		<input type="date" class="form-control to_dt" aria-describedby="sizing-addon2">
																		<span class="input-group-addon" id="sizing-addon2"><i class="fa fa-calendar fa-fw"></i></span>
																	</div>
																</div>
															</div>
														</div>
														<div class="box">
															<header>
																<h5><i class="fa fa-table"></i>&emsp;Tabel </h5>
															</header>
															<div class="body">
																<h4 class="text-center showing-individual">Showing data</h4>
																<table id="individual_table" class="table table-bordered table-condensed table-hover table-striped">
																	<thead>
																		<tr>
																			<th>Category</th>
																			<th>Planned Time</th>
																			<th>Actual Time</th>
																			<th>Notes</th>
																		</tr>
																	</thead>
																	<tbody>

																	</tbody>
																</table>

															</div>
														</div>
														<div class="box">
															<div class="body">
																<table id="individual_detail1" class="table table-bordered table-condensed table-hover table-striped">
																	<thead>
																		<tr>
																			<th>Work Classification</th>
                                                                            <th>Planned Time</th>
                                                                            <th>Actual Time</th>
																		</tr>
																	</thead>
																	<tbody>

																	</tbody>
																</table>

															</div>
															<div class="body">
																<table id="individual_detail2" class="table table-bordered table-condensed table-hover table-striped">
																	<thead>
																		<tr>
																			<th>Job Codes</th>
                                                                            <th>Planned Time</th>
																			<th>Actual Time</th>
																		</tr>
																	</thead>
																	<tbody>

																	</tbody>
																</table>

															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-6">
															<div class="box">
																<header>
																	<h5><i class="fa fa-bar-chart"></i>&emsp;Planned Time Chart</h5>
																</header>
																<div class="body">
																	<div id="canvas-holder-individual-plan">
																		<canvas id="individual_plan" width="300" height="300"></canvas>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-lg-6">
															<div class="box">
																<header>
																	<h5><i class="fa fa-bar-chart"></i>&emsp;Actual Time Chart</h5>
																</header>
																<div class="body">
																	<div id="canvas-holder-individual-actual">
																		<canvas id="individual_actual" width="300" height="300"></canvas>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12">
															<div class="box">
																<div class="row">
																	<div class="col-lg-12">
																		<div class="col-lg-4">
																			<div class="box">
																				<div class="body">
																					<div id="chart_legend_individual"></div>
																				</div>
																			</div>
																		</div>
																		<div class="col-lg-8">
																			<div class="box text-center">
																				<header class="col-lg-12">
																					<h4>Operating Rate (based on a selected date)</h4>
																				</header>
																				<div class="body" id="individual_OR">
																					<h1 style="color:#4183D7;font-size:4em;"></h1>
																					<h5></h5>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
			     </div>
		      </div>
		</div>
	</div>
