<?php 
echo ' 	
<div class="col-md-4">
	<section class="panel">
		<div class="panel-body">
			<div class="thumb-info mb-md">
    			<img src="'.$photo.'" class="rounded img-responsive">
				<div class="thumb-info-title">
					<span class="thumb-info-inner">'.$rowsvalues['campus_name'].'</span>
					<span>'.get_status($rowsvalues['campus_status']).'</span>
				</div>
			</div>	
			<div class="widget-toggle-expand mb-xs">
				<div class="widget-content-expanded">
					<table class="table table-striped table-condensed mb-none">
						<tr>
							<td>Name</td>
							<td align="right">'.$rowsvalues['campus_name'].'</td>
						</tr>
						<tr>
							<td>Code</td>
							<td align="right">'.$rowsvalues['campus_code'].'</td>
						</tr>
						<tr>
							<td>Zone</td>
							<td align="right">'.get_AreaZone($rowsvalues['id_zone']).'</td>
						</tr>
						<tr>
							<td>Regno#</td>
							<td align="right">'.$rowsvalues['campus_regno'].'</td>
						</tr>
						<tr>
							<td>Govt. Regno#</td>
							<td align="right">'.$rowsvalues['campus_regno_gov'].'</td>
						</tr>
						<tr>
							<td>Head</td>
							<td align="right">'.$rowsvalues['campus_head'].'</td>
						</tr>
						<tr>
							<td>Eamil</td>
							<td align="right">'.$rowsvalues['campus_email'].'</td>
						</tr>
						<tr>
							<td>Phone</td>
							<td align="right">'.$rowsvalues['campus_phone'].'</td>
						</tr>
						<tr>
							<td>Fax</td>
							<td align="right">'.$rowsvalues['campus_fax'].'</td>
						</tr>
						<tr>
							<td>Address</td>
							<td align="right">'.$rowsvalues['campus_address'].'</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</section>
</div>';
