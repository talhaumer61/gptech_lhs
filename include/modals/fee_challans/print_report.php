<?php  
echo '
<!-- Add Modal Box -->
<div id="print_report" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="feereportprint.php" target="_blank" class="form-horizontal" id="form" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i> Print Report</h2>
			</header>
			<div class="panel-body">
                <div class="form-group">
                    <div class="col-md-12">
                    	<label class="control-label">Summary <span class="required">*</span></label>
                        <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_summary" onchange="get_formforsummary(this.value)">
                            <option value="">Select Report Type</option>';
                            foreach($summarytype as $summary) {
                            	echo '<option value="'.$summary['id'].'">'.$summary['name'].'</option>';
                            }
                        	echo '
                        </select>
                    </div>
                </div>

                <div id="getformforsummary"> </div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit" name="submit">Print</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
?>

<script type="text/javascript">
	function get_formforsummary(id_summary) {  
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_formforsummary.php",
			data: "id_summary="+id_summary,  
			success: function(msg){  
				$("#getformforsummary").html(msg); 
				$("#loading").html(''); 
			}
		});  
	}
    function get_classsection(id_class) {  
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_classsection.php",
			data: "id_class="+id_class,  
			success: function(msg){  
				$("#getclasssection").html(msg); 
				$("#loading").html(''); 
			}
		});  
	}
	function get_student(id_section) { 
		var id_class = $("#id_class").val();  
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_student.php",
			data: { id_section: id_section, id_class: id_class},  
			success: function(msg){  
				$("#getstudent").html(msg); 
				$("#loading").html(''); 
			}
		});  
	}
</script>

<script>
	let checkboxes = document.querySelectorAll('input[type=checkbox]:checked');
	if(checkboxes.length <= 0) {
		
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		// header("Location: fee_challans.php", true, 301);
		exit();
	}
</script>