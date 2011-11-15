<?php
define("BASE_MEDIA_URL", "http://media.wiley.com");
include('cache.inc.php');

function callFeed($feedName, $parameters, $ttl = 15) {
	$debug = false;
	$baseURL = "http://demosite.frommers.biz/frommers/" . $feedName;

	foreach ($parameters as $key=>$value) {
		if (strpos($baseURL, "?")) {
			$baseURL = $baseURL . "&";
		} else {
			$baseURL = $baseURL . "?";
		}
		$baseURL = $baseURL . $key . "=" . $value;
	}

	if ($debug){
		?><!-- REQUEST: <?php echo $baseURL; ?> --><?php
	}

	$key = md5($baseURL);

	if($ttl < 0 || (!$xml = cache_get($key))) {
		try {
			// is curl installed?
		    if (!function_exists('curl_init')){
		        die('CURL is not installed!');
		    }

		    // create a new curl resource
		    $ch = curl_init();

		    // set URL to download
		    curl_setopt($ch, CURLOPT_URL, $baseURL);

		    // remove header? 0 = yes, 1 = no
		    curl_setopt($ch, CURLOPT_HEADER, 0);

		    // should curl return or print the data? true = return, false = print
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		    // timeout in seconds
		    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

		    // download the given URL, and return output
		    $response = curl_exec($ch);

		    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		    if ($httpCode != "200") {
		    	return false;
		    }

		    // close the curl resource, and free system resources
		    curl_close($ch);

		} catch (Exception $e)
		{
		    return false;
		}

		if ($debug) {
			?><div style="display:none;">
				<!-- RESPONSE:
				<?php echo $response?>
				-->
			</div><?php
		}

	    // convert respone into simple xml element
		try {
		    $xml = new SimpleXMLElement($response);
		}
		catch (Exception $e)
		{
		    return false;
		}

	   	if ($ttl > 0) {
			cache_put($key, $xml, $ttl);
	   	}
	}
	return $xml;
}



function createMenuLink($feedCode, $feedQuery) {

	$queryParts = explode("&", $feedQuery);

	$parameters = array();
    foreach($queryParts as $q)
    {
        list($key, $value) = explode("=", $q);
        $parameters[$key] = $value;
    }

	$u = "";
	if ($feedCode=="guide_structure") {
		$u = "guide_detail.php?guideStructureId=" . $parameters["guideStructureId"];
	} else if ($feedCode=="event_search") {
		$u = "item_of_interest_list.php?type=EVENT&locationId=" . $parameters["locationId"];
	} else if ($feedCode=="poi_search") {
		$u = "item_of_interest_list.php?type=" . $parameters["type"] . "&locationId=" . $parameters["locationId"];
	}

	return $u;
}

function getGuideStructureIdFromUrl($url) {
	$query = parse_url($url, PHP_URL_QUERY);
	$query = htmlspecialchars_decode($query);
	$queryParts = explode("&", $query);

	$parameters = array();
    foreach($queryParts as $q)
    {
        list($key, $value) = explode("=", $q);
        $parameters[$key] = $value;
    }

	return $parameters["guideStructureId"];
}

function createLocationListItems($xml) {
	if ($xml) {
		$parent = $xml->parent;
		createLocationListItems($parent);
		?><li><a href="location_detail.php?locationId=<?php echo $xml["id"]; ?>"><?php echo $xml["name"]; ?></a></li><?php
	}
}

function exculdeParametersFromUrl($parameter1,$parameter2) {

  	$output = "?";
  	$firstRun = true;

  	foreach($_GET as $key=>$val)
  	{
  	 if($key != $parameter1 && $key != $parameter2)
   		{
   		 if(!$firstRun)
   			 {
    			 $output .= "&";
    		}
   		 else
    		{
     			$firstRun = false;
    		}
    	$output .= $key."=".urlencode($val);
   		}
  	}


  return htmlentities($output);
}