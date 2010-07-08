<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 

<?php

include('includes/feed.inc.php');

if (isset($_REQUEST["guideStructureId"])) {
	$guideStructureId = $_REQUEST["guideStructureId"];
}

$guideStructureParameters = array();
$guideStructureParameters["guideStructureId"] = $guideStructureId;
$guideStructure = callFeed("guide_structure.feed", $guideStructureParameters);

$locationId = $guideStructure->guide->location["id"];

$locationParameters = array();
$locationParameters["locationId"] = $locationId;
$location = callFeed("location.feed", $locationParameters);
$type = $location["type"];

$destinationMenuParameters = array();
$destinationMenuParameters["guideStructureId"] = $guideStructureId;
$destinationMenuParameters["autoHide"] = "true";
$destinationMenu = callFeed("destination_menu.feed", $destinationMenuParameters);


?>


<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<script src="js/jquery-1.4.2.min.js" type="text/javascript"></script>
		<script src="js/jquery.cookie.js" type="text/javascript"></script>
		<script src="js/global.js" type="text/javascript"></script>
		<link rel="StyleSheet" href="css/screen.css" type="text/css" media="screen">
	</head>
	<body>
		<div id="page">
			
			<?php $slideContentFile =  "content/slide_guide_detail.xml"; ?>
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
							<?php createLocationListItems($guideStructure->guide->location->parent); ?>
							<li><a href="location_detail.php?locationId=<?php echo $guideStructure->guide->location["id"]; ?>"><?php echo $guideStructure->guide->location["name"]; ?></a></li>
							</ul>
						</div>
						
						<h2><span><?php echo $guideStructure->guideStructureType["name"]; ?></span></h2>
						<div class="feedContent">
						<div class="feedName"><a href="#feed_guide_structure" class="feedViewAction">guide_structure.feed</a></div>
							<div>
								<div class="rawHTMLOutput">
									<?php echo htmlspecialchars_decode($guideStructure->content);?>
								</div>
							</div>
						</div>
					</div>
				</div>			
			</div>
		</div>
	</body>
</html>





























