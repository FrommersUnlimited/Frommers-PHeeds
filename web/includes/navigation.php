<div id="destinationMenu">
<?php if ($destinationMenu->destinationLinks) { ?>
		<div class="feedContent">
			<div class="feedName"><a href="#feed_destination_menu" class="feedViewAction">destination_menu.feed</a></div>
			<ul>
			<?php foreach ($destinationMenu->destinationLinks->destinationLink as $key=>$destinationLink ) { ?>
				<li><a href="<?php echo createMenuLink($destinationLink["feedCode"],$destinationLink["feedQuery"]);?>"><?php echo $destinationLink["name"];?></a>
				<?php if ($destinationLink->children) {?>
					<ul>
						<?php foreach ($destinationLink->children->destinationLink as $key=>$destinationLink ) { ?>
						 	<li><a href="<?php echo createMenuLink($destinationLink["feedCode"],$destinationLink["feedQuery"]);?>"><?php echo $destinationLink["name"];?></a></li>
						<?php } ?>
					</ul>
				<?php } ?>
				</li>
			<?php } ?>
			</ul>
		</div>
<?php } ?>

<?php if (isset($audienceInterestResults) && $audienceInterestResults) {
	$newUrl=exculdeParametersFromUrl("audienceInterestId",NULL); ?>
		<div class="feedContent">
			<div class="feedName"><a href="#feed_audience_interest_search" class="feedViewAction">audience_interest_search.feed</a></div>
			<ul>
			<?php foreach ($audienceInterestResults as $audienceInterest ) { ?>
				<li><a href="<?php echo $newUrl."&audienceInterestId=".urlencode($audienceInterest["id"]) ?>"><?php echo $audienceInterest["name"];?> &nbsp;&nbsp;(<?php echo $audienceInterest["eventCount"];?>)</a> <a href="<?php echo $newUrl ?>">x</a>
				<?php if ($audienceInterest->children) {?>
					<ul>
						<?php foreach ($audienceInterest->children->audienceInterestResult as $audienceInterestChild ) { ?>
						 	<li><a href="<?php echo $newUrl."&audienceInterestId=".urlencode($audienceInterestChild["id"]) ?>"><?php echo $audienceInterestChild["name"];?>&nbsp;&nbsp;(<?php echo $audienceInterestChild["eventCount"];?>)</a></li>
						<?php } ?>
					</ul>
				<?php } ?>
				</li>
			<?php } ?>
			</ul>
		</div>
<?php } ?>

<?php if (isset($calendarResults) && $calendarResults) {
	$newUrl=exculdeParametersFromUrl("startDate","endDate"); ?>
		<div class="feedContent">
			<div class="feedName"><a href="#calendar_event_search" class="feedViewAction">calendar_event_search.feed</a></div>
			<ul><li> <a href="#">Date</a>
			   <ul><?php foreach ($calendarResults as $calendarResult ) { ?>
					<li><a href="<?php echo $newUrl."&startDate=".urlencode($calendarResult["day"])."&endDate=".urlencode($calendarResult["day"]); ?>"><?php echo date("D M j",strtotime($calendarResult["day"]));?>&nbsp;&nbsp;(<?php echo $calendarResult["count"];?>)</a></li>
				   <?php } ?>
			   </ul>
			</li></ul>
		</div>
<?php } ?>

</div>
