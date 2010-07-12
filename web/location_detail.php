<?php

include('includes/feed.inc.php');
include('includes/image_finder.php');

if (isset($_REQUEST["locationId"])) {
	$locationId = $_REQUEST["locationId"];
}

$destinationMenuParameters = array();
$destinationMenuParameters["locationId"] = $locationId;
$destinationMenuParameters["autoHide"] = "true";
$destinationMenu = callFeed("destination_menu.feed", $destinationMenuParameters);

if (!$destinationMenu) {
	include "error.php"; 
	exit();
}

$snippet = null;
$introductionGuideStructureId = $destinationMenu->xpath('//destinationLink[@name="Introduction"]/@url'); 
if ($introductionGuideStructureId) {
	$snippetParameters = array();
	$snippetParameters["guideStructureId"] = getGuideStructureIdFromUrl($introductionGuideStructureId[0]);
	$snippet = callFeed("guide_structure.feed", $snippetParameters);
	$snippet = htmlspecialchars_decode($snippet->content);
	$start = strpos($snippet, '<p>');
	$end = strpos($snippet, '</p>', $start);
	$snippet = substr($snippet, 0, $end-$start+4);
}

 
$locationParameters = array();
$locationParameters["locationId"] = $locationId;
$location = callFeed("location.feed", $locationParameters);

if (!$location) {
	include "error.php"; 
	exit();
}

$type = $location["type"];

$childRegionLocations = null;
$childStateLocations = null;
if ($type =="COUNTRY") {
	$childRegionLocationsParameters["parentId"] = $locationId;
	$childRegionLocationsParameters["type"] = "REGION";
	$childRegionLocationsParameters["showMax"] = "true";
	$childRegionLocations = callFeed("location_search.feed", $childRegionLocationsParameters);
	
	if (!$childRegionLocations) {
		include "error.php"; 
		exit();
	}
	
	$childStateLocationsParameters["parentId"] = $locationId;
	$childStateLocationsParameters["type"] = "STATE";
	$childStateLocationsParameters["showMax"] = "true";
	$childStateLocations = callFeed("location_search.feed", $childStateLocationsParameters);

	if (!$childStateLocations) {
		include "error.php"; 
		exit();
	}
}
$childTownLocations = null;
if ($type =="REGION" || $type == "STATE" || $type == "COUNTRY") {	
	$childTownLocationsParameters["parentId"] = $locationId;
	$childTownLocationsParameters["type"] = "TOWN";
	$childTownLocationsParameters["showMax"] = "true";
	$childTownLocations = callFeed("location_search.feed", $childTownLocationsParameters);
	
	if (!$childTownLocations) {
		include "error.php"; 
		exit();
	}
}

$eventParameters = array();
$eventParameters["nPerPage"] = "7";
$eventParameters["locationId"] = $locationId;
if ($type !="TOWN") {
	$eventParameters["sort"] = "rank";	
}

$event = callFeed("event_search.feed", $eventParameters);

if (!$event) {
	include "error.php"; 
	exit();
}
$numberOfEvents = 3;
if ($type =="TOWN") {
	$numberOfEvents = 5;	
}
$events = $event->xpath('..//eventResult');
if (count($events) > 0 && $numberOfEvents > count($events)) {
	$numberOfEvents = count($events);
}
$rand_keys = array_rand($events , $numberOfEvents);

