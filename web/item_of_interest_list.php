<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<?php

include_once('includes/feed.inc.php');

$locationId = $_REQUEST["locationId"];

$destinationMenuParameters = array();
$destinationMenuParameters["locationId"] = $locationId;
$destinationMenuParameters["autoHide"] = "true";
$destinationMenu = callFeed("destination_menu.feed", $destinationMenuParameters);

$locationParameters = array();
$locationParameters["locationId"] = $locationId;
$location = callFeed("location.feed", $locationParameters);
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
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
			<?php if ($_GET["type"] == "EVENT") { ?>
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
						
						<?php include "item_of_interest_table.php"; ?>
						
					</div>
				</div>			
			</div>
		</div>
	</body>
</html>

