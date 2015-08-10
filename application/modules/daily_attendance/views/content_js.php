<script src="<?php echo config_item('assets'); ?>js/moment.js"></script>
<script src="<?php echo config_item('assets'); ?>js/fullcalendar.js"></script>
<script src="<?php echo config_item('assets'); ?>js/timeline.js"></script>
<script src="<?php echo config_item('assets'); ?>js/jquery.dataTables.js"></script>
<script src="<?php echo config_item('assets'); ?>js/dataTables.bootstrap.js"></script>
<!--<script src="<?php //echo config_item('assets'); ?>js/moment.js"></script>-->
<script>
	
	$(function(){
		
		//////////////////////
		$('#calendar').fullCalendar({
			now: new Date(),
			weekends:false,                
			editable:false,
			contentHeight:'auto',
			header: {
				left: '',
				center: 'title',
				right: 'today, prev, next'
			},
			defaultView: 'timelineMonth',
			resourceAreaWidth: '15%',
			resourceLabelText: 'Status',
			resources: function(callback){
				$.ajax({
					url: "<?php echo site_url('daily_attendance/report')?>",
					type: 'POST',
					datatype:'json',
					success: function (data) {
						jsonData = $.parseJSON(data);   
						sData = jsonData.title;     
						xData = jsonData.data;       
						t = [];
						r = [];
						$.each(sData, function(i, val) {
							t.push({
								id:val.id_status,
								title:val.status
							}); 
						});
						$.each(xData, function(i, val) {
							r.push({
								id:val.id_status,
								resourceId:val.id_status,
								start: val.date,
								end: val.date,
								title:val.ct
							}); 
						}); 
						callback(t);
					}
				});
			},
			events:
			
			function(start, end,timezone, callback){
				$('.fc-total-count').remove();
				$('#calendar').find(".fc-head > tr").append('<td class="fc-total-count"><b>Total</b></td>');
				$('#calendar').find(".fc-body > tr").append('<td class="fc-total-count"><div class="fc-rows"><table></table></div></td>');
				 $.ajax({
					url: "<?php echo site_url('daily_attendance/report')?>",
					type: 'POST',
					datatype:'json',
					data:{
						start: start.unix(),
						end: end.unix()
					},
					success: function (data) {
						jsonData = $.parseJSON(data);   
						sData = jsonData.title;     
						xData = jsonData.data;       
						t = [];
						r = [];
						$.each(sData, function(i, val) {
							t.push({
								id:val.id_status,
								title:val.status
							}); 
						});
						$.each(xData, function(i, val) {
							r.push({
								id:val.id_status,
								resourceId:val.id_status,
								start: val.dateu,
								end: val.dateu,
								title:val.ct
							}); 
						}); 
						
						callback(r);
						// alert(start);
						// alert(end);
						generate_summary(start.unix(),end.unix());
						generate_total(start.unix(),end.unix());
						
					}
				 });
			},
			eventRender: function(event, element) {
				date = $('#calendar').fullCalendar("getDate").format();
				$("#month").val(date.substring(0, 10));
				$(element).find('.fc-content').append('<a class="modal-detail" href="#" data-status="'+event.id+'" data-date="'+event.start+'"></a>');
				$(element).find('.modal-detail').append($(element).find(".fc-title"));
				$(element).find(".fc-time").remove();
				
			},
			viewRender: function(currentView){
				var minDate = moment(),
				maxDate = moment().add(0,'month');
				
				// Future
				if (maxDate >= currentView.start && maxDate <= currentView.end) {
					$(".fc-next-button").prop('disabled', true); 
					$(".fc-next-button").addClass('fc-state-disabled'); 
				} else {
					$(".fc-next-button").removeClass('fc-state-disabled'); 
					$(".fc-next-button").prop('disabled', false); 
				}
				// Past
				//if (minDate >= currentView.start && minDate <= currentView.end) {$(".fc-prev-button").prop('disabled', true);$(".fc-prev-button").addClass('fc-state-disabled'); }
				//else {$(".fc-prev-button").removeClass('fc-state-disabled'); $(".fc-prev-button").prop('disabled', false); }
			}
		});
		function generate_summary(start,end){
			 $.ajax({
				url: "<?php echo site_url('daily_attendance/summary')?>",
				type: 'POST',
				datatype:'json',
				data:{
					start: start,
					end: end
				},
				success: function (data) {
					$('#summary').html(data);
					
				}
			 });
		}
		function generate_total(start,end){
			 $.ajax({
				url: "<?php echo site_url('daily_attendance/getTotal')?>",
				type: 'POST',
				datatype:'json',
				data:{
					start: start,
					end: end
				},
				success: function (data) {
					$('.fc-total-count table').html(data);
					
				}
			 });
			// <tr><td><div style="height: 45px;">10</div></td></tr>
		}
	})
            
       
	$(document).on('click','.modal-detail',function(){
		var _this = $(this);
		$.ajax({
			type: 'POST',
			url: '<?php echo site_url('daily_attendance/view_status'); ?>',
			data: {date:$(this).data('date'),status:$(this).data('status')},
			success: function(data){
				$('#myModal-sm').html(data).modal('show');
				$('#attendance_detail').dataTable({
					'bProcessing': false,
					'bServerSide': false,
					'bPaginate': true,
					'bLengthChange': true,
					'bFilter': false,
					'bSort': false,
					'bInfo': false,
					'sAjaxSource': '<?php  echo site_url('daily_attendance/detail_attendance') ?>',
					'fnServerData': function (sSource, aoData, fnCallback) {
						$.ajax ({
							type : 'POST',
							url : sSource,
							data : aoData,
							dataType : 'json',
							success : fnCallback
						});
					},
					"fnServerParams": function ( aoData ) {
						aoData.push({ "name": "date", "value": _this.data('date') },
									{ "name": "status", "value": _this.data('status') } 
									);
					},
					'fnDrawCallback': function ( oSettings) {
						$('[title]').tooltip();
					}
				});
			}
		});
	});
	function makeRequest(){
		var date = $("#month").val();
		window.open(
		 'daily_attendance/export_excel/'+date,
		  '_blank'
		);
	}
</script>
<style>
	.fc-event{
		font-size:inherit;
		line-height:2.3;
		border:none;
		background-color:transparent;
	}
</style>