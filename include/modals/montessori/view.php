<?php 
	echo '
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<form action="video-lecture.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="fa fa-video-camera"></i> '.$_GET['title'].'</h2>
					</header>
					<div class="panel-body">';
							echo '<iframe width="100%" height="320" src="https://www.youtube.com/embed/'.$_GET['youtube_code'].'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
					echo '
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button class="btn btn-default modal-dismiss">Cancel</button>
							</div>
						</div>
					</footer>
				</form>
			</section>
		</div>
	</div>';
?>