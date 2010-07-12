<?php

include_once('includes/feed.inc.php');

$locationId = $_REQUEST["locationId"];

$destinationMenuParameters = array();
$destinationMenuParameters["locationId"] = $locationId;
$destinationMenuParameters["autoHide"] = "true";
$destinationMenu = callFeed("destination_menu.feed", $destinationMenuParameters);

if (!$destinationMenu) {
	include "error.php"; 
	exit();
}

$locationParameters = array();
$locationParameters["locationId"] = $locationId;
$location = callFeed("location.feed", $locationParameters);

if (!$location) {
	include "error.php"; 
	exit();
}

$q = array();

foreach ($_GET as $key=>$val) {
	$q[$key] = $val;
}

$type = $q["type"];

if (!isset($q["locationId"])) {
	$q["locationId"] = $_REQUEST["locationId"];
}
if (!isset($q["page"])) {
	$q["page"] = 1;
}

if (!isset($q["sort"])) {
	
	if ($type == "EVENT") {
		$q["sort"] = "rank";
	} else {
		$q["sort"] = "";
		$q["sortDirection"] = "asc";
	} 
}

if (!isset($q["sortDirection"])) {
	$q["sortDirection"] = "desc";
}

if (!isset($q["nPerPage"])) {
	$q["nPerPage"] = "10";
}

if ($type == "EVENT") {
	$tagName = "eventResult";
} else {
	$tagName = "poiResult";
} 
		
$parameters = array();
$parameters["locationId"] = $q["locationId"];
if ($q["sort"] != "") {
	$parameters["sort"] = $q["sort"];
}
$parameters["sortDirection"] = $q["sortDirection"];
$parameters["page"] = $q["page"];
$parameters["nPerPage"] = $q["nPerPage"];
if ($type != "EVENT") {
	$parameters["type"] = $q["type"];
	$parameters["showMax"] = "true";
}

$baseURL = "item_of_interest_list.php";

if ($type == "EVENT") {
	$feed = "event_search.feed";
} else {
	$feed = "poi_search.feed";
}

$results = callFeed($feed, $parameters);

if (!$results) {
	include "error.php"; 
	exit();
}

