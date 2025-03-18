<?php
echo'
<header class="panel-heading bg-primary">
    <a href=""><p class="text-weight-semibold mt-none text-center" style="font-size: 24px; color:#ffffff;">Attendance - '.$value_detail['class_name'].' ('.$value_detail['section_name'].')</p></a>
</header>
<header class="panel-heading">';
    if(!isset($_POST['view_student']) && !isset($_GET['edit_id'])){
        echo'
        <form action="#" id="form" method="post" accept-charset="utf-8">
            <input type="hidden" id="id_subject" name="id_subject" value="'.$value_detail['subject_id'].'">
            <input type="hidden" id="id_section" name="id_section" value="'.$value_detail['section_id'].'">
            <input type="hidden" id="id_class" name="id_class" value="'.$value_detail['class_id'].'">
            <input type="hidden" id="id_teacher" name="id_teacher" value="'.$value_emp['emply_id'].'">
            <button type="submit" id="view_student" name="view_student" class="btn btn-primary btn-xs pull-right"><i class="fa fa-save"></i> Mark Attendance</button>
        </form>';
    }
    echo'
    <h2 class="panel-title"><i class="fa fa-list"></i> List </h2>
</header>
<div class="panel-body">';
include_once('attendance/query_attendance.php');
include_once('attendance/list.php');
include_once('attendance/list_student.php');
include_once('attendance/edit.php');
echo '
</div>';