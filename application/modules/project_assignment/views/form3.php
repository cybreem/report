<script>
	//call modal form
	function call_modal(id) {
		$('#myModal').modal('hide');
		setTimeout(function(){
			$.ajax({
				type: 'POST',
				url: '<?php echo site_url('project_assignment/call_form'); ?>/'+id,
				success: function(data) {
					$('#myModal').html(data).modal('show');
				}
			});
			
			return false;
		},1000);
	}
</script>
<div class="modal-dialog modal-md">
	<div class="modal-content">
	<?php
	$total_plan = 0;
	$total_actual = 0;
		foreach($list as $set_work => $data_work){
		}
	?>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				&times;
			</button>
			<h4 class="modal-title"> <i class="fa fa-briefcase"></i> <?php echo $data_work['name'] ?> Details Assignment for <?php echo $data_work['category'] ?></h4>
		</div>
		<div class="modal-body">
		<?php
		$count = count($list);
		if($count>0){
		?>
		<div class="col-lg-12">
				<div class="form-group">
					<table class="table table-hover table-striped">
					<thead>
						<tr class="danger">
							<td class="text-left"><strong>Details</strong></td>
							<td><strong>Plan Time</strong></td>
							<td><strong>Actual Time</strong></td>
						</tr>
					</thead>
					<tbody>
					<?php
		foreach($list as $set_work => $data_work){
						$plan_time =  $data_work['plan_time']!=""?$data_work['plan_time']:0;
						$actual_time =  $data_work['actual_time']!=""?$data_work['actual_time']:0;
						$total_plan = $total_plan+$plan_time;
						$total_actual = $total_actual+$actual_time;
						?>
						<tr class="success">
							<td class="text-left"><?php  echo $data_work['description'] ?></td>
							<td><?php  echo $plan_time ?></td>
							<td><?php  echo $actual_time ?></td>
						</tr>
		<?php 
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
			<div class="clearfix"></div>
		<?php
		}else{
			echo "<h4>No data available in table</h4>";
		}
		?>
			
		</div>
		<div class="modal-footer">
			<button class="btn btn-info" onClick="call_modal(<?php echo $id_job ?>)">Back</button>
		</div>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->