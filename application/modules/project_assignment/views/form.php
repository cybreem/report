<script>
	$(document).ready(function () {
		id = $("#job_id").val();
		$.getJSON('<?php echo site_url('project_assignment/get_chart'); ?>/'+id, function( json )
		{
			oTable = $('#chart_table'
			).dataTable({
			
			'bProcessing': false,
	        'bServerSide': false,
	    	'bLengthChange': false,
	        'bFilter': false,
	        'bSort': false,
	        'bInfo': false,
	        'bPaginate': false,
	        'bRetrieve': true
			});
			oSettings = oTable.fnSettings();
	
			oTable.fnClearTable(this);
	
			for (var i = 0; i < json.aaData.length; i++) {
				oTable.oApi._fnAddData(oSettings, json.aaData[i]);
			}
	
			oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
			oTable.fnDraw();
			})
	});
	$('#myModal').on('shown.bs.modal', function(event) {
		var sData = oTable.fnGetData();
		var chartplan = new Array();
		$.each(sData, function(i, val) {
			var i;
			var colorArray = ['#E74C3C', '#F62459', '#BF55EC', '#4183D7', '#19B5FE', '#87D37C', '#03C9A9', '#FDE3A7', '#F27935', '#6C7A89', '#BDC3C7'];
			chartplan.push({
				value : parseFloat(val[1]) || 0,
				color : colorArray[i],
				highlight : '#89C4F4',
				label : val[0]
			})
			i++
		});
		var ctx = document.getElementById("chart-plan").getContext("2d");
		var pieData = chartplan;
		window.myPie = new Chart(ctx).Pie(pieData, {
			animationSteps : 100,
			animationEasing : "none",
			responsive : true,
			animateRotate : true
		});
		window.myPie.update();

		//actual cart
		var chartactual = new Array();
		$.each(sData, function(i, val) {
			var i;
			var colorArray = ['#E74C3C', '#F62459', '#BF55EC', '#4183D7', '#19B5FE', '#87D37C', '#03C9A9', '#FDE3A7', '#F27935', '#6C7A89', '#BDC3C7'];
			chartactual.push({
				value : parseFloat(val[2]) || 0,
				color : colorArray[i],
				highlight : '#59ABE3',
				label : val[0]
			})
			i++
		});
		var ctx = document.getElementById("chart-actual").getContext("2d");
		var pieData = chartactual;
		window.myPie = new Chart(ctx).Pie(pieData, {
			animationSteps : 100,
			animationEasing : "none",
			responsive : true,
			animateRotate : true
		});
		window.myPie.update();
		legend(document.getElementById("chart-legend"), pieData);
	}); 
	function openDetail(id_user,category,id_job)
	{
		$('#myModal').modal('hide');
		setTimeout(function(){
			$.ajax({
				type: 'POST',
				url: '<?php echo site_url('project_assignment/call_detail'); ?>/'+id_user+'/'+category+'/'+id_job,
				success: function(data) {
					$('#myModal').html(data).modal('show');
				}
			});
			
			return false;
		},1000);
	}
</script>
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<input type="hidden" value="<?php echo $id_job ?>" id="job_id" />
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				&times;
			</button>
			<h4 class="modal-title"><i class="fa fa-briefcase"></i> Job Breakdown for <?php echo $job->project_mng_code ?></h4>
		</div>
		<div class="modal-body">
		<?php
		$count = count($list);
		if($count>0){
		?>
		<div class="col-lg-6">
				<div class="form-group">
					<table class="table table-hover table-striped">
					<thead>
						<tr class="danger">
							<td class="text-left"><strong>Member Name</strong></td>
							<td><strong>Plan Time</strong></td>
							<td><strong>Actual Time</strong></td>
						</tr>
					</thead>
					<tbody>
					<?php
					$total_plan = 0;
					$total_actual = 0;
					foreach($list as $set => $data){
						$id_user =  $data['id_user'];
						$name =  $data['name'];
						$plan_time =  $data['plan_time']!=""?$data['plan_time']:0;
						$actual_time =  $data['actual_time']!=""?$data['actual_time']:0;
						
						$total_plan = $total_plan+$plan_time;
						$total_actual = $total_actual+$actual_time;
						?>
						<tr class="info">
							<td class="text-left"><strong><?php echo $name ?></strong></td>
							<td><strong><?php echo $plan_time ?> Hour</strong></td>
							<td><strong><?php echo $actual_time ?> Hour</strong></td>
						</tr>
						<?php
							foreach(${'work_'.$id_user} as $set_work => $data_work){
							?>
							<tr class="success">
								<td class="text-left"><a href="#SeeDetail" onClick="openDetail(<?php echo $id_user ?>,'<?php echo str_replace(' ', '',$data_work['category']) ?>',<?php echo $id_job ?>)"><i class="fa fa-plus"></i> <?php  echo $data_work['category'] ?></a></td>
								<td><?php  echo $data_work['total_plan'] ?></td>
								<td><?php  echo $data_work['total_actual'] ?></td>
							</tr>
							<?php 
							}
					}
					?>
					</tbody>
					<tfoot>
						<td><strong>Total</strong></td>
						<td><?php echo $total_plan." Hour" ?></td>
						<td><?php echo $total_actual." Hour" ?></td>
					</tfoot>
					</table>
				</div>
			</div>
			
			<div class="col-lg-6">
				<div class="col-lg-12">
					<div id="canvas-holder-plan" class="col-lg-6 text-center">
						<canvas id="chart-plan" width="250" height="250"></canvas>
						<strong>Plan</strong>
					</div>
					<div id="canvas-holder-actual" class="col-lg-6 text-center">
						<canvas id="chart-actual" width="250" height="250"></canvas>
						<strong>Actual</strong>
					</div>
				</div>
				<div class="col-lg-12">
					<div class="box">
						<div class="body">
							<div id="chart-legend"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<table id="chart_table" class="table table-bordered table-condensed table-hover table-striped hide">
					<thead>
						<tr>
							<th>Job Category</th>
							<th>Plan Time</th>
							<th>Actual Time</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>
			<div class="clearfix"></div>
		<?php
		}else{
			echo "<h4>No data available in table</h4>";
		}
		?>
			
		</div>
		<div class="modal-footer">
			<button class="btn btn-default" data-dismiss="modal">Cancel</button>
		</div>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->