$page = intval($results["currentPage"]);
$perPage = intval($q["nPerPage"]);
$start = (($page - 1) * $perPage) + 1;
$end = $start + intval($results["currentPageResultCount"]) - 1;

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
		<script src="js/jquery-1.4.2.min.js" type="text/javascript"></script>
		<script src="js/jquery.ajaxify.js" type="text/javascript"></script>
		<script src="js/jquery.cookie.js" type="text/javascript"></script>
		<script src="js/global.js" type="text/javascript"></script>
		<script type="text/javascript">
		$(document).ready(function() {
			$('.ajaxify').ajaxify();
		});
		</script>
		<link rel="stylesheet" href="css/screen.css" type="text/css" media="screen"></link>
	</head>
	<body>
		<div id="page">
			<?php if ($type == "EVENT") { ?>
				<?php $slideContentFile =  "content/slide_event_list.xml"; ?>	
			<?php } else { ?>
				<?php $slideContentFile =  "content/slide_poi_list.xml"; ?>
			<?php } ?>
			
			<?php include('includes/slider.php'); ?>
			
			<div id="innerPage">
			
				<div id="header">
					<a href="index.php">Your Brand | Home</a>
				</div>
				
				<div id="content">
		
					<?php include 'includes/navigation.php';?>
					
					<div class="primaryContent">
						
						<div id="breadcrumb">
							<!--  You are currently visiting -->
							<ul>
							<?php createLocationListItems($location->parent) ?>
							<li><a href="location_detail.php?locationId=<?php echo $location["id"]; ?>"><?php echo $location["name"];?></a></li>
							</ul>
						</div>

						<?php if ($type == "EVENT") { ?>
							<h2><span>Events</span></h2>
						<?php } else if ($type == "SHOPPING") { ?>
							<h2><span>Shopping</span></h2>
						<?php } if ($type == "HOTEL") { ?>
							<h2><span>Hotels</span></h2>
						<?php } if ($type == "RESTAURANT") { ?>
							<h2><span>Restaurants</span></h2>
						<?php } if ($type == "NIGHTLIFE") { ?>
							<h2><span>Nightlife</span></h2>
						<?php } if ($type == "ATTRACTION") { ?>
							<h2><span>Attractions</span></h2>
						<?php }?>
						
						<div id="results">
							<?php 
							$items = $results->xpath("//" . $tagName);
							$typeName = $items[0]["typeName"];
							$extendedInfoType = $items[0]["extended-info-type"]; 
							?>
							<div class="feedContent">
								<?php if ($type == "EVENT") { ?>
									<div class="feedName"><a href="#feed_event_search" class="feedViewAction">event_search.feed</a></div>
								<?php } else { ?>
									<div class="feedName"><a href="#feed_poi_search" class="feedViewAction">poi_search.feed</a></div>
								<?php } ?>
								<div>
									<table class="sortTable">
										<tr>
											<?php if ($type == "EVENT") { ?>
												<th class="imageColumn">&nbsp;</th>
												<?php buildSortHeader("Event", "name", $results["sort"], $results["sortDirection"], "eventColumn"); ?>
												<?php buildSortHeader("Date", "date", $results["sort"], $results["sortDirection"]); ?>
												<?php buildSortHeader("Rank", "rank", $results["sort"], $results["sortDirection"]); ?>
											<?php } else { ?>
												<?php buildSortHeader("Rank", "rank", $results["sort"], $results["sortDirection"]); ?>	
												<?php if ($type == "RESTAURANT" || $type == "HOTEL") { ?>
													<?php buildSortHeader("Price", "priceCategory", $results["sort"], $results["sortDirection"]); ?>
												<?php } ?>
												<?php buildSortHeader($typeName, "name", $results["sort"], $results["sortDirection"]); ?>		
												<?php if ($type == "RESTAURANT" || $type == "HOTEL" ) { ?>
													<?php buildSortHeader("Neighborhood", "neighborhood", $results["sort"], $results["sortDirection"]); ?>
												<?php } ?>
												<?php if ($type == "RESTAURANT") { ?>
													<?php buildSortHeader("Food Type", "extendedInfo", $results["sort"], $results["sortDirection"]); ?>
												<?php } ?>
												<?php if ($type == "NIGHTLIFE" || $type == "SHOPPING" || $type == "ATTRACTION") { ?>
													<?php if ($type == "NIGHTLIFE" || $type == "SHOPPING" ) { ?>
														<th>Category</th>
													<?php }else if ($type == "ATTRACTION") { ?>
														<th>Attraction Type</th>
													<?php } ?>
												<?php } ?>
											<?php } ?>	
										</tr>
										
										<?php foreach ($items as $result) {?>
											<tr>
												<?php if ($type == "EVENT") { ?>
							
													<td valign="top" style="padding: 7px 0px 0px 5px;">
														<?php if($result->image) { ?>
															<a href="item_of_interest_detail.php?itemOfInterestId=<?php echo $result["id"]; ?>"><img src="<?php echo BASE_MEDIA_URL; ?><?php echo $result->image["mediaUrl"]; ?>" /></a>
														<?php } ?>
													</td>
													<td colspan="2">
														<div class="details">
															<a href="item_of_interest_detail.php?locationId=<?php echo $locationId; ?>&itemOfInterestId=<?php echo $result["id"];?>"><?php echo $result["name"];?></a>
															<div><strong><?php echo $result["displayDate"];?></strong></div>
															<div><em><?php echo $result["displayLocation"];?></em></div>
														</div>
														<p><?php echo $result->summary; ?></p>
													</td>
													<td valign="top"><div class="rank<?php echo $result["rankId"];?>"><?php echo $result["rankId"];?></div></td>
												<?php } else { ?>
													<td><div class="rank<?php echo $result["rankId"];?>"><?php echo $result["rankId"];?></div></td>
													<?php if ($type == "RESTAURANT" || $type == "HOTEL") { ?>
														<td><div class="price<?php echo $result["priceCategory"];?>"><?php echo $result["priceCategory"];?></div></td>
													<?php } ?>
													<td><a href="item_of_interest_detail.php?locationId=<?php echo $locationId; ?>&itemOfInterestId=<?php echo $result["id"];?>"><?php echo $result["name"];?></a></td>
													<?php if ($type == "RESTAURANT" || $type == "HOTEL") { ?>
														<td><?php echo $result["neighborhood"];?></td>
													<?php } ?>
													<?php if ($type == "RESTAURANT") { ?>
														<td><?php echo $result["extendedInfo"];?></td>
													<?php } ?>
													<?php if ($type == "NIGHTLIFE" || $type == "SHOPPING" || $type == "ATTRACTION" ) { ?>
														<td><?php echo $result["subTypeName"];?></td>
													<?php } ?>
												<?php } ?>
												
											</tr>
										<?php } ?>
									</table>
								</div>		
							
						
								<?php if (intval($results["totalPageCount"]) > 1) { ?>		
								<div class="pagination">
									<strong>Showing <?php echo $start; ?> to <?php echo $end; ?> of <?php echo $results["totalResultCount"]; ?></strong>
									<!--  <strong>Page <?php echo $results["currentPage"];?> of <?php echo $results["totalPageCount"];?></strong> -->
									<div class="controls">
										<div class="back">
											<?php if (intval($results["currentPage"]) > 1) {?>
												<a href="<?php echo buildURL($q, array("page" => "1")); ?>">&lt;&lt; First</a>
												<a href="<?php echo buildURL($q, array("page" => $results["currentPage"] - 1)); ?>">&lt; Previous</a>
											<?php } ?>
										</div>
										<div class="forward">
											<?php if (intval($results["currentPage"]) < intval($results["totalPageCount"])) {?>
												<a href="<?php echo buildURL($q, array("page" => $results["currentPage"] + 1)); ?>">Next &gt;</a>
												<a href="<?php echo buildURL($q, array("page" => intval($results["totalPageCount"]))); ?>">Last &gt;&gt;</a>
											<?php } ?>
										</div>
									</div>
								</div>
								<?php } ?>
							</div>
						</div>						
					</div>
				</div>			
			</div>
		</div>
	</body>
