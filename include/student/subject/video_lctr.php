<?php
echo '
<header class="panel-heading bg-primary">
    <p class="text-weight-semibold mt-none" style="font-size: 24px; color:#ffffff;"><i class="fa fa-list"></i>  Video Lectures</p>
</header>
<header class="panel-heading">
    <h2 class="panel-title"><i class="fa fa-list"></i> Video Lecture List </h2>
</header>
<div class="panel-body">';
include_once('video_lctr/list.php');
echo '
</div>';