if ($type =="TOWN" ) {	
	$topRestaurantParameters = array();
	$topRestaurantParameters["nPerPage"] = "3";
	$topRestaurantParameters["locationId"] = $locationId;
	$topRestaurantParameters["type"] = "RESTAURANT";
	$topRestaurantParameters["sort"] = "rank";
	$topRestaurant = callFeed("poi_search.feed", $topRestaurantParameters);

	if (!$topRestaurant) {
		include "error.php"; 
		exit();
	}
	
	$topAttractionParameters = array();
	$topAttractionParameters["nPerPage"] = "3";
	$topAttractionParameters["locationId"] = $locationId;
	$topAttractionParameters["type"] = "ATTRACTION";
	$topRestaurantParameters["sort"] = "rank";
	$topAttraction = callFeed("poi_search.feed", $topAttractionParameters);
	
	if (!$topAttraction) {
		include "error.php"; 
		exit();
	}
	
	$topNightlifeParameters = array();
	$topNightlifeParameters["nPerPage"] = "3";
	$topNightlifeParameters["locationId"] = $locationId;
	$topNightlifeParameters["type"] = "NIGHTLIFE";
	$topNightlifeParameters["sort"] = "rank";
	$topNightlife = callFeed("poi_search.feed", $topNightlifeParameters);
	
	if (!$topNightlife) {
		include "error.php"; 
		exit();
	}
	
	$topShoppingParameters = array();
	$topShoppingParameters["nPerPage"] = "3";
	$topShoppingParameters["locationId"] = $locationId;
	$topShoppingParameters["type"] = "SHOPPING";
	$topShoppingParameters["sort"] = "rank";
	$topShopping = callFeed("poi_search.feed", $topShoppingParameters);
	
	if (!$topShopping) {
		include "error.php"; 
		exit();
	}
	
	$topHotelParameters = array();
	$topHotelParameters["nPerPage"] = "3";
	$topHotelParameters["locationId"] = $locationId;
	$topHotelParameters["type"] = "HOTEL";
	$topHotelParameters["sort"] = "rank";
	$topHotel = callFeed("poi_search.feed", $topHotelParameters);

	if (!$topHotel) {
		include "error.php"; 
		exit();
	}
}

