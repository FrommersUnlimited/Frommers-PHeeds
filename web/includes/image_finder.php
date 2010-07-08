<?php
function findImages($locationId, $count, $type) {
	$page = 1;
	$pages = 1;
	$images = array();
	
	while($page <= $pages && count($images) < $count) { 
		if ($type == "EVENT") {
		
			$eventParameters = array();
			$eventParameters["locationId"] = $locationId;
			$eventParameters["sort"] = "rank";
			$eventParameters["page"] = $page;
			$events = callFeed("event_search.feed", $eventParameters);
			
			$page = $page + 1;
			$pages = $events["totalPageCount"];
		
			foreach ($events->eventResult as $i) { 
				if($i->image && count($images) < $count) {
					$item = array();
					$item["mediaUrl"] = $i->image["mediaUrl"];
					$item["url"] = "item_of_interest_detail.php?itemOfInterestId=" . $i["id"];
					$item["id"] = $i["id"];
					$item["name"] = $i["name"];
					$images[] = $item;
				}
			}
		} else {
			$poiParameters = array();
			$poiParameters["locationId"] = $locationId;
			$poiParameters["sort"] = "rank";
			$poiParameters["page"] = $page;
			$pois = callFeed("poi_search.feed", $poiParameters);
			
			$page = $page + 1;
			$pages = $pois["totalPageCount"];
		
			foreach ($pois->poiResult as $i) { 
				if($i->image && count($images) < $count) {
					$item = array();
					$item["mediaUrl"] = $i->image["mediaUrl"];
					$item["url"] = "item_of_interest_detail.php?itemOfInterestId=" . $i["id"];
					$item["id"] = $i["id"];
					$item["name"] = $i["name"];
					$images[] = $item;
				}
			}
			
		}
	}
	return $images;
}