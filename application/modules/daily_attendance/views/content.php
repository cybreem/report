<div class="box">
	<header>
		<h5><i class="fa fa-clock-o"></i> Daily Attendance</h5>
		<div class="toolbar">
		
			<?php if($this->session->userdata('level')=="Admin") { ?>
            <div class="btn-group">
				<input type="hidden" id="month" />
				<a class="btn btn-primary btn-sm" onclick="makeRequest()"><i class="fa fa-download"></i> Export to Excel</a>               
            </div>
			<?php } ?>
        </div>
	</header>
	<div class="body dailyAttendance">
		<div class="row">
			<div id="calendar" class="col-lg-12"></div>
		</div>
		<div class="row">
			<br>
			<div class="text-center" id="summary">
			  
              
            </div>
			
		</div>
	</div>
</div>