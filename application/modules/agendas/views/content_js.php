<script src="<?php echo config_item('assets'); ?>js/moment.js"></script>
<script src="<?php echo config_item('assets'); ?>js/fullcalendar.js"></script>
<script src="<?php echo config_item('assets'); ?>js/timeline.js"></script>
<script>
    $.ajax({
        url: "<?php echo site_url('agendas/project')?>",
        type: 'POST',
        datatype:'json',
        success: function (data) {
            jsonData = $.parseJSON(data);   
            sData = jsonData.member;     
            xData = jsonData.project;       
            console.log(xData);  
            mebers = [];
            projects = [];
            $.each(sData, function(i, val) {
                mebers.push({
                    id:val.id,
                    title:val.name
                }); 
            });
            $.each(xData, function(i, val) {
                projects.push({
                    id:val.id,
                    resourceId:val.id_user,
                    date: val.date,
                    title:val.code,
					tip:val.code +' \nPlan: '+ val.plan_time +' hrs '
                }); 
            }); 
            console.log(mebers);
            $('#calendar').fullCalendar({
                now: new Date(),
                weekends:false,                
                editable:false,
                contentHeight:'auto',
                header: {
                    left: '',
                    center: ' title ',
                    right: 'today, prev, next'
                },
                defaultView: 'timelineMonth',
                resourceAreaWidth: '15%',
                resourceLabelText: 'Member Name',
                resources: mebers,
                events:projects,
                eventRender: function(event, element) {
                    $(element).find(".fc-time").remove();
					element.attr('title', event.tip);
                },
				viewRender: function(currentView){
					//var minDate = moment(),
					//maxDate = moment().add(6,'month');
					
					// Future
					//if (maxDate >= currentView.start && maxDate <= currentView.end) {$(".fc-next-button").prop('disabled', true); $(".fc-next-button").addClass('fc-state-disabled'); 
					//} else {$(".fc-next-button").removeClass('fc-state-disabled'); $(".fc-next-button").prop('disabled', false); }
					// Past
					//if (minDate >= currentView.start && minDate <= currentView.end {$(".fc-prev-button").prop('disabled', true);$(".fc-prev-button").addClass('fc-state-disabled'); }
					//else {$(".fc-prev-button").removeClass('fc-state-disabled'); $(".fc-prev-button").prop('disabled', false); }
				}
            });
        }
    });
</script>