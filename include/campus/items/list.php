<?php
if($_SESSION['userlogininfo']['LOGINTYPE'] == 1) {

	$search_word = isset($_GET['search_word']) ? $_GET['search_word'] : '';
	$id_cls = isset($_GET['id_cls']) ? $_GET['id_cls'] : '';
	$id_cat = isset($_GET['id_cat']) ? $_GET['id_cat'] : '';
	

echo'
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-list"></i>  Items List '.$_SESSION['userlogininfo']['CAMPUSCODE'].'</h2>
	</header>
	<div class="panel-body">
		<form action="#" method="GET" autocomplete="off">
			<div class="row form-group mb-lg">
				<div class="col-md-3">
					<label class="control-label">Search </label>
					<div class="form-group">
						<input type="search" name="search_word" id="search_word" class="form-control" value="'.$search_word.'" placeholder="item name or item code" aria-controls="table_export">
					</div>
				</div>
				<div class="col-md-4">
					<label class="control-label">Class </label>
					<select data-plugin-selectTwo data-width="100%" name="id_cls" id="id_cls" title="Must Be Required" class="form-control populate">
						<option value="">Select</option>';
						// GET CLASSES
						$classes	  = $api->get_classes();
						foreach ($classes['data'] as $cls):
							echo'<option value="'.$cls['cls_id'].'" '.($id_cls == $cls['cls_id'] ? 'selected' : '').'>'.$cls['cls_code'].'-'.$cls['cls_name'].'</option>';
						endforeach;
						echo'
					</select>
				</div>
				<div class="col-md-4">
					<label class="control-label">Categories </label>
					<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_cat" name="id_cat">
						<option value="">Select</option>';
							// GET ITEM CATEGORIES
							$categories	  = $api->get_item_categories();
							foreach ($categories['data'] as $cat):
								echo'<option value="'.$cat['cat_id'].'" '.($id_cat == $cat['cat_id'] ? 'selected' : '').'>'.$cat['cat_name'].'</option>';
							endforeach;
							echo '
					</select>
				</div>
				<div class="col-md-1">
					<div class="form-group mt-xl">
						<button class="btn btn-primary btn-block" name="search"><i class="fa fa-search"></i></button>
					</div>
				</div>
			</div>
		</form>
		<hr>';
		// GET ITEMS
		if(file_exists('api/items.json')){
			// Read JSON file
			$json	=	file_get_contents('api/items.json');
			$items	=	json_decode($json, true);
		}else{
			$data	=	$api->get_items();
			$items	=	$data['items'];
			file_put_contents('api/items.json', json_encode($items));
		}
		
		if(count($items)){
			echo'
			<div class="row row-sm" id="items">';
				// FILTETR DATA
				$filteredItems = array_filter($items, function($item) use ($search_word, $id_cls, $id_cat) {
					$match = true;
					if ($search_word) {
						$search_word_lower = strtolower($search_word);
						$match = $match && (
							strpos(strtolower($item['item_name']), $search_word_lower) !== false ||
							strpos(strtolower($item['item_code']), $search_word_lower) !== false
						);
					}
					if ($id_cls) {
						$match = $match && ($item['id_cls'] == $id_cls);
					}
					if ($id_cat) {
						$match = $match && ($item['id_cat'] == $id_cat);
					}
					return $match;
				});
				// Calculate pagination details
				$Limit = '18';
				$count = count($filteredItems);
				$startIndex = ($page - 1) * $Limit;
				$paginatedItems = array_slice($filteredItems, $startIndex, $Limit);
				if($page == 1){
					$srno = 0;
				}else{
					$srno = ($page - 1) * $Limit;
				}
				$lastpage	= ceil(count($filteredItems) / $Limit);	
				$filters	= 'id_cls='.$id_cls.'&id_cat='.$id_cat.'&search_word='.$search_word.'';
				foreach($paginatedItems as $item){
					$srno++;
					echo '
					<div class="col-4 col-sm-4 col-md-3 col-lg-2 col-xl-2 mb-sm">
						<div class="card card-file">
							<div class="card-file-thumb">
								<img src="'.$item['item_photo'].'" width="100" height="100">
							</div>
							<div class="card-body  text-center">
								<b class="line-clamp-1">'.$item['item_code'].'-'.$item['item_name'].'</b>
								<b class="text-info">Rs: '.$item['item_pricesale'].'</b>
							</div>
							<div class="card-footer text-center">
								<a onclick="addtocart(\''.$item['item_id'].'\',\''.$item['item_photo'].'\',\''.$item['item_code'].'\',\''.$item['item_name'].'\',\''.$item['item_pricesale'].'\')" class="btn btn-primary btn-xs"><i class="fa fa-shopping-cart"></i> Add to cart</a>
							</div>
						</div>	
					</div>';
				}
				echo '
			</div>';
			include("include/pagination.php");
		}
		else{
			echo'<div class="panel-body"><h2 class="text text-center text-danger mt-lg">No Record Found!</h2></div>';
		}
		echo'
	</div>
</section>';
}
else{
	header("Location: dashboard.php");
}
?>