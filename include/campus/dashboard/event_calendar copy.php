<?php
// ACADEMIC CALENDAR
$sqllms	= $dblms->querylms("SELECT d.id, d.date_start, d.date_end, d.remarks, p.cat_name
                                FROM ".A_CALENAR." a
                                INNER JOIN ".ACADEMIC_DETAIL." d ON d.id_setup = a.id 
                                INNER JOIN ".ACADEMIC_PARTICULARS." p ON p.cat_id = d.id_cat 
                                WHERE p.is_deleted != '1' AND published= '1'
                                AND p.cat_status = '1'
                                ORDER BY p.cat_ordering ASC
                            ");
$eventArray = array();
while($rowsvalues = mysqli_fetch_array($sqllms)) {
   array_push($eventArray, $rowsvalues);
}
echo'
<link rel="stylesheet" href="assets/calendar/css/style.css">

<div class="col-md-12">
    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h2 class="panel-title"> <i class="fa fa-calendar"></i> Event Calender	</h2>
        </header>
        <div class="panel-body">
            <!--<div id="demo-responsive-month-view"></div>-->
            <div id="event_calendar"></div>
            <div id="calendar"></div>            
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>
            <script src="assets/calendar/js/script.js"></script>
        </div>
    </section>
</div>


<script>
var events = [';
    foreach($eventArray as $event){
        $strt_day   = date('d', strtotime($event['date_start']));
        $strt_month = date('m', strtotime($event['date_start'])) -1 ;
        $strt_year   = date('Y', strtotime($event['date_start']));
        
        $end_day = date('d', strtotime($event['date_end'])) + 1;
        $end_month = date('m', strtotime($event['date_end'])) - 1;
        $end_year = date('Y', strtotime($event['date_end']));

        echo'
        {
            id: '.$event['id'].',
            start: new Date('.$strt_year.', '.$strt_month.', '.$strt_day.'),
            end: new Date('.$end_year.', '.$end_month.', '.$end_day.'),
            text: "'.$event['cat_name'].'"
        },';
    }
    echo'
];

var inst = mobiscroll.eventcalendar("#demo-responsive-month-view", {
    locale: mobiscroll.localeEn,
    theme: "ios",
    themeVariant: "light",
    responsive: {
        xsmall: {
            view: {
                calendar: {
                    type: "week",
                },
                agenda: {
                    type: "week"
                }
            }
        },
        custom: {
            breakpoint: 600,
            view: {
                calendar: {
                    type: "month",
                    labels: true
                },
                agenda: {
                    type: "week"
                }
            }
        }
    }
});

inst.setEvents(events);
</script>';
?>