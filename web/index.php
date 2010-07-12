<?php
				
include('includes/feed.inc.php');

$eventParameters = array();
$eventParameters["nPerPage"] = "5";
$eventParameters["sort"] = "rank";
$event = callFeed("event_search.feed", $eventParameters);

if (!$event) {
	include "error.php"; 
	exit();
}

$input = array(0, 1, 2, 3, 4);
$rand_keys = array_rand($input, 3);

$countryLocationsParameters = array();
$countryLocationsParameters["type"] = "COUNTRY";
$countryLocations = callFeed("location_search.feed", $countryLocationsParameters);

if (!$countryLocations) {
	include "error.php"; 
	exit();
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<script src="js/jquery-1.4.2.min.js" type="text/javascript"></script>
		<script src="js/jquery.cookie.js" type="text/javascript"></script>
		<script src="js/global.js" type="text/javascript"></script>
		<link rel="stylesheet" href="css/screen.css" type="text/css" media="screen"></link>
        <title>Your Brand</title>
	</head>
	<body id="home">
		<div id="page">
			
			<?php $slideContentFile =  "content/slide_index.xml"; ?>
			<?php include('includes/slider.php'); ?>
			
			<div id="innerPage">	
				<div id="header">
					<a href="index.php">Your Brand | Home</a>
				</div>
				
				<div id="content">
					<div class="primaryContent">
						<img src="images/elephant.jpg" alt="Imagery" title="Imagery" />	
						
						<h1>Welcome to the Your Brand Destination Guide</h1>
						
						<p>Welcome to the Your Brand destination guide. Let us guide you through everything you need to ensure you have the perfect vacation. We can give detailed practical information to ensure your travel plans run as smoothly as possible. Check out our restaurant guides to find the best tables in town. Our attractions guides ensure you'll know what not to miss and also what hidden gems you can uncover. Our nightlife guide will guide you towards the best places to kick-back at the end of the day, while our accommodation guide will help you discover the most comfortable places to stay. And, if you're looking for some extra inspiration, our comprehensive events guide lets you know all about the festivals, concerts, exhibitions etc that will make it a trip to remember. Enjoy!</p>
					</div>
					<div class="secondaryContent">
						<h2><span>Featured Events</span></h2>
						
						<div class="feedContent">
							<div class="feedName"><a href="#feed_event_search" class="feedViewAction">event_search.feed</a></div>
							<?php $i = $rand_keys[0] ?>
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
							
							<?php $i = $rand_keys[1] ?>
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
						</div>
						<div class="tidbit">
							<div class="feedContent">
								<div class="feedName"><a href="#feed_location_search" class="feedViewAction">location_search.feed</a></div>
								<h3>Countries</h3>
								<ul class="simple">
								<?php foreach ($countryLocations as $key=>$locationResult) { ?>
									<li><a href="location_detail.php?locationId=<?php echo $locationResult["id"];?>"><?php echo $locationResult["name"];?></a></li>
								<?php } ?>
								</ul>
							</div>
						</div>
						
						<div class="tidbit">
							<h3>Cities</h3>
							<ul class="simple">
								<li><a href="location_detail.php?locationId=149605">London</a></li>
								<li><a href="location_detail.php?locationId=151160">Paris</a></li>
								<li><a href="location_detail.php?locationId=150668">New York City</a></li>
								<li><a href="location_detail.php?locationId=153134">Sydney</a></li>
								<li><a href="location_detail.php?locationId=153446">Tokyo</a></li>
							</ul>
						</div>
						
						<div class="tidbit">
							<?php $i = $rand_keys[2] ?>
							<?php if($event->eventResult[$i]->image) { ?>
								<a href="item_of_interest_detail.php?itemOfInterestId=<?php echo $event->eventResult[$i]["id"]; ?>"><img src="<?php echo BASE_MEDIA_URL; ?><?php echo $event->eventResult[$i]->image["mediaUrl"]; ?>" /></a>
							<?php } ?>
						</div>
					</div>
				</div>			
			</div>
		</div>
	</body>
</html>












