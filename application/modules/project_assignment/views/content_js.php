<script src="<?php echo config_item('assets'); ?>js/jquery.dataTables.js"></script>
<script src="<?php echo config_item('assets'); ?>js/dataTables.bootstrap.js"></script>
<script src="<?php echo config_item('assets'); ?>js/chart.js"></script>
<script src="<?php echo config_item('assets'); ?>js/moment.js"></script>
<script src="<?php echo config_item('assets'); ?>js/chartlegend.js"></script>
<script>
	//datatables
	$(document).ready(function () {
		oTable = $('#jobCodes').dataTable({
            'bPaginate': false,
           	'sAjaxSource': '<?php  if(isset($source)) echo $source; ?>',
            'fnDrawCallback': function (settings) {
                $('[title]').tooltip();
				var totalPlan = 0;
				var totalActual = 0;
				var totalLocal = 0;
				var totalOffshore = 0;
				
				for (var i=0, iLen=settings.aiDisplay.length; i<iLen; i++)
                {	
					
					var date = moment(); //Get the current date
					date.format("YYYY-MM-DD"); //2014-07-10
					var date3 = moment().add(3, 'days').startOf('day');
					
					var enddate  = $('td:eq(5)', settings.aoData[settings.aiDisplay[i]].nTr).text();
					var pstat  = $('td:eq(12)', settings.aoData[settings.aiDisplay[i]].nTr).find('button.status').data('status');
					var status  = $('td:eq(12)', settings.aoData[settings.aiDisplay[i]].nTr).find('button.status').data('status');
					var hour  = $('td:eq(9)', settings.aoData[settings.aiDisplay[i]].nTr).html();
					var a  = $('td:eq(11)', settings.aoData[settings.aiDisplay[i]].nTr).html();
					var b  = $('td:eq(10)', settings.aoData[settings.aiDisplay[i]].nTr).html();
					if(a==" Hour"){
						$('td:eq(11)', settings.aoData[settings.aiDisplay[i]].nTr).html("0 Hour");
					}
					if(b==" Hour"){
						$('td:eq(10)', settings.aoData[settings.aiDisplay[i]].nTr).html("0 Hour");
					}
					var new_hour = parseFloat(hour) || (0).toFixed(2);
					$('td:eq(9)', settings.aoData[settings.aiDisplay[i]].nTr).html(new_hour);
					
					if ((enddate < date.format("YYYY-MM-DD")) && pstat !=1){
						$('td:eq(12)', settings.aoData[settings.aiDisplay[i]].nTr).closest('tr').addClass("overdue");
					}else if ((enddate < date3.format("YYYY-MM-DD")) && pstat !=1){
						$('td:eq(12)', settings.aoData[settings.aiDisplay[i]].nTr).closest('tr').addClass("pwarning");
					}
					
					
					if(status == 0){
						$('td:eq(12)', settings.aoData[settings.aiDisplay[i]].nTr).find('button.status').html("Not Yet Start");
						$('td:eq(12)', settings.aoData[settings.aiDisplay[i]].nTr).find('button.status').addClass("btn-warning");
					}else if(status == 1){
						$('td:eq(12)', settings.aoData[settings.aiDisplay[i]].nTr).find('button.status').html("Complete");
						$('td:eq(12)', settings.aoData[settings.aiDisplay[i]].nTr).find('button.status').addClass("btn-success");
					}else if(status == 2){
						$('td:eq(12)', settings.aoData[settings.aiDisplay[i]].nTr).find('button.status').html("On going");
						$('td:eq(12)', settings.aoData[settings.aiDisplay[i]].nTr).find('button.status').addClass("btn-info");
					}else if(status == 3){
						$('td:eq(12)', settings.aoData[settings.aiDisplay[i]].nTr).find('button.status').html("Pending");
						$('td:eq(12)', settings.aoData[settings.aiDisplay[i]].nTr).find('button.status').addClass("btn-danger");
					}else if(status == 4){
						$('td:eq(12)', settings.aoData[settings.aiDisplay[i]].nTr).find('button.status').html("Overdue");
						$('td:eq(12)', settings.aoData[settings.aiDisplay[i]].nTr).find('button.status').addClass("btn-danger");
						
					}
					var str_plan  = $('td:eq(10)', settings.aoData[settings.aiDisplay[i]].nTr).html();
					var str_actual  = $('td:eq(11)', settings.aoData[settings.aiDisplay[i]].nTr).html();
					var str_classification  =$('td:eq(3)', settings.aoData[settings.aiDisplay[i]].nTr).html();
					var plan = str_plan.replace(" Hour", "");
					if(plan=="")(plan=0);
					totalPlan = totalPlan+parseFloat(plan);
					
					var actual = str_actual.replace(" Hour", "");
					if(actual=="")(actual=0);
					totalActual = totalActual+parseFloat(actual);
					
					if(str_classification.toString()=="OffshoreProject"){
						totalOffshore+=1;
					}else if(str_classification.toString()=="LocalProject"){
						totalLocal+=1;
					}
                }
				$("#footer-classification").html("Total Offshore : "+totalOffshore+"</br>Total Local : "+totalLocal);
				$("#footer-plan").html("Sum of Plan : "+totalPlan);
				$("#footer-actual").html("Sum of Actual : "+totalActual);
            },
            'fnFooterCallback': function (nFoot, aaData, iStart, iEnd, aiDisplay) {
				/*
				for (var i=0, iLen=aiDisplay.length; i<iLen; i++)
                {
					
				
				}					
				$("#footer-classification").html("Total Offshore : "+totalOffshore+"</br>Total Local : "+totalLocal);
				$("#footer-plan").html("Sum of Plan : "+totalPlan);
				$("#footer-actual").html("Sum of Actual : "+totalActual);
				*/
            }
        });
		
	});

	//call modal form
	function call_modal(id) {
		$.ajax({
			type: 'POST',
			url: '<?php echo site_url('project_assignment/call_form'); ?>/'+id,
			success: function(data) {
				$('#myModal').html(data).modal('show');
			}
		});
		
		return false;
	}
	
	
	function call_modal_status(id) {
		$.ajax({
			type: 'POST',
			url: '<?php echo site_url('project_assignment/call_status'); ?>/'+id,
			success: function(data) {
				$('#myModal').html(data).modal('show');
			}
		});
		
		return false;
	}
</script>