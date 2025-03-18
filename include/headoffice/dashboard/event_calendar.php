<?php 
echo '
<div class="col-md-8">
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title">Event Calendar</h2>
		</header>
		<div class="panel-body" style="height: 529.71px;">
			<div id="event_calendar"></div>
		</div>
	</section>
</div>';
// NOTIFICATIONS
$condition = array(
                     'select'       =>  'n.not_title, n.dated'
                    ,'where'        =>  array(  
                                                 'n.is_deleted'    => 0
                                                ,'n.not_status'    => 1
                                        )
                    ,'order_by'  	=>  ' n.not_id DESC'
                    ,'return_type'  =>  'all'
);
$NOTIFICATIONS = $dblms->getRows(NOTIFICATIONS.' AS n', $condition);
// EVENTS
$condition = array(
                     'select'       =>  'e.subject, e.date_from, e.date_to'
                    ,'where'        =>  array(  
                                                 'e.is_deleted'    	=> 0
                                                ,'e.status'    		=> 1
                                        )
                    ,'order_by'  	=>  ' e.id DESC'
                    ,'return_type'  =>  'all'
);
$EVENTS = $dblms->getRows(EVENTS.' AS e', $condition);
echo'
<script type="application/javascript">
	$( document ).ready( function () {
		$( "#event_calendar" ).fullCalendar( {
			header: {
				left: "title",
				center: "month, agendaWeek, agendaDay, list",
				right: "prev,today,next"
			},
			//DEFAULTVIEW: "BASICWEEK"
			displayEventTime : false,
			editable: false,
			firstDay: 1,
			height: 500,
			droppable: false,
			events: [';
				foreach ($NOTIFICATIONS as $key => $val) {
					$year 	 = date('Y',strtotime($val['dated']));
					$month 	 = date('m',strtotime($val['dated']));
					$day 	 = date('d',strtotime($val['dated']));
					$date	 = $year.', '.($month-1).', '.$day;
					echo'
					{
						 title: "'.$val['not_title'].'"
						,start: new Date( '.$date.' )
						,color: "#c93e43"
						,textColor: "#ffffff"
					},';
				}
				foreach ($EVENTS as $key => $val) {
					$yearFrom 	= date('Y',strtotime($val['date_from']));
					$monthFrom 	= date('m',strtotime($val['date_from']));
					$dayFrom 	= date('d',strtotime($val['date_from']));
					$dateFrom	= $yearFrom.', '.($monthFrom-1).', '.$dayFrom;

					$yearTo 	= date('Y',strtotime($val['date_to']));
					$monthTo 	= date('m',strtotime($val['date_to']));
					$dayTo 		= date('d',strtotime($val['date_to']));
					$dateTo		= $yearTo.', '.($monthTo-1).', '.($dayTo+1);
					echo'
					{
						 title: "'.$val['subject'].'"
						,start: new Date( '.$dateFrom.' )
						,end: new Date( '.$dateTo.' )
						,color: "#0088cc"
						,textColor: "#ffffff"
					},';
				}
				echo'
			]
		});
	});
</script>';