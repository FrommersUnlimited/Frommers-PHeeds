<?php
global $slideContentFile;
global $slideQuickLinksFile;
if (file_exists ( $slideContentFile ) && file_exists( "content/slide_quick_links.xml" ) ) {
	$slide = simplexml_load_file($slideContentFile);
	$quickLinks = simplexml_load_file("content/slide_quick_links.xml");
} else {
	die('Cannot find slide content files!');
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
		            		<li><a id="feed_<?php echo $feed["code"]; ?>_link" href="#feed_<?php echo $feed["code"]; ?>" class="feedDetailDisplayAction closed"><?php echo $feed["name"]; ?></a></li>
		            	<?php } ?>
		            </ul>
				</div>
			<?php } ?>
            <div class="pageList">
            	<h3>Other Example Pages</h3>
                <!-- Split list in 2 -->
                <?php 
                    $exampleCount = count($quickLinks->link);
                    $split = round($exampleCount / 2);
                ?>
                <ul class="simple">
                    <?php for ($i=0; $i<$split; $i++) {
                        $link = $quickLinks->link[$i];
                    ?>
                        <li><a href="<?php echo $link["url"]; ?>"><?php echo $link["name"]; ?></a></li>
                    <?php }?>    
	            </ul>
                <ul class="simple">
                    <?php for ($i=(int)$split; $i<$exampleCount; $i++) {
                        $link = $quickLinks->link[$i];
                    ?>
                        <li><a href="<?php echo $link["url"]; ?>"><?php echo $link["name"]; ?></a></li>
                    <?php }?>
                </ul>
            </div>
		</div>
	</div>
	<div id="sliderSecondaryContent">
        <div id="sliderSecondaryContentInner">
        	<a href="#" class="feedDetailCloseAction">Close</a>
        	<?php foreach ($slide->feeds->feed as $feed) {?>
	        	<div id="feed_<?php echo $feed["code"]; ?>" class="feedDetail" style="display:none;">
	        		<div class="overview">
                        <h3><?php echo $feed["name"]; ?></h3>
	        			<?php echo $feed->overview; ?>
	        		</div>
	        		<div class="additionalInfo">
                        <h3>Find out more on support.frommers.biz</h3>
	        			<ul class="simple">
                            <li><a class="external" href="http://support.frommers.biz/api-reference/#<?php echo $feed["code"]; ?>">View API Reference for <?php echo $feed["name"]; ?> feed</a></li>
				            <?php if ($feed["exampleLink"]) {?>
			            		<li><a class="external" href="http://support.frommers.biz/examples/<?php echo $feed["code"]; ?>">View Example for <?php echo $feed["name"]; ?></a></li>
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
			<span>Show all feeds on this page</span>
		</a>
	</div>
</div>
