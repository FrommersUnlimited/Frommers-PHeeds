<?php


// Caching using APC cache. PHP4/PHP5
// Requires installation of the APC extension
//

error_reporting(E_ALL);

// This is the main caching routine. More robust error checking should
// be implemented.

function cache_get($key) {
	$debug = false;
	$xml = null;
	if (function_exists("apc_fetch")) {
		if($cacheItem = apc_fetch($key)) {
			?>
			<?php if ($debug){ ?>
				<!-- CACHE: Get <?php echo $key; ?> from Cache: FOUND-->
			<?php } ?>
			<?php
			$xml = new SimpleXMLElement($cacheItem); 
		} else {
			if ($debug){ ?>
				<!-- CACHE: Get <?php echo $key; ?> from Cache: NOT FOUND-->
			<?php }
		}
	} else {
		if ($debug){ ?>
			<!-- CACHE: APC_CACHE NOT INSTALLED-->
		<?php }
	}
	return $xml;
}

function cache_put($key, $obj, $ttl) {
	$debug = false;
	if (function_exists("apc_store")) {
		apc_store($key, $obj->asXML(), $ttl);
	}
	if ($debug){ ?>
		<!-- CACHE: Put <?php echo $key; ?> to Cache for <?php echo $ttl; ?>-->
	<?php } 
}

?>