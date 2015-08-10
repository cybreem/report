<div class="box">
	<header>
		<h5><i class="fa fa-info-circle"></i> Application Help</h5>
	</header>
	<div class="body">
		<div class="row help" style="white-space:normal;">
			<style>
				.help .panel{
					border-left: none !important;
					border-right: none !important;
					border-top: 1px solid transparent;
				}
				dt{
					margin: 15px 0 5px 0;
				}
				dt:before{
					content:" "
				}
				
				li{
					margin: 8px 0;
				}
			</style>
			<div class="col-lg-12">
				<div id="collapse4" class="body">
					<h4>Welcome to the Application Help Center</h4>
					<p>This page contains basic information and how to operate this web application.</p>
					<br>
					<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
						
					  <!-- Manage Your Profile -->
					  <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading01">
						  <h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse01" aria-expanded="true" aria-controls="collapse01"><span class="glyphicon glyphicon-plus"></span>
							  Manage your profile
							</a>
						  </h4>
						</div>
						<div id="collapse01" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading01">
						  <div class="panel-body">
							<dl>
							  <dt>Change profile picture</dt>
							  <dd class="row">
								  <div class="col-md-6">
									<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/userProfile_05.PNG"><img src="<?php echo config_item('assets'); ?>img/help/userProfile_05.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
								  </div>
								  <div class="col-md-6">
									<h4>instruction :</h4>
									<ul>
										<li>Hover your cursor on your profile picture.</li>
										<li><img src="<?php echo config_item('assets'); ?>img/help/changepic.PNG" alt="change"> button will appear, click on the button.</li>
										<li>Upload window will appear, click on browse button to find your picture and then click open button.</li>
										<li>Finally click "Upload" button to update your profile picture.</li>
									</ul>
								   </div>
							   </dd>  
							  <dt>Change your data to keep your profile up to date</dt>
							  <dd class="row">
								  <div class="col-md-6">
									<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/userProfile_02.PNG"><img src="<?php echo config_item('assets'); ?>img/help/userProfile_02.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
								  </div>
								  <div class="col-md-6">
									<h4>instruction :</h4>
									<ul>
										<li>On top right corner, click your username  and than click menu "Edit user profile".</li>
										<li>Your profile will appear, click button "update" to enable text field.</li>
										<li>After update/edit your data click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to apply your change.</li>
										<li>or click button "cancel" to abort your change.</li>
									</ul>
								  </div>
							  </dd>  
							  <dt>Update password</dt>
							  <dd class="row">
								  <div class="col-md-6">
									<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/userProfile_04.PNG"><img src="<?php echo config_item('assets'); ?>img/help/userProfile_04.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
								  </div>
								  <div class="col-md-6">
									<h4>instruction :</h4>
									<ul>
										<li>On user profile page.</li>
										<li>Click <img src="<?php echo config_item('assets'); ?>img/help/changepass.PNG" alt="Change Password"> button</li>
										<li>Change password window will appear, type your new password twice.</li>
										<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to apply your change.</li>
									</ul>
								  </div>
							  </dd>
							</dl>
						  </div>
						</div>
					  </div>
					  <!-- /Manage Your Profile -->
					  
					  <!-- Dashboard Information -->
					  <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading02">
						  <h4 class="panel-title">
							<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse02" aria-expanded="false" aria-controls="collapse02"><span class="glyphicon glyphicon-plus"></span>
							   Dashboard Information
							</a>
						  </h4>
						</div>
						<div id="collapse02" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading02">
						  <div class="panel-body">
							<dl>
							  <dt>Attendance Information & Operating rate</dt>
							  <dd class="row">
								  <div class="col-md-6">
									<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/dashboard_01.PNG"><img src="<?php echo config_item('assets'); ?>img/help/dashboard_01.PNG" alt="Attendance Information" class="img-thumbnail"></a><br>
								  </div>
								  <div class="col-md-6">
									<h4>note :</h4>
									
									<ul>
										<li>Administrator
											<ul>
												<li>Attendance Information on administrator provide summary of late and Absent for Each unit within a month.</li>
												<li>Operating Rate provide information about General Daily Operating Rate and Weekly Operating Rate.</li>
											</ul>
										</li>
										<li>Leader &amp; Member
											<ul>
												<li>Attendance Information will provide summary of late and Absent for individual within a month, and</li>
												<li>Operating Rate provide information about individual Daily Operating Rate and Weekly Operating Rate.</li>
											</ul>
										</li>
									</ul>
								   </div>
							  </dd>  
							  <dt>Workload (work category)</dt>
							  <dd class="row">
								  <div class="col-md-6">
									<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/dashboard_02.PNG"><img src="<?php echo config_item('assets'); ?>img/help/dashboard_02.PNG" alt="Workload (work category)" class="img-thumbnail"></a><br>
								  </div>
								  <div class="col-md-6">
									<h4>note :</h4>
									<ul>
										<li>Workload report table contains information about planned and actual time base on work category.</li>
										<li>Change start date &amp; end date to view another day workload.</li>
										<li>Use <img src="<?php echo config_item('assets'); ?>img/help/toExcel.PNG" alt="to excel"> button to create excel file, base on selected date.</li>
										<li>For Leader, workload only showing data from their unit member, and</li>
										<li>For Member, workload only showing Individual data. </li>
									</ul>
								   </div>
							  </dd>  
							  <dt>Workload (project detail)</dt>
							  <dd class="row">
								  <div class="col-md-6">
									<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/dashboard_03.PNG"><img src="<?php echo config_item('assets'); ?>img/help/dashboard_03.PNG" alt="Workload (project detail)" class="img-thumbnail"></a><br>
								  </div>
								  <div class="col-md-6">
									<h4>note :</h4>
									<ul>
										<li>On project detail tab, this view display information base on Work Classification and Job Codes.</li>
										<li>Data on this view are detailed from Work Category page.</li>
									</ul>
								   </div>
							  </dd>  
							  <dt>Individual Workload Detail</dt>
							  <dd class="row">
								  <div class="col-md-6">
									<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/dashboard_04.PNG"><img src="<?php echo config_item('assets'); ?>img/help/dashboard_04.PNG" alt="Individual Workload Detail" class="img-thumbnail"></a><br>
								  </div>
								  <div class="col-md-6">
									<h4>note :</h4>
									<ul>
										<li>Individual workload deliver us information about individual member workload day by day.</li>
										<li>You can customize data by search/select member name, adjust start &amp; end date.</li>
									</ul>
								   </div>
							  </dd> 
							  
							</dl>
						  </div>
						</div>
					  </div>
					  <!-- /Dashboard Information -->
					  
					  <!-- Daily Attendance -->
					  <?php if(($this->session->userdata('level')=="Leader")||($this->session->userdata('level')=="Admin")){ ?>
					  <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading06">
						  <h4 class="panel-title">
							<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse06" aria-expanded="false" aria-controls="collapse06"><span class="glyphicon glyphicon-plus"></span>
							   Daily Attendance
							</a>
						  </h4>
						</div>
						<div id="collapse06" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading06">
						  <div class="panel-body">
							<dl>
							  <dt>Attendance Table</dt>
							  <dd class="row">
								  <div class="col-md-6">
									<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/dailyAttendance_01.PNG"><img src="<?php echo config_item('assets'); ?>img/help/dailyAttendance_01.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
								  </div>
								  <div class="col-md-6">
									<h4>instruction :</h4>
									<ul>
										<li>This page provide member attendance information.</li>
										<li>Click on the number in the cell to see the details of attendance.</li>
										<li>Use <img src="<?php echo config_item('assets'); ?>img/help/toExcel.PNG" alt="Export to Excel"> button to export data in to excel file.</li>
									</ul>
								   </div>
							  </dd>
							  
							</dl>
						  </div>
						</div>
					  </div>
					  <?php } ?>
					  <!-- /Daily Attendance -->
					  
					  <!-- Member request -->
					  <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading07">
						  <h4 class="panel-title">
							<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse07" aria-expanded="false" aria-controls="collapse07"><span class="glyphicon glyphicon-plus"></span>
							  Member Request
							</a>
						  </h4>
						</div>
						<div id="collapse07" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading07">
						  <div class="panel-body">
							<!-- Here we insert another nested accordion -->

							<div class="panel-group" id="accordion0701">
							  <?php if(($this->session->userdata('level')=="Leader")||($this->session->userdata('level')=="Member")){ ?>
							  <div class="panel panel-default">
								<div class="panel-heading">
								  <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion0701" href="#collapseInner0701">
									History
								  </a></h4>
								</div>
								<div id="collapseInner0701" class="panel-collapse collapse in">
								<?php if(($this->session->userdata('level')=="Leader")){ ?>
								  <div class="panel-body">
									<dl>
									  <dt>Create new request</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/memberRequest_03.PNG"><img src="<?php echo config_item('assets'); ?>img/help/memberRequest_03.PNG" alt="Create new request" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/makeRequest.PNG" alt="save"> button to create new request.</li>
												<li>"Member request form" will appear and fill in the text field.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
											</ul>
										   </div>
									   </dd>  
									   <dt>View detail request</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/memberRequest_05.PNG"><img src="<?php echo config_item('assets'); ?>img/help/memberRequest_05.PNG" alt="View detail request" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/detail.PNG" alt="detail"> button to view detail request.</li>
												<li>"Edit member request form" will appear and change the data on text field.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
												<li>Or click <img src="<?php echo config_item('assets'); ?>img/help/cancel.PNG" alt="cancel"> button to abort the update.</li>
											</ul>
										   </div>
									   </dd>  
									</dl>
								  </div>
								   <?php }elseif(($this->session->userdata('level')=="Member")){  ?>
								  <div class="panel-body">
									<dl>
									  <dt>Create new request</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/memberRequest_06.PNG"><img src="<?php echo config_item('assets'); ?>img/help/memberRequest_06.PNG" alt="Create new request" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/makeRequest.PNG" alt="save"> button to create new request.</li>
												<li>"Member request form" will appear and fill in the text field.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
											</ul>
										   </div>
									   </dd>  
									   <dt>View detail request</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/memberRequest_07.PNG"><img src="<?php echo config_item('assets'); ?>img/help/memberRequest_07.PNG" alt="View detail request" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/detail.PNG" alt="detail"> button to view detail request.</li>
												<li>"Edit member request form" will appear and change the data on text field.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
												<li>Or click <img src="<?php echo config_item('assets'); ?>img/help/cancel.PNG" alt="cancel"> button to abort the update.</li>
											</ul>
										   </div>
									   </dd>  
									</dl>
								  </div>
								   <?php } ?>
								</div>
							  </div>
							  <?php } ?>
							  
							  <?php if(($this->session->userdata('level')=="Leader")||($this->session->userdata('level')=="Admin")){ ?>
							  <div class="panel panel-default">
								<div class="panel-heading">
								  <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion0701" href="#collapseInner0702">
									Member Request List
								  </a></h4>
								</div>
								<div id="collapseInner0702" class="panel-collapse collapse">
								  <div class="panel-body">
									<dl>
									  <dt>Approve or Decline Member Request</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/memberRequest_02.PNG"><img src="<?php echo config_item('assets'); ?>img/help/memberRequest_02.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On Member Request page, click <img src="<?php echo config_item('assets'); ?>img/help/detail.PNG" alt="detail"> button</li>
												<li>"Details Form" will appear, and showing member request list, fill the note and click Approve or Decline button.</li>
												<li>For administrator, request list only display request that already approve by Leader.</li>
											</ul>
										   </div>
									  </dd>									  
									</dl>
								  </div>
								</div>
							  </div>
							  <?php } ?>
							</div>

							<!-- Inner accordion ends here -->							
						  </div>
						</div>
					  </div>
					  <!-- /Member request -->
					  
					  <!-- Reporting Activity -->
					  <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading03">
						  <h4 class="panel-title">
							<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse03" aria-expanded="false" aria-controls="collapse03"><span class="glyphicon glyphicon-plus"></span>
							  Reporting Activity
							</a>
						  </h4>
						</div>
						<div id="collapse03" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading03">
						  <div class="panel-body">
							<!-- Here we insert another nested accordion -->

							<div class="panel-group" id="accordion0301">
							  <?php if(($this->session->userdata('level')=="Leader")||($this->session->userdata('level')=="Member")){ ?>
							  <div class="panel panel-default">
								<div class="panel-heading">
								  <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion0301" href="#collapseInner0301">
									Set Daily Report
								  </a></h4>
								</div>
								<div id="collapseInner0301" class="panel-collapse collapse in">
								  <div class="panel-body">
									<dl>
									  <dt>Entry daily report</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/dailyReport_01.PNG"><img src="<?php echo config_item('assets'); ?>img/help/dailyReport_01.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On daily report page, fill Actual Hours column with your actual work time.</li>
												<li>Click "Add Row" button to add new row for recording additional/unplanned work and fill the text field.</li>
												<li>Total Actual Hours <strong>should be 8 Hours</strong></li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
											</ul>
										   </div>
									   </dd>  
									   <dt>Change daily report</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/dailyReport_02.PNG"><img src="<?php echo config_item('assets'); ?>img/help/dailyReport_02.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>Double click on the text and the text field will appear, type/select your data change.</li>
												<li>Click outside the text field to apply the change.</li>
												<li>You can't change planned work, unless you have privilege as a Leader.</li>
											</ul>
										   </div>
									   </dd>  
									  
									</dl>
								  </div>
								</div>
							  </div>
							  <?php } ?>
							  
							  <?php if(($this->session->userdata('level')=="Leader")||($this->session->userdata('level')=="Admin")){ ?>
							  <div class="panel panel-default">
								<div class="panel-heading">
								  <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion0301" href="#collapseInner0302">
									Weekly Report
								  </a></h4>
								</div>
								<div id="collapseInner0302" class="panel-collapse collapse">
								  <div class="panel-body">
									<dl>
									  <dt>Import weekly plan</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/weeklyReport_02.PNG"><img src="<?php echo config_item('assets'); ?>img/help/weeklyReport_02.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On weekly report page, click <img src="<?php echo config_item('assets'); ?>img/help/import.PNG" alt="import"> button</li>
												<li>"Upload Form" will appear, browse/choose your file and click open.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/upload.PNG" alt="upload"> button to complete the process.</li>
											</ul>
										   </div>
									  </dd>  
									  <dt>Add or Change member plan</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/weeklyReport_04.PNG"><img src="<?php echo config_item('assets'); ?>img/help/weeklyReport_04.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On Action column, click <img src="<?php echo config_item('assets'); ?>img/help/edit.PNG" alt="edit"> "Edit Plan" button.</li>
												<li>"Daily report form" will appear, and change your plan.</li>
												<li>Click "+ Add planning" link to add new plan.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
												<li>Or click <img src="<?php echo config_item('assets'); ?>img/help/cancel.PNG" alt="cancel"> button to abort the change.</li>
												<li>Another action is delete the Plan by clicking on <img src="<?php echo config_item('assets'); ?>img/help/delete.PNG" alt="delete"> delete button.</li>
											</ul>
										   </div>
									  </dd>
									</dl>
								  </div>
								</div>
							  </div>
							  
							  <div class="panel panel-default">
								<div class="panel-heading">
								  <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion0301" href="#collapseInner0303">
									Project Assignment
								  </a></h4>
								</div>
								<div id="collapseInner0303" class="panel-collapse collapse">
								  <div class="panel-body">
									<dl>
									  <dt>Project Assignment Table</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/projectAssignment_01.PNG"><img src="<?php echo config_item('assets'); ?>img/help/projectAssignment_01.PNG" alt="project assignment" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>This page provide information : Job Classification, Project Management Code, Job Name, Start &amp; End Date Plan, Start &amp; End Date Actual, Man day Plan &amp; Actual</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/editStatus.PNG" alt="edit status"> button to change actual time &amp; project status.</li>
											</ul>
										   </div>
									  </dd>  
									  <dt>View Assign Member</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/projectAssignment_02.PNG"><img src="<?php echo config_item('assets'); ?>img/help/projectAssignment_02.PNG" alt="project assignment" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On Action column, click <img src="<?php echo config_item('assets'); ?>img/help/viewAssignMember.PNG" alt=" assign member"> button.</li>
												<li>"Job Breakdown" window will appear, provide information about member name, planned time &amp; actual time.</li>
											</ul>
										   </div>
									  </dd>  
									  <dt>Detail Assignment</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/projectAssignment_03.PNG"><img src="<?php echo config_item('assets'); ?>img/help/projectAssignment_03.PNG" alt="project assignment" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On the member name column below the name,  click the category "Coding/Design/Verification" link.</li>
												<li>"Detail Assignment" window will appear &amp; provide detail information about member task in this project.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/back.PNG" alt="back"> button to go back previous window.</li>
											</ul>
										   </div>
									   </dd>  
									  
									</dl>
								  </div>
								</div>
							  </div>
							  <?php } ?>
							  
							  
							</div>

							<!-- Inner accordion ends here -->							
						  </div>
						</div>
					  </div>
					  <!-- /Reporting Activity -->
					  
					  <!-- Manage Member List -->
					  <?php if($this->session->userdata('level')=="Admin"){ ?>
					  <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading04">
						  <h4 class="panel-title">
							<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse04" aria-expanded="false" aria-controls="collapse04"><span class="glyphicon glyphicon-plus"></span>
							  Manage Member List
							</a>
						  </h4>
						</div>
						<div id="collapse04" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading04">
						  <div class="panel-body">
							<!-- Here we insert another nested accordion -->

							<div class="panel-group" id="accordion0402">
							  <div class="panel panel-default">
								<div class="panel-heading">
								  <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion0402" href="#collapseInner0401">
									Manage Member Data 
								  </a></h4>
								</div>
								<div id="collapseInner0401" class="panel-collapse collapse in">
								  <div class="panel-body">
									
									<dl>
									  <dt>Import data from excel</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/user_02.PNG"><img src="<?php echo config_item('assets'); ?>img/help/user_02.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On the Member page, click <img src="<?php echo config_item('assets'); ?>img/help/import.PNG" alt="import"> button</li>
												<li>"Upload Form" will appear, browse/choose your file and click open.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/upload.PNG" alt="upload"> button to complete the process.</li>
											</ul>
										   </div>
									  </dd>  
									  <dt>Add new member data</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/user_03.PNG"><img src="<?php echo config_item('assets'); ?>img/help/user_03.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On the Member page, click <img src="<?php echo config_item('assets'); ?>img/help/import.PNG" alt="import"> button</li>
												<li>"Member Form" will appear, fill the text field according to the title of field.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
											</ul>
										   </div>
									  </dd>  
									  <dt>Change member data</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/user_04.PNG"><img src="<?php echo config_item('assets'); ?>img/help/user_04.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On the Action column, click edit <img src="<?php echo config_item('assets'); ?>img/help/edit.PNG" alt="edit"> button</li>
												<li>"Member Form" will appear, update/edit the text field content.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
												<li>Or click <img src="<?php echo config_item('assets'); ?>img/help/cancel.PNG" alt="cancel"> button to abort the update.</li>
												<li>Another action is inactive Member by clicking  on <img src="<?php echo config_item('assets'); ?>img/help/disable.PNG" alt="disable"> button and,</li>
												<li>Delete Member by clicking on <img src="<?php echo config_item('assets'); ?>img/help/delete.PNG" alt="delete"> button.</li>
											</ul>
										   </div>
									   </dd>  
									  
									</dl>
								  </div>
								</div>
							  </div>
							  <div class="panel panel-default">
								<div class="panel-heading">
								  <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion0402" href="#collapseInner0402">
									Manage Users &amp; Privileges
								  </a></h4>
								</div>
								<div id="collapseInner0402" class="panel-collapse collapse">
								  <div class="panel-body">
									<dl>
									  <dt>Add new privilege</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/userPrivileges_01.PNG"><img src="<?php echo config_item('assets'); ?>img/help/userPrivileges_01.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On the Division page, click <img src="<?php echo config_item('assets'); ?>img/help/addnew.PNG" alt="edit"> button</li>
												<li>"Privilege Form" will appear, fill the text field according to the title of field.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
											</ul>
										   </div>
									  </dd>  
									  <dt>Change privilege name</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/userPrivileges_02.PNG"><img src="<?php echo config_item('assets'); ?>img/help/userPrivileges_02.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On the Action column, click edit <img src="<?php echo config_item('assets'); ?>img/help/edit.PNG" alt="edit"> button to edit privilege name.</li>
												<li>"Group Form" will appear, update/edit the text field content.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
												<li>Or click <img src="<?php echo config_item('assets'); ?>img/help/cancel.PNG" alt="cancel"> button to abort the update.</li>
												<li>Another action is delete the Privilege by clicking on <img src="<?php echo config_item('assets'); ?>img/help/delete.PNG" alt="delete"> button.</li>
											</ul>
										   </div>
									  </dd>  
									  <dt>Change access privilege</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/userPrivileges_03.PNG"><img src="<?php echo config_item('assets'); ?>img/help/userPrivileges_03.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On the Privilege column, click <img src="<?php echo config_item('assets'); ?>img/help/editPriv.PNG" alt="edit"> button to edit access privilege.</li>
												<li>"Privilege list" will appear, update/edit the privilege by check or unchecked the list.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
											</ul>
										   </div>
									   </dd>  
									  
									</dl>
								  </div>
								</div>
							  </div>
							</div>

							<!-- Inner accordion ends here -->
						  </div>
						</div>
					  </div>
					  <?php } ?>
					  <!-- /Manage Member List -->
					  
					  <!-- Manage Division & Jobs -->
					  <?php if($this->session->userdata('level')=="Admin"){ ?>
					  <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading05">
						  <h4 class="panel-title">
							<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse05" aria-expanded="false" aria-controls="collapse05"><span class="glyphicon glyphicon-plus"></span>
							  Manage Division &amp; Jobs
							</a>
						  </h4>
						</div>
						<div id="collapse05" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading05">
						  <div class="panel-body">
							<!-- Here we insert another nested accordion -->

							<div class="panel-group" id="accordion0502">
							
							  <div class="panel panel-default">
								<div class="panel-heading">
								  <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion0502" href="#collapseInner0501">
									Manage Division
								  </a></h4>
								</div>
								<div id="collapseInner0501" class="panel-collapse collapse in">
								  <div class="panel-body">
									<dl>
									  <dt>Add new division</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/division_01.PNG"><img src="<?php echo config_item('assets'); ?>img/help/division_01.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On the Division page, click <img src="<?php echo config_item('assets'); ?>img/help/addnew.PNG" alt="edit"> button</li>
												<li>"Division Form" will appear, fill the text field according to the title of field.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
											</ul>
										   </div>
									  </dd>  
									  <dt>Change division name</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/division_02.PNG"><img src="<?php echo config_item('assets'); ?>img/help/division_02.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On the Action column, click edit <img src="<?php echo config_item('assets'); ?>img/help/edit.PNG" alt="edit"> button</li>
												<li>"Division Form" will appear, update/edit the text field content.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
												<li>Or click <img src="<?php echo config_item('assets'); ?>img/help/cancel.PNG" alt="cancel"> button to abort the update.</li>
												<li>Another action is delete the Division by clicking on <img src="<?php echo config_item('assets'); ?>img/help/delete.PNG" alt="delete"> button.</li>
											</ul>
										   </div>
									  </dd>  
									  
									</dl>
								  </div>
								</div>
							  </div>
							  
							  <div class="panel panel-default">
								<div class="panel-heading">
								  <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion0502" href="#collapseInner0502">
									Manage Work Classification
								  </a></h4>
								</div>
								<div id="collapseInner0502" class="panel-collapse collapse">
								  <div class="panel-body">
									<dl>
									  <dt>Add new work classification</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/workClasification_01.PNG"><img src="<?php echo config_item('assets'); ?>img/help/workClasification_01.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On work categories page, click <img src="<?php echo config_item('assets'); ?>img/help/addnew.PNG" alt="edit"> button</li>
												<li>"Work classification Form" will appear, fill the text field according to the title of field.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
											</ul>
										   </div>
									  </dd>  
									  <dt>Change work classification name</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/workClasification_02.PNG"><img src="<?php echo config_item('assets'); ?>img/help/workClasification_02.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On the Action column, click edit <img src="<?php echo config_item('assets'); ?>img/help/edit.PNG" alt="edit"> button</li>
												<li>"Work classification Form" will appear, update/edit the text field content.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
												<li>Or click <img src="<?php echo config_item('assets'); ?>img/help/cancel.PNG" alt="cancel"> button to abort the update.</li>
												<li>Another action is inactive work classification by clicking  on <img src="<?php echo config_item('assets'); ?>img/help/disable.PNG" alt="disable"> button and,</li>
												<li>Delete work classification by clicking on <img src="<?php echo config_item('assets'); ?>img/help/delete.PNG" alt="delete"> button.</li>
											</ul>
										   </div>
									  </dd>  
									  
									</dl>
								  </div>
								</div>
							  </div>
							  
							   <div class="panel panel-default">
								<div class="panel-heading">
								  <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion0502" href="#collapseInner0503">
									Manage Work Categories
								  </a></h4>
								</div>
								<div id="collapseInner0503" class="panel-collapse collapse">
								  <div class="panel-body">
									<dl>
									  <dt>Add new work categories</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/workCategories_01.PNG"><img src="<?php echo config_item('assets'); ?>img/help/workCategories_01.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On work categories page, click <img src="<?php echo config_item('assets'); ?>img/help/addnew.PNG" alt="edit"> button</li>
												<li>"Work Categories Form" will appear, fill the text field according to the title of field.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
											</ul>
										   </div>
									  </dd>  
									  <dt>Change work categories name</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/workCategories_02.PNG"><img src="<?php echo config_item('assets'); ?>img/help/workCategories_02.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On the Action column, click edit <img src="<?php echo config_item('assets'); ?>img/help/edit.PNG" alt="edit"> button</li>
												<li>"Work Categories Form" will appear, update/edit the text field content.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
												<li>Or click <img src="<?php echo config_item('assets'); ?>img/help/cancel.PNG" alt="cancel"> button to abort the update.</li>
												<li>Another action is inactive work categories by clicking  on <img src="<?php echo config_item('assets'); ?>img/help/disable.PNG" alt="disable"> button and,</li>
												<li>Delete work categories by clicking on <img src="<?php echo config_item('assets'); ?>img/help/delete.PNG" alt="delete"> button.</li>
											</ul>
										   </div>
									  </dd>  
									  
									</dl>
								  </div>
								</div>
							  </div>
							  
							  <div class="panel panel-default">
								<div class="panel-heading">
								  <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion0502" href="#collapseInner0504">
									Manage Job Code
								  </a></h4>
								</div>
								<div id="collapseInner0504" class="panel-collapse collapse">
								  <div class="panel-body">
									<dl>
									  <dt>Add new job code</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/jobCode_01.PNG"><img src="<?php echo config_item('assets'); ?>img/help/jobCode_01.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On job code page, click <img src="<?php echo config_item('assets'); ?>img/help/addnew.PNG" alt="edit"> button</li>
												<li>"Job Code Form" will appear, fill the text field according to the title of field.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
											</ul>
										   </div>
									  </dd>  
									  <dt>Change job code classification, name &amp; descriptions</dt>
									  <dd class="row">
										  <div class="col-md-6">
											<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/jobCode_02.PNG"><img src="<?php echo config_item('assets'); ?>img/help/jobCode_02.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
										  </div>
										  <div class="col-md-6">
											<h4>instruction :</h4>
											<ul>
												<li>On the Action column, click edit <img src="<?php echo config_item('assets'); ?>img/help/edit.PNG" alt="edit"> button</li>
												<li>"Job Code Form" will appear, update/edit the text field content.</li>
												<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
												<li>Or click <img src="<?php echo config_item('assets'); ?>img/help/cancel.PNG" alt="cancel"> button to abort the update.</li>
												<li>Another action is inactive the job code by clicking  on <img src="<?php echo config_item('assets'); ?>img/help/disable.PNG" alt="disable"> button, and </li>
												<li>Delete the job code by clicking on <img src="<?php echo config_item('assets'); ?>img/help/delete.PNG" alt="delete"> button.</li>
											</ul>
										   </div>
									  </dd>  
									  
									</dl>
								  </div>
								</div>
							  </div>
							  
							</div>

							<!-- Inner accordion ends here -->
						  </div>
						</div>
					  </div>
					  <?php }?>
					  <!-- /Manage Division & Jobs -->
					  
					  <?php if($this->session->userdata('level')=="Admin"){ ?>
					  <!-- Project Calendar -->
					  <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading08">
						  <h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse08" aria-expanded="true" aria-controls="collapse08"><span class="glyphicon glyphicon-plus"></span>
							  Project Calendar
							</a>
						  </h4>
						</div>
						<div id="collapse08" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading08">
						  <div class="panel-body">
							<dl>
							  <dt>View project calendar</dt>
							  <dd class="row">
								  <div class="col-md-6">
									<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/projectCalendar_01.PNG"><img src="<?php echo config_item('assets'); ?>img/help/projectCalendar_01.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
								  </div>
								  <div class="col-md-6">
									<h4>instruction :</h4>
									<ul>
										<li>click <kbd>&gt;</kbd> button to see next month schedule on project calendar</li>
										<li>click <kbd>&lt;</kbd> button to see previous month schedule on project calendar, and</li>
										<li>click <kbd>This month</kbd> button to see this month schedule on project calendar</li>
									</ul>
								   </div>
							   </dd>
							</dl>
						  </div>
						</div>
					  </div>
					  <!-- /Project Calendar -->
					  
					  <!-- Holiday Setting -->
					  <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading09">
						  <h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse09" aria-expanded="true" aria-controls="collapse09"><span class="glyphicon glyphicon-plus"></span>
							  Holiday Setting
							</a>
						  </h4>
						</div>
						<div id="collapse09" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading09">
						  <div class="panel-body">
							<dl>
							  <dt>Add new holiday date</dt>
							  <dd class="row">
								  <div class="col-md-6">
									<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/holidaySetting_02.PNG"><img src="<?php echo config_item('assets'); ?>img/help/holidaySetting_02.PNG" alt="Add new holiday date" class="img-thumbnail"></a><br>
								  </div>
								  <div class="col-md-6">
									<h4>instruction :</h4>
									<ul>
										<li>On Holiday Setting page, click <img src="<?php echo config_item('assets'); ?>img/help/addnew.PNG" alt="edit"> button</li>
										<li>"Holiday Form" will appear, fill the text field with date and holiday name</li>
										<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
									</ul>
								  </div>
							  </dd>
							  <dt>Update holiday data</dt>
							  <dd class="row">
								  <div class="col-md-6">
									<a class="fancybox" href="<?php echo config_item('assets'); ?>img/help/holidaySetting_03.PNG"><img src="<?php echo config_item('assets'); ?>img/help/holidaySetting_03.PNG" alt="Update profile picture" class="img-thumbnail"></a><br>
								  </div>
								  <div class="col-md-6">
									<h4>instruction :</h4>
									<ul>
										<li>On the Action column, click edit <img src="<?php echo config_item('assets'); ?>img/help/edit.PNG" alt="edit"> button</li>
										<li>"Holiday Form" will appear, update/edit the text field content.</li>
										<li>Click <img src="<?php echo config_item('assets'); ?>img/help/save.PNG" alt="save"> button to complete the process.</li>
										<li>Or click <img src="<?php echo config_item('assets'); ?>img/help/cancel.PNG" alt="cancel"> button to abort the update.</li>
										<li>Another action is Delete the Holiday by clicking on <img src="<?php echo config_item('assets'); ?>img/help/delete.PNG" alt="delete"> button.</li>
									</ul>
								  </div>
							  </dd>
							</dl>
						  </div>
						</div>
					  </div>
					  <!-- /Holiday Setting -->
					  <?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>