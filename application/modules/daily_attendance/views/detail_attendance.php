<div class="modal-dialog modal-m">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				&times;
			</button>
			<h4 class="modal-title"><i class="fa fa-edit"></i> Attendance Report</h4>
		</div>
		<div class="modal-body">
			<table id="attendance_detail" class="table table-bordered table-condensed table-hover table-striped">
				<thead>
				
				
					<tr>
					<?php 
					foreach($header as $key => $value){
					?>
						<th><?php echo $value ?></th>
						
					<?php } ?>
					</tr>
				
					
				</thead>
				<tbody>

				</tbody>
			</table>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			
		</div>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

