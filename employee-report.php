<?php
    if(isset($_GET['report'])) {

        include_once(('reports/employee/'.$_GET['report']).'.php');
        echo '<button type="button" id="printPageButton" onClick="window.print();" class="modal-with-move-anim ml-sm mb-xs btn btn-primary btn-xs pull-right">Print</button>';

    } else {
        echo'<div class="panel-body"><h2 class="text text-center text-danger mt-lg">No Record Found!</h2></div>';
    }
?>