
<?php if ($destinationMenu->destinationLinks) { ?>
	<div id="destinationMenu">
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
	</div>
<?php } ?>