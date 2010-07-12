<?php				
include('includes/feed.inc.php');

if (isset($_REQUEST["itemOfInterestId"])) {
	$itemOfInterestId = $_REQUEST["itemOfInterestId"];
}

$itemOfInterestParameters = array();
$itemOfInterestParameters["itemOfInterestId"] = $itemOfInterestId;
$itemOfInterest = callFeed("item_of_interest.feed", $itemOfInterestParameters);

if (!$itemOfInterest) {
	include "error.php"; 
	exit();
}

$type = $itemOfInterest["type"];

if (isset($_REQUEST["locationId"])) {
	$locationId = $_REQUEST["locationId"];
} else {
	$topLocationId =  ($itemOfInterest->xpath('/itemOfInterest/locationInfos/locationInfo/parent/@id'));
	$locationId =  $topLocationId[0];
}

$destinationMenuParameters = array();
$destinationMenuParameters["itemOfInterestId"] = $itemOfInterestId;
$destinationMenuParameters["autoHide"] = "true";
$destinationMenu = callFeed("destination_menu.feed", $destinationMenuParameters);

if (!$destinationMenu) {
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
	</head>
	<body>
		<div id="page">
		
			<?php $slideContentFile =  "content/slide_item_detail.xml"; ?>
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
							<?php createLocationListItems($itemOfInterest->locationInfos->locationInfo->parent) ?>
							</ul>
						</div>
						
                        	<h2><span><?php echo htmlspecialchars_decode($itemOfInterest["name"]);?></span></h2>				
						
							<div class="feedContent">
							<div class="feedName"><a href="#feed_item_of_interest" class="feedViewAction">item_of_interest.feed</a></div>
							
							<div style="float:right;width:30%">
								<?php if ($itemOfInterest["rankId"]) { ?>
									<div style="float:right" class="rank<?php echo $itemOfInterest["rankId"];?>"><?php echo $itemOfInterest["rankId"];?></div>								
								<?php } ?>
								
								<?php
									$media = null; 
									$media =  $itemOfInterest->xpath("//medias/media[@mediaType='I-BG']");
									if ($media) { ?>
										<div style="float:right;clear:right;"><img src="<?php echo BASE_MEDIA_URL; ?><?php echo $media[0]["url"]; ?>" alt="<?php echo $media[0]["caption"]; ?>" title="<?php echo $media[0]["caption"]; ?>" /></div>
								<?php } ?>
							</div>
							<?php if ($itemOfInterest->locationInfos->locationInfo ) { 
								$locationInfo = $itemOfInterest->locationInfos->locationInfo;
								if ($locationInfo->address) {
									$address = $locationInfo->address?>
									<div class="address" style="width:65%">
										<?php if ($address["address"]) { ?>
											<?php echo $address["address"]; ?>,
										<?php } ?>
										<?php if ($address["city"]) { ?>
											<?php echo $address["city"]; ?>,
										<?php } ?>
										<?php if ($address["state"]) { ?>
											<?php echo $address["state"]; ?>
										<?php } ?>
										<?php if ($address["postcode"]) { ?>
											<?php echo $address["postcode"]; ?>,
										<?php } ?>
										<?php if ($address["country"]) { ?>
											<?php echo $address["country"]; ?>
										<?php } ?>
									</div>
								<?php } ?>
							<?php } ?>
							
							<div class="detailGroup">
								<table class="infoTable" <?php if ($media) { ?> style="width: 65%" <?php } ?>>
								<?php if ($address["directions"]) { ?>
									<tr><th>Directions</th><td><?php echo $address["directions"]; ?></td></tr>
								<?php } ?>
								<?php if ($address["telephone1"]) { ?>
									<tr><th>Telephone</th><td><?php echo $address["telephone1"]; ?></td></tr>
								<?php } ?>
								<?php if ($address["email"]) { ?>
									<tr><th>Email</th><td><a href="mailto:<?php echo $address["email"]; ?>"><?php echo $address["email"]; ?></a></td></tr>	
								<?php } ?>
								<?php if ($address["fax"]) { ?>
									<tr><th>Fax</th><td><?php echo $address["fax"]; ?></td></tr>
								<?php } ?>
								<?php if ($locationInfo->link) {
									$link = $locationInfo->link ?>
									<tr><th>Website</th><td><a href="<?php if (!strpos($link["url"],"http",0)===0) echo "http://"; echo $link["url"]; ?>"><?php echo $link["name"]; ?></a></td></tr>
								<?php } ?>
								<?php if ($itemOfInterest["openingHours"] && $itemOfInterest["openingHours"] != "") { ?>
									<tr><th>Hours</th><td><?php echo str_replace(';','<br />',$itemOfInterest["openingHours"]); ?></td></tr>
								<?php } ?>
								<?php
									$field = null; 
									$field =  $itemOfInterest->xpath("//fields/field[@key='CUISINE_TYPE1']"); 
									if ($field && $field != "") { ?>
										<tr>
											<th>Cuisine Type</th>
											<td>
												<?php echo $field[0]["value"]; ?><?php
													$field2 = null; 
													$field2 =  $itemOfInterest->xpath("//fields/field[@key='CUISINE_TYPE2']"); 
													if ($field2 && $field2 != "") { ?>,
													<?php echo $field2[0]["value"]; ?><?php } ?>	
											</td>
										</tr>
								<?php } ?>
								<?php if ($itemOfInterest["priceCategoryCode"]) { ?>
									<tr><th>Price Range</th><td>
										<?php if ($itemOfInterest["priceCategoryCode"] == "V") { ?>
											Very Expensive
										<?php } ?>
										<?php if ($itemOfInterest["priceCategoryCode"] == "E") { ?>
											Expensive
										<?php } ?>
										<?php if ($itemOfInterest["priceCategoryCode"] == "M") { ?>
											Moderately Expensive
										<?php } else { ?>
											Well Priced
										<?php } ?>
									</td></tr>
								<?php } ?>
								<?php if ($itemOfInterest["cost"] && $itemOfInterest["cost"] != "") { ?>
									<tr>
										<th>Cost</th>
										<td><?php echo $itemOfInterest["cost"]; ?></td>
									</tr>
								<?php } ?>
								<?php 
									$field = null;
									$field =  $itemOfInterest->xpath("//fields/field[@key='CREDIT_CARDS']"); 
									if ($field && $field != "") { ?>
										<tr>
											<th><?php echo $field[0]["name"];?></th>
											<td><?php echo $field[0]["value"]; ?></td>
										</tr>
									<?php } ?>
								</table>
							</div>
							
							<br />
							
							<?php if ($itemOfInterest->summary && $itemOfInterest->summary != "") { ?>
								<p><?php echo htmlspecialchars_decode($itemOfInterest->summary);?></p>
							<?php } ?>
							<?php if ($itemOfInterest->description && $itemOfInterest->description != "") { ?>
								<p><?php echo htmlspecialchars_decode($itemOfInterest->description);?></p>
							<?php } ?>
							
							
							<?php if ($itemOfInterest->links) { ?>
								<div class="detailGroup">
									<table class="infoTable">
										<tr>
											<th>Additional Links</th>
											<td>
												<?php foreach ($itemOfInterest->links->link as $key=>$link) { ?>
													<a href="<?php if (!strpos($link["url"],"http",0)===0) echo "http://"; echo $link["url"]; ?>"><?php echo $link["name"]; ?></a><br />
												<?php } ?>
											</td>
										</tr>
									</table>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>			
			</div>
		</div>
	</body>
</html>

