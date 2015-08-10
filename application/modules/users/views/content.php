<div class="box">
	<header>
		<h5><i class="fa fa-group"></i> Users</h5>
		<div class="toolbar">
			<div class="btn-group">
				<a class="btn btn-primary btn-sm" onclick="call_modal(0)"><i class="fa fa-plus-circle"></i> Add New</a>
				<a class="btn btn-primary btn-sm" onclick="call_modal_upload()"><i class="fa fa-file-excel-o"></i> Import Data</a>
			</div>
		</div>
	</header>
	<div class="body">
		<div class="row">
			<div class="col-lg-12">
			<div class="body">
				<div role="tabpanel">
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active" ><a href="#users">Users</a></li>
						<li role="presentation"><a href="<?php echo site_url('privileges'); ?>" id="privileges_tab">Privileges</a></li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="privileges">
							<div id="collapse4" class="body">
								<table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
									<thead>
										<tr>
											<th>NIP</th>
											<th>Name</th>
											<th>Level</th>
											<th>Status</th>
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