$eventImages = findImages($locationId, 8, "EVENT");
$poiImages = findImages($locationId, 8, "POI");
$headerImages = array_merge($eventImages, $poiImages);
$showImageBand = false;
if (count($headerImages) >= 5) {
	$showImageBand = true;
	$rand_images = array_rand($headerImages, 5);
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<script src="js/jquery-1.4.2.min.js" type="text/javascript"></script>
		<script src="js/jquery.cookie.js" type="text/javascript"></script>
		<script src="js/jquery.tools.min.js" type="text/javascript"></script>
		<script src="js/global.js" type="text/javascript"></script>
		<script src="js/location-detail.js" type="text/javascript"></script>
		<link rel="stylesheet" href="css/screen.css" type="text/css" media="screen"></link>
		<link rel="stylesheet" href="css/location-scroll.css" type="text/css" media="screen"></link>
	</head>
	<body>
		<div id="page">
			
			<?php $slideContentFile =  "content/slide_location_detail.xml"; ?>
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
						
						<h2><span><?php echo $location["name"];?></span></h2>				
						
						<?php 
						if ($snippet) { ?>
							<div class="feedContent">
								<div class="feedName"><a href="#feed_guide_structure" class="feedViewAction">guide_structure.feed</a></div>
								<?php echo $snippet; ?>
							</div>
						<?php }	?>
		
						<?php  if ($showImageBand && (count($rand_images) > 4)) { ?>
							<div class="feedContent">
								<div class="feedName"><a href="#feed_event_search" class="feedViewAction">event_search.feed</a> &amp; <a href="#feed_poi_search" class="feedViewAction">poi_search.feed</a></div>
								<div class="thumbs">
									<?php foreach($rand_images as $i) { ?>
										<?php $image = $headerImages[$i]; ?>
										<a href="<?php echo $image["url"]; ?>" title="<?php echo $image["name"]; ?>"><img src="<?php echo BASE_MEDIA_URL; ?><?php echo $image["mediaUrl"]; ?>" /></a>
									<?php } ?>
								</div>
							</div>	
						<?php } ?>
						
						<?php $maxItemCount = 32; ?>
	
						<?php if (count($childRegionLocations) > 0) {
							?><h3>REGIONS</h3>	
							<div class="feedContent">
								<div class="feedName"><a href="#feed_location_search" class="feedViewAction">location_search.feed</a></div>
								<?php if(count($childRegionLocations) > $maxItemCount) {?>								
									<div class="scrollContainer" id="countryScroll">
										<a class="prev browse left"></a>
										<div class="scrollable">   
											<div class="items">
												<div>
												<?php
													$i = 0; 
													foreach ($childRegionLocations as $key=>$locationResult) { 
														$i++;
														if ($i % $maxItemCount == 0) {?>...</div><div>...<?php } ?>
													<a href="location_detail.php?locationId=<?php echo $locationResult["id"];?>"><?php echo str_replace(' ','&nbsp;',$locationResult["name"]);?></a>
												<?php } ?>
												</div>
											</div>
										</div>
										<a class="next browse right"></a>	
									</div>
								<?php } else { ?>
									<div class="noScrollContainer">
										<?php foreach ($childRegionLocations as $key=>$locationResult) { ?>
												<a href="location_detail.php?locationId=<?php echo $locationResult["id"];?>"><?php echo str_replace(' ','&nbsp;',$locationResult["name"]);?></a>
										<?php } ?>
									</div>
								<?php } ?>					
							</div>
						<?php } ?>
	
						<?php if (count($childStateLocations) > 0) {
							?><h3>STATES</h3>
							<div class="feedContent">
								<div class="feedName"><a href="#feed_location_search" class="feedViewAction">location_search.feed</a></div>
								<?php if(count($childStateLocations) > $maxItemCount) {?>										
									<div class="scrollContainer" id="countryScroll">
										<a class="prev browse left"></a>
										<div class="scrollable">   
											<div class="items">
												<div>
												<?php
													$i = 0; 
													foreach ($childStateLocations as $key=>$locationResult) { 
														$i++;
														if ($i % $maxItemCount == 0) {?>...</div><div>...<?php } ?>
													<a href="location_detail.php?locationId=<?php echo $locationResult["id"];?>"><?php echo str_replace(' ','&nbsp;',$locationResult["name"]);?></a>
												<?php } ?>
												</div>
											</div>
										</div>
										<a class="next browse right"></a>	
									</div>
								<?php } else { ?>
									<div class="noScrollContainer">
										<?php foreach ($childStateLocations as $key=>$locationResult) { ?>
												<a href="location_detail.php?locationId=<?php echo $locationResult["id"];?>"><?php echo str_replace(' ','&nbsp;',$locationResult["name"]);?></a>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
						
						<?php if (count($childTownLocations) > 0) { ?>
							<h3>TOWNS</h3>
							<div class="feedContent">
								<div class="feedName"><a href="#feed_location_search" class="feedViewAction">location_search.feed</a></div>
								<?php if(count($childTownLocations) > $maxItemCount) {?>									
									<div class="scrollContainer" id="countryScroll">
										<a class="prev browse left"></a>
										<div class="scrollable">   
											<div class="items">
												<div>
												<?php
													$i = 0; 
													foreach ($childTownLocations as $key=>$locationResult) { 
														$i++;
														if ($i % $maxItemCount == 0) {?>...</div><div>...<?php } ?>
													<a href="location_detail.php?locationId=<?php echo $locationResult["id"];?>"><?php echo str_replace(' ','&nbsp;',$locationResult["name"]);?></a>
												<?php } ?>
												</div>
											</div>
										</div>
										<a class="next browse right"></a>	
									</div>
								<?php } else { ?>
									<div class="noScrollContainer">
										<?php foreach ($childTownLocations as $key=>$locationResult) { ?>
												<a href="location_detail.php?locationId=<?php echo $locationResult["id"];?>"><?php echo str_replace(' ','&nbsp;',$locationResult["name"]);?></a>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
						
						<div id="featuredEventsOverview" class="<?php echo $type;?>">
							<?php if (count($event->eventResult) > 0) { ?>
								<h2><span>Featured Events</span></h2>
								<div class="feedContent">
									<div class="feedName"><a href="#feed_event_search" class="feedViewAction">event_search.feed</a></div>
									<?php foreach($rand_keys as $i) { ?>
									<div class="detailBox">
										<?php if($event->eventResult[$i]->image) { ?>
											<a href="item_of_interest_detail.php?itemOfInterestId=<?php echo $event->eventResult[$i]["id"]; ?>"><img src="<?php echo BASE_MEDIA_URL; ?><?php echo $event->eventResult[$i]->image["mediaUrl"]; ?>" /></a>
										<?php } ?>
										<div class="inner">
											<div class="details">
												<a href="item_of_interest_detail.php?itemOfInterestId=<?php echo $event->eventResult[$i]["id"]; ?>"><?php echo $event->eventResult[$i]["name"]; ?></a><br />
												<strong><?php echo $event->eventResult[$i]["displayDate"]; ?></strong><br />
												<em><?php echo $event->eventResult[$i]["displayLocation"]; ?></em><br />
											</div>
											<p><?php echo $event->eventResult[$i]->summary; ?></p>
										</div>
									</div>
									<?php } ?>
								</div>
							<?php  } ?>
						</div>
						
						<?php if ($type =="TOWN" ) { ?>	
						<div style="float:right;margin-left:20px;">
							<?php if ($topRestaurant->poiResult) {?>
								<div class="feedContent">
									<div class="feedName"><a href="#feed_poi_search" class="feedViewAction">poi_search.feed</a></div>
									<a href="item_of_interest_list.php?type=RESTAURANT&locationId=<?php echo $locationId; ?>" style="float:right">view all</a>
									<h3>Top Restaurants</h3>
									<ul class="detailList">
										<?php foreach ($topRestaurant->poiResult as $result) { ?>
										<li>
											<a href="item_of_interest_detail.php?itemOfInterestId=<?php echo $result["id"]; ?>"><?php echo $result["name"]; ?></a> (<?php echo $result["extendedInfo"]; ?>)
											<div><?php echo $result["neighborhood"]; ?></div>
											<?php if ($result["rankId"] && $result["rankId"] != "") { ?>
												<div style="float:left" class="rank<?php echo $result["rankId"];?>"><?php echo $result["rankId"];?></div>
											<?php } ?>
											<?php if ($result["priceCategory"] && $result["priceCategory"] > 0) { ?>
												<div class="price<?php echo $result["priceCategory"];?>"><?php echo $result["priceCategory"];?></div>
											<?php } ?>
											<div style="clear:both"></div>
										</li>	
										<?php } ?>
									</ul>
								</div>
							<?php } ?>
							<?php if ($topAttraction->poiResult) {?>
								<div class="feedContent">
									<div class="feedName"><a href="#feed_poi_search" class="feedViewAction">poi_search.feed</a></div>
									<a href="item_of_interest_list.php?type=ATTRACTION&locationId=<?php echo $locationId; ?>" style="float:right">view all</a>
									<h3>Top Attractions</h3>
									<ul class="detailList">
										<?php foreach ($topAttraction->poiResult as $result) { ?>
										<li>
											<a href="item_of_interest_detail.php?itemOfInterestId=<?php echo $result["id"]; ?>"><?php echo $result["name"]; ?></a>
											<div><?php echo $result["subTypeName"]; ?></div>
											<?php if ($result["rankId"] && $result["rankId"] != "") { ?>
												<div style="float:left" class="rank<?php echo $result["rankId"];?>"><?php echo $result["rankId"];?></div>
											<?php } ?>
											<?php if ($result["priceCategory"] && $result["priceCategory"] > 0) { ?>
												<div class="price<?php echo $result["priceCategory"];?>"><?php echo $result["priceCategory"];?></div>
											<?php } ?>
											<div style="clear:both"></div>
										</li>	
										<?php } ?>
									</ul>
								</div>
							<?php } ?>
							<?php if ($topNightlife->poiResult) {?>
								<div class="feedContent">
									<div class="feedName"><a href="#feed_poi_search" class="feedViewAction">poi_search.feed</a></div>
									<a href="item_of_interest_list.php?type=NIGHTLIFE&locationId=<?php echo $locationId; ?>" style="float:right">view all</a>
									<h3>Top Nightlife</h3>
									<ul class="detailList">
										<?php foreach ($topNightlife->poiResult as $result) { ?>
										<li>
											<a href="item_of_interest_detail.php?itemOfInterestId=<?php echo $result["id"]; ?>"><?php echo $result["name"]; ?></a>
											<div><?php echo $result["subTypeName"]; ?></div>
											<?php if ($result["rankId"] && $result["rankId"] != "") { ?>
												<div style="float:left" class="rank<?php echo $result["rankId"];?>"><?php echo $result["rankId"];?></div>
											<?php } ?>
											<?php if ($result["priceCategory"] && $result["priceCategory"] > 0) { ?>
												<div class="price<?php echo $result["priceCategory"];?>"><?php echo $result["priceCategory"];?></div>
											<?php } ?>
											<div style="clear:both"></div>
										</li>	
										<?php } ?>
									</ul>
								</div>
							<?php } ?>
							<?php if ($topShopping->poiResult) {?>
								<div class="feedContent">
									<div class="feedName"><a href="#feed_poi_search" class="feedViewAction">poi_search.feed</a></div>
									<a href="item_of_interest_list.php?type=SHOPPING&locationId=<?php echo $locationId; ?>" style="float:right">view all</a>
									<h3>Top Shopping</h3>
									<ul class="detailList">
										<?php foreach ($topShopping->poiResult as $result) { ?>
										<li>
											<a href="item_of_interest_detail.php?itemOfInterestId=<?php echo $result["id"]; ?>"><?php echo $result["name"]; ?></a>
											<div><?php echo $result["subTypeName"]; ?></div>
											<?php if ($result["rankId"] && $result["rankId"] != "") { ?>
												<div style="float:left;" class="rank<?php echo $result["rankId"];?>"><?php echo $result["rankId"];?></div>
											<?php } ?>
											<?php if ($result["priceCategory"] && $result["priceCategory"] > 0) { ?>
												<div class="price<?php echo $result["priceCategory"];?>"><?php echo $result["priceCategory"];?></div>
											<?php } ?>
											<div style="clear:both"></div>
										</li>	
										<?php } ?>
									</ul>
								</div>
							<?php } ?>
							<?php if ($topHotel->poiResult) {?>
								<div class="feedContent">
									<div class="feedName"><a href="#feed_poi_search" class="feedViewAction">poi_search.feed</a></div>
									<a href="item_of_interest_list.php?type=HOTEL&locationId=<?php echo $locationId; ?>" style="float:right">view all</a>
									<h3>Top Hotels</h3>
									<ul class="detailList">
										<?php foreach ($topHotel->poiResult as $result) { ?>
										<li>
											<a href="item_of_interest_detail.php?itemOfInterestId=<?php echo $result["id"]; ?>"><?php echo $result["name"]; ?></a>
											<div><?php echo $result["neighborhood"]; ?></div>
											<div><?php echo $result["extendedInfo"]; ?></div>
											<?php if ($result["rankId"] && $result["rankId"] != "") { ?>
												<div style="float:left" class="rank<?php echo $result["rankId"];?>"><?php echo $result["rankId"];?></div>
											<?php } ?>
											<?php if ($result["priceCategory"] && $result["priceCategory"] > 0) { ?>
												<div class="price<?php echo $result["priceCategory"];?>"><?php echo $result["priceCategory"];?></div>
											<?php } ?>
											<div style="clear:both"></div>					
										</li>	
										<?php } ?>
									</ul>
								</div> 
							<?php } ?>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>			
		</div>
	</body>
</html>