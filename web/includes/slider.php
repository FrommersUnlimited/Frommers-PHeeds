<?php
global $slideContentFile;
if (file_exists ( $slideContentFile ) ) {
	$slide = simplexml_load_file($slideContentFile);
} else {
	die;
}
?>

<div id="slider" class="closed">
	<div id="sliderPrimaryContent">
      	<div class="inner">
			<div class="overview ">
				<?php echo $slide->overview; ?>
			</div>
			<?php if ($slide->feeds) { ?>
				<div class="feedInfo">
					<h3>Feeds Called On This Page</h3>
		            <ul class="simple">
		            	<?php foreach ($slide->feeds->feed as $feed) {?>
		            		<li><a id="feed_<?php echo $feed["id"]; ?>_link" href="#feed_<?php echo $feed["id"]; ?>" class="feedDetailDisplayAction closed"><?php echo $feed["name"]; ?></a></li>
		            	<?php } ?>
		            </ul>
				</div>
			<?php } ?>
            <div class="pageList">
            	<h3>Also Available</h3>
	            <ul class="simple">
	            	<?php foreach ($slide->quickLinks->link as $link) {?>
	            		<li><a href="<?php echo $link["url"]; ?>"><?php echo $link["name"]; ?></a></li>
	            	<?php } ?>
	            </ul>
            </div>
		</div>
	</div>
	<div id="sliderSecondaryContent">
        <div id="sliderSecondaryContentInner">
        	<a href="#" class="feedDetailCloseAction">Close</a>
        	<?php foreach ($slide->feeds->feed as $feed) {?>
	        	<div id="feed_<?php echo $feed["id"]; ?>" class="feedDetail" style="display:none;">
	        		<div class="overview">
	        			<?php echo $feed->overview; ?>
	        		</div>
	        		<div class="additionalInfo">
	        			<a class="external" href="<?php echo $feed->apiLink["url"]; ?>"><strong><?php echo $feed->apiLink["name"]; ?></strong></a>
	        			<h3>Related Examples</h3>
			            <ul class="simple">
				            <?php foreach ($feed->exampleLinks->link as $link) {?>
			            		<li><a class="external" href="<?php echo $link["url"]; ?>"><?php echo $link["name"]; ?></a></li>
			            	<?php } ?>
			            </ul>
	        		</div>
	        	</div>
            <?php } ?>
        	<div style="clear:both"></div>
        </div>
   	</div>
	<div id="sliderBar">
		<div class="hoverFix">&nbsp;</div>
		<a href="#" class="slideToggleAction" id="sliderHandle">
			<span>Show All Feeds On This Page</span>
		</a>
	</div>
</div>
