<div class="box">
  	<header>
		<h5><i class="fa fa-cogs"></i> Groups & Privileges</h5>
		<div class="toolbar">
			<div class="btn-group">
			    <a class="btn btn-primary btn-sm" onclick="call_modal(0)"><i class="fa fa-plus-circle"></i> Add New</a>
			</div>
		</div>
	</header>
	<div class="body">
		<div class="row">
			<div class="body">
				<div role="tabpanel">
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" ><a href="<?php echo site_url('users'); ?>">Users</a></li>
						<li role="presentation" class="active"><a href="#privileges" id="privileges_tab">Privileges</a></li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="privileges">
							<div class="col-lg-12">
								<div id="collapse4" class="body">
									<table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
										<thead>
											<tr>
												<th>Level</th>
												<th>Privileges</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>