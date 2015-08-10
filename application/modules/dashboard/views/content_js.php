<script src="<?php echo config_item('assets'); ?>js/jquery.dataTables.js"></script>
<script src="<?php echo config_item('assets'); ?>js/dataTables.bootstrap.js"></script>
<script src="<?php echo config_item('assets'); ?>js/datepicker.js"></script>
<script src="<?php echo config_item('assets'); ?>js/chart.js"></script>
<script src="<?php echo config_item('assets'); ?>js/chartlegend.js"></script>
<script src="<?php echo config_item('assets'); ?>js/chosen.jquery.min.js"></script>
<script>
	<?php
		if($modal&&$this->session->userdata('level') != 'Admin'){
		?>
		$.ajax({
			type: 'POST',
			url: '<?php echo site_url('dashboard/login_modal'); ?>',
			success: function(data){
				$('#myModal-sm').html(data).modal({
					backdrop:'static',
					keyboard:false
				});
			}
		});
		<?php
		}
	?>
</script>
<script>	
	/*==============================
		Attendance Chart
	==============================*/
function refresh_list(element){
			$.ajax({
				url: 'dashboard/'+element.data('url'),
				success: function(data){
					element.html(data);
				}
			});
		}
	$(document).ready(function() {
		refresh_list($(this));
		$.each($('.get_list'),function(key,element){
			refresh_list($(this));
		});
		

		
		
		$('.attendance_form').datepicker({
			onRender: function(date){
				var a = new Date(date.valueOf());
				var day = a.getDay();
				return day != 1 ? 'disabled' : '';
				
			}
		}).bind('changeDate clearDate',function(){
			refresh_table_att();
			generate_chart_attendance();				
		});
		
	});
	
	
	
	/*==============================
		End Attendance Chart
	==============================*/
	
	$.ajax({
            type: "POST",
            url: "<?php echo site_url('dashboard/get_holiday'); ?>",
            success: function(data) {
            	
            	tt = $.parseJSON(data);
            	
            	$.each(tt, function(i, val){
            		var holiday_date = [];
					var holiday_desc= [];
					holiday_date +=val.date; 
					holiday_desc +=val.holiday_desc;
					//console.log(holiday_date, holiday_desc);
					         	          		
            	});
            		
            }
            
        });	
	
	<?php
	foreach($check_privileges as $get => $set){
		$id = "workLoad".$get;
		$division = $set;
	?>
    /*==============================
		All / unit Work Load Unit
	==============================*/

/* Datepicker Configuration For All Workload
 ----------------------------------------*/
$('.panel').on('shown.bs.collapse', function (e) {
	<?php echo $id ?>_refresh_workload($('#<?php echo $id ?>picker .from_dt').val(), $('#<?php echo $id ?>picker .to_dt').val());
	var arr = [2015-05-01]
	$('#<?php echo $id ?>picker .from_dt').datepicker({
        format: 'yyyy-mm-dd',
		onRender: function(date){
				var a = new Date(date.valueOf());
				var day = a.getDay();
				return day == 0 || day== 6 ? 'disabled' : '';
					
		}
	}).bind('changeDate',function(e){
        format: 'yyyy-mm-dd';
        $(this).datepicker('hide');
        <?php echo $id ?>_refresh_workload($('#<?php echo $id ?>picker .from_dt').val(), $('#<?php echo $id ?>picker .to_dt').val());
	});
	$('#<?php echo $id ?>picker .to_dt').datepicker({
        format: 'yyyy-mm-dd',
		onRender: function(date){
				var a = new Date(date.valueOf());
				var day = a.getDay();
				return day == 0 || day== 6 ? 'disabled' : '';
				return day == holiday_date ? 'disabled' : '';
			}
	}).bind('changeDate',function(e){
        format: 'yyyy-mm-dd',
        $(this).datepicker('hide');
        <?php echo $id ?>_refresh_workload($('#<?php echo $id ?>picker .from_dt').val(), $('#<?php echo $id ?>picker .to_dt').val());
	});
	
/* Datatables, Plan Time and Actual Chart Configuration For Workload
--------------------------------------------------------------------*/
function <?php echo $id ?>_refresh_workload(fr, tr){
	var from_dt = $('#<?php echo $id ?>picker .from_dt').val();
	var to_dt = $('#<?php echo $id ?>picker .to_dt').val();
	$.getJSON('<?php  if(isset($workload_all)) echo $workload_all."/".$get; ?>', {'from_dt':$('#<?php echo $id ?>picker .from_dt').val(), 'to_dt':$('#<?php echo $id ?>picker .to_dt').val()}, function(json)
	{
		oTable = $('#<?php echo $id ?>').dataTable({
			'aoColumnDefs': [ 
					            {
					                "bSearchable": false,
							        "bVisible": false,
							        "aTargets": [0]
					            },
					            {
					                "bSearchable": false,
							        "bVisible": false,
							        "aTargets": [1]
					            }
					        ],
			'bProcessing': false,
	        'bServerSide': false,
	    	'bLengthChange': false,
	        'bFilter': false,
	        'bSort': false,
	        'bInfo': false,
	        'bPaging': false,
	        'bRetrieve': true,
	        "oLanguage": {
			    "sEmptyTable": "No Sources found currently division workload data",
			},
	        'bRetrieve': true,
			'fnDrawCallback': function ( oSettings) {
				for (var i=0, iLen=oSettings.aiDisplay.length; i<iLen; i++)
				{
					var status  = $('td:eq(3)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html();
					var status2  = $('td:eq(2)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html();
					var status3  = $('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html();
					if(status == " hours"){
						$('td:eq(3)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html("0 hour");
					}
					if(status2 == " hours"){
						$('td:eq(2)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html("0 hour");
					}
					if(status3 == " hours"){
						$('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html("0 hour");
					}
				}
			}
		});
		oSettings = oTable.fnSettings();
		oTable.fnClearTable(this);
		for (var i=0; i<json.aaData.length; i++)
		{
		  oTable.oApi._fnAddData(oSettings, json.aaData[i]);
		}

		oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
		oTable.fnDraw();
		var sum = 0;
		var sData = oTable.fnGetData();
		var chartplan = new Array();
		$.each(sData, function(i, val) {
		   var i
		   var colorArray = ['#E74C3C', '#F62459', '#BF55EC', '#4183D7', '#19B5FE', '#87D37C', '#03C9A9', '#FDE3A7', '#F27935', '#6C7A89', '#BDC3C7'];
		   chartplan.push({
		   		value:parseFloat(val[3]) || 0,
		        color: colorArray[i],
		        highlight: '#89C4F4',
		        label: val[2]})
		   i++
	    });
	    $('#<?php echo $id ?>_plan').remove();
		$('#<?php echo $id ?>_canvas-holder-plan').append('<canvas id="<?php echo $id ?>_plan" width="300" height="300"></canvas>');	
		var ctx = document.getElementById("<?php echo $id ?>_plan").getContext("2d");
		var pieData = chartplan;
		window.myPie = new Chart(ctx).Pie(pieData, {
		    animationSteps : 100,
		    animationEasing : "none",
		    responsive: true,
		    animateRotate : true				
		});	
		window.myPie.update();
		legend(document.getElementById("<?php echo $id ?>_chart_legend"), pieData);
		
		//actual cart
		var charactual = new Array();
		$.each(sData, function(i, val) {
		   var i
		   var colorArray = ['#E74C3C', '#F62459', '#BF55EC', '#4183D7', '#19B5FE', '#87D37C', '#03C9A9', '#FDE3A7', '#F27935', '#6C7A89', '#BDC3C7'];
		   charactual.push({
		   		value:parseFloat(val[4]) || 0,
		        color: colorArray[i],
		        highlight: '#59ABE3',
		        label: val[2]})
		    i++    
	    });	
	    $('#<?php echo $id ?>_actual').remove();
		$('#<?php echo $id ?>_canvas-holder-actual').append('<canvas id="<?php echo $id ?>_actual" width="300" height="300"></canvas>');
		var ctx = document.getElementById("<?php echo $id ?>_actual").getContext("2d");
		var pieData = charactual;
		window.myPie = new Chart(ctx).Pie(pieData, {
		    animationSteps : 100,
		    animationEasing : "none",
		    responsive: true,
		    animateRotate : true				
		});	
		window.myPie.update();
		
		// Calculate Operating Rate
		var from_date = new Date(fr);
		var to_date = new Date(tr);
		if(isNaN(from_date)){
			from_date = new Date();
		}
		if(isNaN(to_date)){
			to_date = new Date();
		}
		var workday = workingDaysBetweenDates(from_date, to_date);
		var gData = rTable.fnGetData();
		var opr_dt = 0;
		var opr_hr = 0;
		var s_hours = '';
		$.each(gData, function(i, val) {
			if(val[1]=="LocalProject" || val[1]=="OffshoreProject"){
				s_hours = parseFloat(val[3].split(" hours")) || 0;
				opr_hr += s_hours;
			}
		});
		if(opr_hr==0){
			opr_dt = 0;
			$('#<?php echo $id ?>_OR h1').html(((opr_dt*100).toFixed(2)) + ' %');
			$('#<?php echo $id ?>_OR h5').html(opr_hr + ' hours ');
		}else{
			$.post("dashboard/get_member_num/<?php echo $get ?>", function( data ) {
				var num_member = data;
				opr_dt = opr_hr / ((num_member) * 8) / (workday);
			$('#<?php echo $id ?>_OR h1').html(((opr_dt*100).toFixed(2)) + ' %');
			$('#<?php echo $id ?>_OR h5').html(opr_hr + ' hours ');
			});
		}	
		
		var from_date = new Date(from_dt);
		var to_date = new Date(to_dt);
		var from_month = ["January", "February", "March", "April", "May", "June",
		"July", "August", "September", "October", "November", "December"][from_date.getMonth()];
		var to_month = ["January", "February", "March", "April", "May", "June",
		"July", "August", "September", "October", "November", "December"][to_date.getMonth()];
		if(from_dt!=""){
		var show_from = 'Showing data from ('+from_month+ ', ' +from_date.getDate()+')';
		}else{
		var show_from = '';
		}
		if(to_dt!=""){
		var show_to = ' to ('+to_month+ ', ' +to_date.getDate()+')';
		}else{
		var show_to = '';
		}
		
		$('#<?php echo $id ?>_all').html(show_from+show_to);
		
		});
	
	$.getJSON('<?php  if(isset($workload_all_detail)) echo $workload_all_detail."/".$get; ?>', {'from_dt':fr, 'to_dt':tr}, function(json)
	{
		rTable = $('#<?php echo $id ?>_detail').dataTable({
			'aoColumnDefs': [ 
					            {
					                "bSearchable": false,
							        "bVisible": false,
							        "aTargets": [0]
					            }
					        ],
			'bProcessing': false,
	        'bServerSide': false,
	    	'bLengthChange': false,
	        'bFilter': false,
	        'bSort': false,
	        'bInfo': false,
	        'bRetrieve': true,
	        "oLanguage": {
			    "sEmptyTable": "No Sources found currently division workload clasification detail data",
			},
	        'bRetrieve': true,
			'fnDrawCallback': function ( oSettings) {
				for (var i=0, iLen=oSettings.aiDisplay.length; i<iLen; i++)
				{
					var status  = $('td:eq(2)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html();
					var status2  = $('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html();
					if(status == " hours"){
						$('td:eq(2)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html("0 hour");
					}
					if(status2 == " hours"){
						$('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html("0 hour");
					}
				}
			}
		});
		oSettings = rTable.fnSettings();
		rTable.fnClearTable(this);
		for (var i=0; i<json.aaData.length; i++)
		{
		  rTable.oApi._fnAddData(oSettings, json.aaData[i]);
		}

		oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
		rTable.fnDraw();
	});
	
	$('#<?php echo $id ?>_detail_hr').on('shown.bs.tab', function (e) {
		var sData = rTable.fnGetData();
			var chartplan = new Array();
			$.each(sData, function(i, val) {
			   var i
			   var colorArray = ['#E74C3C', '#F62459', '#BF55EC', '#4183D7', '#19B5FE', '#87D37C', '#03C9A9', '#FDE3A7', '#F27935', '#6C7A89', '#BDC3C7'];
			   chartplan.push({
			   		value: parseFloat(val[3].split(" hours")) || 0,
			        color: colorArray[i],
			        highlight: '#89C4F4',
			        label: val[1]})
			   i++
		    });
			
		    $('#<?php echo $id ?>_plan_detail').remove();
			$('#<?php echo $id ?>_canvas-holder-plan_detail').append('<canvas id="<?php echo $id ?>_plan_detail" width="300" height="300"></canvas>');
			
			var ctx = document.getElementById("<?php echo $id ?>_plan_detail").getContext("2d");
			var pieData = chartplan;
			//legend(document.getElementById("WRall_plan"), data);
			window.myPie = new Chart(ctx).Pie(pieData, {
			    animationSteps : 100,
			    animationEasing : "none",
			    animateRotate : true				
			});	
			window.myPie.update();
			legend(document.getElementById("<?php echo $id ?>_chart_detail_legend"), pieData);
			
			
			
	});
	
	$.getJSON('<?php  if(isset($workload_all_detail2)) echo $workload_all_detail2."/".$get; ?>', {'from_dt':fr, 'to_dt':tr}, function(json)
	{
		rTable2 = $('#<?php echo $id ?>_detail2').dataTable({
			'bProcessing': false,
	        'bServerSide': false,
	    	'bLengthChange': false,
	        'bFilter': false,
	        'bSort': false,
	        'bInfo': false,
	        'bRetrieve': true,
	        "oLanguage": {
			    "sEmptyTable": "No Sources found currently division workload job detail data",
			},
	        'bRetrieve': true,
			'fnDrawCallback': function ( oSettings) {
				for (var i=0, iLen=oSettings.aiDisplay.length; i<iLen; i++)
				{
					var status  = $('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html();
					var status2  = $('td:eq(2)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html();
					if(status == " hours"){
						$('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html("0 hour");
					}
					if(status2 == " hours"){
						$('td:eq(2)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html("0 hour");
					}
				}
			}
		});
		oSettings = rTable2.fnSettings();
		rTable2.fnClearTable(this);
		for (var i=0; i<json.aaData.length; i++)
		{
		  rTable2.oApi._fnAddData(oSettings, json.aaData[i]);
		}

		oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
		rTable2.fnDraw();
	});
	$('#<?php echo $id ?>_detail_hr').on('shown.bs.tab', function (e) {
		var sData = rTable2.fnGetData();
			var chartplan = new Array();
			$.each(sData, function(i, val) {
			   var i
			   var colorArray = ['#E74C3C', '#F62459', '#BF55EC', '#4183D7', '#19B5FE', '#87D37C', '#03C9A9', '#FDE3A7', '#F27935', '#6C7A89', '#BDC3C7'];
			   chartplan.push({
			   		value: parseFloat(val[2].split(" hours")) || 0,
			        color: colorArray[i],
			        highlight: '#89C4F4',
			        label: val[0]})
			   i++
		    });
		    $('#<?php echo $id ?>_actual_detail').remove();
			$('#<?php echo $id ?>_canvas-holder-actual_detail').append('<canvas id="<?php echo $id ?>_actual_detail" width="300" height="300"></canvas>');
			
			var ctx = document.getElementById("<?php echo $id ?>_actual_detail").getContext("2d");
			var pieData = chartplan;
			window.myPie = new Chart(ctx).Pie(pieData, {
			    animationSteps : 100,
			    animationEasing : "none",
			    animateRotate : true				
			});	
			window.myPie.update();
			legend(document.getElementById("<?php echo $id ?>_chart_detail2_legend"), pieData);
			
	});

	
}	
});
	/*==============================
		End All / Unit Work Load Unit
	==============================*/
	
	<?php
	}
	?>
    /*==============================
		Individual Unit
	==============================*/
	
	
/* Datepicker Configuration For Individual
 ----------------------------------------*/

$(document).ready(function(){
	oTable = $('#individual_table').dataTable({
			'bProcessing': false,
	        'bServerSide': false,
	    	'bLengthChange': false,
	        'bFilter': false,
	        'bSort': false,
	        'bInfo': false,
	        'bPaging': false,
	        'bRetrieve': true,
			'fnDrawCallback': function ( oSettings) {
				for (var i=0, iLen=oSettings.aiDisplay.length; i<iLen; i++)
				{
					var status  = $('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html();
					var status2  = $('td:eq(2)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html();
					var status3  = $('td:eq(3)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html();
					if(status == " hours"){
						$('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html("0 hour");
					}
					if(status2 == " hours"){
						$('td:eq(2)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html("0 hour");
					}
					if(status3 == " hours"){
						$('td:eq(3)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html("0 hour");
					}
				}
			}
		});
	refresh_individual();
	inTable();
	detail2();
	$('#individual_date .from_dt').datepicker({
		format:'yyyy-mm-dd',
	    daysOfWeekDisabled: "0,6",
	    autoclose: true
	}).bind('changeDate',function(){
		refresh_individual();
		inTable();
		detail2();
	});
	$('#individual_date .to_dt').datepicker({
		format:'yyyy-mm-dd',
	    daysOfWeekDisabled: "0,6",
	    autoclose: true
	}).bind('changeDate',function(){
		refresh_individual();
		inTable();
		detail2();
	});
});
	
/* Datatables, Plan Time and Actual Chart Configuration For Individual
--------------------------------------------------------------------*/

function refresh_individual(){
	var id_user = $("#cumcum").val();
	var from_dt = $('#individual_date .from_dt').val();
	var to_dt = $('#individual_date .to_dt').val();
	$.getJSON('<?php echo site_url('dashboard/individual_content'); ?>/'+id_user, {'from_dt':$('#individual_date .from_dt').val(), 'to_dt':$('#individual_date .to_dt').val()}, function( json )
	{
		oTable = $('#individual_table').dataTable({
				'bPaginate': false,
				'bLengthChange': false,
				'bFilter': false,
				'bSort': false,
				'bInfo': false,
				'bRetrieve': true,
				"oLanguage": {
			    "sEmptyTable": "No Sources found currently user individual workload data",
			},
		});
		oTable = $('#individual_table').dataTable({
			'bProcessing': false,
	        'bServerSide': false,
	    	'bLengthChange': false,
	        'bFilter': false,
	        'bSort': false,
	        'bInfo': false,
	        'bPaging': false,
	        'bRetrieve': true,
			'fnDrawCallback': function ( oSettings) {
				for (var i=0, iLen=oSettings.aiDisplay.length; i<iLen; i++)
				{
					var status  = $('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html();
					var status2  = $('td:eq(2)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html();
					var status3  = $('td:eq(3)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html();
					if(status == " hours"){
						$('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html("0 hour");
					}
					if(status2 == " hours"){
						$('td:eq(2)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html("0 hour");
					}
					if(status3 == " hours"){
						$('td:eq(3)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html("0 hour");
					}
				}
			}
		});
		oSettings = oTable.fnSettings();

		oTable.fnClearTable(this);

		for (var i=0; i<json.aaData.length; i++)
		{
		  oTable.oApi._fnAddData(oSettings, json.aaData[i]);
		}

		oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
		oTable.fnDraw();
		var sum = 0;
		var sData = oTable.fnGetData();
		var chartplan = new Array();
		$.each(sData, function(i, val) {
			var i;
			var colorArray = ['#E74C3C', '#F62459', '#BF55EC', '#4183D7', '#19B5FE', '#87D37C', '#03C9A9', '#FDE3A7', '#F27935', '#6C7A89', '#BDC3C7'];
		   chartplan.push({
		   		value:parseFloat(val[1]),
		        color: colorArray[i],
		        highlight: '#89C4F4',
		        label: val[0]})
		   i++
	    });
	    $('#individual_plan').remove();
		$('#canvas-holder-individual-plan').append('<canvas id="individual_plan" width="300" height="300"></canvas>');	
		var ctx = document.getElementById("individual_plan").getContext("2d");
		var pieData = chartplan;
		//legend(document.getElementById("WRall_plan"), data);
		window.myPie = new Chart(ctx).Pie(pieData, {
		    animationSteps : 100,
		    animationEasing : "none",
		    responsive: true,
		    animateRotate : true				
		});	
		window.myPie.update();
		
		//actual cart
		var charactual = new Array();
		$.each(sData, function(i, val) {
			var i;
			var colorArray = ['#E74C3C', '#F62459', '#BF55EC', '#4183D7', '#19B5FE', '#87D37C', '#03C9A9', '#FDE3A7', '#F27935', '#6C7A89', '#BDC3C7'];
		   charactual.push({
		   		value:parseFloat(val[2]),
		        color: colorArray[i],
		        highlight: '#59ABE3',
		        label: val[0]})
		    i++    
	    });	
	    $('#individual_actual').remove();
		$('#canvas-holder-individual-actual').append('<canvas id="individual_actual" width="300" height="300"></canvas>');	
		var ctx = document.getElementById("individual_actual").getContext("2d");
		var pieData = charactual;
		window.myPie = new Chart(ctx).Pie(pieData, {
		    animationSteps : 100,
		    animationEasing : "none",
		    responsive: true,
		    animateRotate : true				
		});	
		window.myPie.update();
		legend(document.getElementById("chart_legend_individual"), pieData);
	
		
		var from_date = new Date(from_dt);
		var to_date = new Date(to_dt);
		var from_month = ["January", "February", "March", "April", "May", "June",
		"July", "August", "September", "October", "November", "December"][from_date.getMonth()];
		var to_month = ["January", "February", "March", "April", "May", "June",
		"July", "August", "September", "October", "November", "December"][to_date.getMonth()];
		if(from_dt!=""){
		var show_from = 'from ('+from_month+ ', ' +from_date.getDate()+')';
		}else{
		var show_from = '';
		}
		if(to_dt!=""){
		var show_to = ' to ('+to_month+ ', ' +to_date.getDate()+')';
		}else{
		var show_to = '';
		}
		
		$('.showing-individual').html("Showing data "+show_from+show_to);
	});
}	
	function inTable(){
	var id_user = $("#cumcum").val();
	var from_dt = $('#individual_date .from_dt').val();
	var to_dt = $('#individual_date .to_dt').val();
		$.getJSON('<?php echo site_url('dashboard/individual_detail'); ?>/'+id_user, {'from_dt':$('#individual_date .from_dt').val(), 'to_dt':$('#individual_date .to_dt').val()}, function( json )
		{
		var id_user = $("#cumcum").val();
		var from_dt = $('#individual_date .from_dt').val();
		var to_dt = $('#individual_date .to_dt').val();
		dtIn = $('#individual_detail1').dataTable({
				'bPaginate': false,
				'bLengthChange': false,
				'bFilter': false,
				'bSort': false,
				'bInfo': false,
				'bRetrieve': true,
				"oLanguage": {
			    "sEmptyTable": "No Sources found Your currently Workload Clasification Detail Data",
			},
				'bRetrieve': true,
				'fnDrawCallback': function ( oSettings) {
					for (var i=0, iLen=oSettings.aiDisplay.length; i<iLen; i++)
					{
						var status  = $('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html();
						var status2  = $('td:eq(2)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html();
						if(status == " hours"){
							$('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html("0 hour");
						}
						if(status2 == " hours"){
							$('td:eq(2)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html("0 hour");
						}
					}
				}
		});
		oSettings = dtIn.fnSettings();

		dtIn.fnClearTable(this);

		for (var i=0; i<json.aaData.length; i++)
		{
		  dtIn.oApi._fnAddData(oSettings, json.aaData[i]);
		}
		var from_date = new Date(from_dt);
		var to_date = new Date(to_dt);
		if(isNaN(from_date)){
			from_date = new Date();
		}
		if(isNaN(to_date)){
			to_date = new Date();
		}
		var sData = dtIn.fnGetData();
		var opr_dt = 0;
		var opr_hr = 0;	
		var x = 0;
		var workday = workingDaysBetweenDates(from_date,to_date);
		$.each(sData, function(i, val) {
			if(val[0]=="LocalProject" || val[0]=="OffshoreProject"){
				opr_hr += parseInt((val[2]).split("hour")) || 0;
			}
			x+=1;
		});
		if(opr_hr==0){
			opr_dt = 0;
		}else{
			opr_dt = (opr_hr / 8) / (workday);
		}
		$('#individual_OR h1').html(((opr_dt*100).toFixed(2)) + ' %');
		$('#individual_OR h5').html(opr_hr + ' hours ');
		oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
		dtIn.fnDraw();
		});
	}
	function detail2(){
	var id_user = $("#cumcum").val();
	var from_dt = $('#individual_date .from_dt').val();
	var to_dt = $('#individual_date .to_dt').val();
		$.getJSON('<?php echo site_url('dashboard/individual_detail2'); ?>/'+id_user, {'from_dt':$('#individual_date .from_dt').val(), 'to_dt':$('#individual_date .to_dt').val()}, function( json )
		{
		var id_user = $("#cumcum").val();
		var from_dt = $('#individual_date .from_dt').val();
		var to_dt = $('#individual_date .to_dt').val();
		dtIn2 = $('#individual_detail2').dataTable({
				'bPaginate': false,
				'bLengthChange': false,
				'bFilter': false,
				'bSort': false,
				'bInfo': false,
				'bRetrieve': true,
				"oLanguage": {
			    "sEmptyTable": "No Sources found Your currently Workload Job Detail Data",
			},
				'bRetrieve': true,
				'fnDrawCallback': function ( oSettings) {
					for (var i=0, iLen=oSettings.aiDisplay.length; i<iLen; i++)
					{
						var status  = $('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html();
						var status2  = $('td:eq(2)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html();
						if(status == " hours"){
							$('td:eq(1)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html("0 hour");
						}
						if(status2 == " hours"){
							$('td:eq(2)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html("0 hour");
						}
					}
				}
		});
		oSettings = dtIn2.fnSettings();

		dtIn2.fnClearTable(this);

		for (var i=0; i<json.aaData.length; i++)
		{
		  dtIn2.oApi._fnAddData(oSettings, json.aaData[i]);
		}

		oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
		dtIn2.fnDraw();
		});
	}
	
	function workingDaysBetweenDates(startDate, endDate) {
  
    // Validate input
    if (endDate < startDate)
        return 0;
    
    // Calculate days between dates
    var millisecondsPerDay = 86400 * 1000; // Day in milliseconds
    startDate.setHours(0,0,0,1);  // Start just after midnight
    endDate.setHours(23,59,59,999);  // End just before midnight
    var diff = endDate - startDate;  // Milliseconds between datetime objects    
    var days = Math.ceil(diff / millisecondsPerDay);
    
    // Subtract two weekend days for every week in between
    var weeks = Math.floor(days / 7);
    var days = days - (weeks * 2);

    // Handle special cases
    var startDay = startDate.getDay();
    var endDay = endDate.getDay();
    
    // Remove weekend not previously removed.   
    if (startDay - endDay > 1)         
        days = days - 2;      
    
    // Remove start day if span starts on Sunday but ends before Saturday
    if (startDay == 0 && endDay != 6)
        days = days - 1  
            
    // Remove end day if span ends on Saturday but starts after Sunday
    if (endDay == 6 && startDay != 0)
        days = days - 1  
    
    return days;
}
$(document).ready(function(){	
	var id_user = $("#cumcum").val();
	
});
$("#cumcum").select2({placeholder: "Type Member Name"});
	/*==============================
		End All Individual Unit
	==============================*/
var arr = [ 1, 2, 3, 4, 5 ];
var cumi = $.inArray([1, 2], arr);
console.log(cumi+"cumi");
$(".export").on('click',function(){
	division = $(this).data('division');
	from = $("#workLoad"+division+"picker .from_dt").val();
	to = $("#workLoad"+division+"picker .to_dt").val();
	window.open("dashboard/export/"+division+"/"+from+"/"+to, '_blank');
});

</script>

<script>
	window.onload = function() {		
		$.ajax({
            type: "POST",
            url: "<?php  if(isset($global_chart)) echo $global_chart; ?>",
            dataType: 'json',
            success: function(data) {  
            	var ctx = document.getElementById("global_line_chart").getContext("2d");
            	var myData = data;
            	//var fill_trans = ['rgba(214, 69, 65, 0.2)','rgba(102, 51, 153, 0.2)','rgba(65, 131, 215, 0.2)','rgba(38, 166, 91, 0.2)','rgba(211, 84, 0, 0.2)'];
            	//var stroke = ['rgba(214, 69, 65, 1)','rgba(102, 51, 153, 1)','rgba(65, 131, 215, 1)','rgba(38, 166, 91, 1)','rgba(211, 84, 0, 1)'];
            	var lineChartData = {
            		labels : myData.labels,
            		datasets : myData.data

            	};
            	console.log(myData);
				window.myLine = new Chart(ctx).Line(lineChartData,{
					responsive: true,
					multiTooltipTemplate: function(valuesObject){
								  console.log(valuesObject);
								  return valuesObject.datasetLabel+" : "+valuesObject.value+" hours";
					}
				});		
            }
        });
	};
</script>