</html>


<?php 
function buildURL($q, $u) {
	global $baseURL;
	$url = $baseURL . "?";
	foreach ($q as $k=>$v) {
		if (isset($u[$k])) {
			$url = $url . $k . "=" . $u[$k] . "&";
		} else {
			$url = $url . $k . "=" . $v . "&";
		}
	}
	return $url;
}
?>

<?php function buildSortHeader($name, $code, $sort, $sortDirection, $class = "") {
	global $q;
	if($sort != $code) { ?>
		<th class="sortable <?php if ($class != "") { echo $class; }?>"><a href="<?php echo buildURL($q, array("page" => "1", "sort" => $code, "sortDirection" => "desc")); ?>"><?php echo $name; ?></a></th>
	<?php } else { ?>
		<?php if ($sortDirection == "ascending") { ?>
			<th class="sorted asc <?php if ($class != "") { echo $class; }?>"><a href="<?php echo buildURL($q, array("page" => "1", "sort" => $code, "sortDirection" => "desc")); ?>"><?php echo $name; ?></a></th>
		<?php } else {?>
			<th class="sorted desc <?php if ($class != "") { echo $class; }?>"><a href="<?php echo buildURL($q, array("page" => "1", "sort" => $code, "sortDirection" => "asc")); ?>"><?php echo $name; ?></a></th>
		<?php } ?>
	<?php } 
}?>