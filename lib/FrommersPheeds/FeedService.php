<?php
namespace FrommersPheeds;
/**
 * Frommers Pheeds FeedServiceIf implementation class that is used to
 * parse the various Frommers Unlimited XML feeds
 * into a series of dictionaries then return the
 * relevant arrays
 */
class FeedService implements FeedServiceIF {

	protected $domain;

	function __construct($domain='sisp.dev.frommers.biz') {
		$this->domain = $domain;
	}

	function get_domain() {
		return $this->domain;
	}

	// Base IOI (Item)
	function get_item($id) {
		$url = 'http://' . $this->domain . '/frommers/item_of_interest.feed?itemOfInterestId=' . $id;
		$xml = simplexml_load_file($url);

		// All the generic attributes
		$data = array(
            'id'           => (string) $xml['id'],
            'type'         => (string) $xml['type'],
            'subType'      => (string) $xml['subType'],
            'name'         => (string) $xml['name'],
            'rank'         => (string) $xml['rankName'],
            'cost'         => (string) $xml['cost'],
            'price'        => (string) $xml['priceCategoryName'],
            'description'  => (string) $xml->description,
            'latitude'     => (string) $xml->locationInfos->locationInfo['latitude'],
            'longitude'    => (string) $xml->locationInfos->locationInfo['longitude'],
            'address'      => (string) $xml->locationInfos->locationInfo->address['address'],
            'city'         => (string) $xml->locationInfos->locationInfo->address['city'],
            'state'        => (string) $xml->locationInfos->locationInfo->address['state'],
            'country'      => (string) $xml->locationInfos->locationInfo->address['country'],
            'neighborhood' => (string) $xml->locationInfos->locationInfo->address['neighborhood'],
            'telephone'    => (string) $xml->locationInfos->locationInfo->address['telephone1'],
            'fax'          => (string) $xml->locationInfos->locationInfo->address['fax'],
            'url'          => (string) $xml->links->link['url'],
            'website'      => (string) $xml->links->link['name'],
		);

		return array(
			'data' => $data,
			'xml'  => $xml,
		);
	}

	/**
	 * Retreives the ItemOfInterest IOI feed data as a simple dictionary
	 *
	 * @param The IOI id $id
	 * @return The associative array IOI
	 */
	function get_ioi($id) {
		$result = $this->get_item($id);

		$xml  = $result['xml'];
		$data = $result['data'];

		return $data;
	}

	function get_poi($id) {
		// Simply delegate to the get IOI method
		return get_ioi($id);
	}

	/**
	 * Retrieves the event for a particular IOI id
	 * @param The event IOI id
	 */
	function get_event($id) {
		$result = $this->get_item($id);

		$xml  = $result['xml'];
		$data = $result['data'];

		// Crummy way of getting the display location
		$data['display_location'] = (string) $xml->locationInfos->locationInfo['locationName'];

		// Date info
		$data['display_date']     = (string) $xml['displayDate'];
		$data['start_date']       = (string) $xml->dateRanges->dateRange['startDate'];
		$data['end_date']         = (string) $xml->dateRanges->dateRange['endDate'];

		return $data;
	}

	/**
	 * Retrieves an Attraction by its ODF IOI id
	 * @param The attraction IOI id $id
	 */
	function get_attraction($id) {
		$result = $this->get_item($id);

		$xml  = $result['xml'];
		$data = $result['data'];

		$data['opening_hours'] = (string) $xml['openingHours'];
		$data['extended_type_name'] = (string) $xml['extendedTypeName'];

		return $data;
	}

	/**
	 * Retrieves a Nightlife POI by its oDF id
	 * @param The Nightlife POI id $id
	 */
	function get_nightlife($id) {
		$result = $this->get_item($id);

		$xml  = $result['xml'];
		$data = $result['data'];

		$data['extended_type_name'] = (string) $xml['extendedTypeName'];

		return $data;
	}

	/**
	 * Retrieves a Hotel by its ODF IOI id
	 * @param The Hotel IOI id $id
	 */
	function get_hotel($id) {
		$result = $this->get_item($id);

		$xml  = $result['xml'];
		$data = $result['data'];

		$data['price_category'] = (string) $xml['priceCategoryName'];

		// Load up the extra fields
		if ($xml->fields) {
			foreach ($xml->fields->field as $f) {
				$key = (string) strtolower($f['key']); // lower_case stuff
				$data[$key] = $f['value'];
			}
		}
			
		return $data;
	}

	/**
	 * Retrieves a Restaurant by its ODF IOI id
	 * @param The Restaurant IOI id $id
	 */
	function get_restaurant($id) {
		$result = $this->get_item($id);

		$xml  = $result['xml'];
		$data = $result['data'];

		$data['price_category'] = strtolower( (string) $xml['priceCategoryName']);
		$data['opening_hours']  = (string) $xml['openingHours'];

		// Load up the extra fields
		if ($xml->fields) {
			foreach ($xml->fields->field as $f) {
				$key = (string) $f['key'];

				switch ($key) {
					case 'CREDIT_CARDS':
						$data['credit_cards'] = $f['value'];
						break;
					case 'RESERVATIONS':
						$data['reservations'] = $f['value'];
						break;
					case 'CUISINE_TYPE1':
						$data['cuisine'] = $f['value'];
						break;
				}
			}
		}
			
		return $data;
	}

	/**
	 *
	 * Retrieves a Shop by its ODF IOI id
	 * @param The Shopping POI id $id
	 */
	function get_shop($id) {
		$result = $this->get_item($id);

		$xml  = $result['xml'];
		$data = $result['data'];

		$data['opening_hours']  = (string) $xml['openingHours'];

		return $data;
	}

	/**
	 *
	 * Perform the poi_search
	 * @param dictionary of conditions $conditions
	 */
	function poi_search($conditions=null) {
		$url = 'http://' . $this->domain . '/frommers/poi_search.feed';

		if ($conditions && size($conditions > 0)) {
			$url .= '?' . build_query($conditions);
		}

		$data = array();
		try {
			$xml = simplexml_load_file($url);
				
			$data = array(
						'total'        => (int) $xml['totalResultCount'],
						'page'         => (int) $xml['currentPage'],
						'num_per_page' => (int) $xml['currentPageResultCount'],
						'num_pages'    => (int) $xml['totalPageCount'],
			);
				
			$results = array();
			foreach ($xml->poiResult as $row) {
				$results[] = array(
							'id'        => $row['id'],
							'name'      => $row['name'],
						  'type'      => $row['typeName'],
							'sub_type'  => $row['subTypeName'],
							'city'      => $row['city'],
							'country'   => $row['country'],
							'longitude' => $row['longitude'],
							'latitude'  => $row['latitude'],
							'rank'      => $row['rankId'],
							'summary'   => $row['summary'],				
				);
			}
			$data['results'] = $results;
		} catch (Exception $e) {
			$data = array(
				'error' => $e->getMessage()
			);
		}

		return $data;
	}

	/**
	 *
	 * Load the location.feed when supplied with the locationID
	 * @param int $id The location id of the feed to parse
	 * @return data the 'view-friendly' dictionary ready for use
	 */
	function get_location($id) {
		$url = 'http://' . $this->domain . '/frommers/location.feed?locationId=' . $id;

		$data = array();
		try {
			$xml = simplexml_load_file($url);

			$data = array(
				'id'        => (int) $xml['id'],
				'type'      => (int) $xml['type'],
				'name'      => (int) $xml['name'],	
				'latitude'  => (float) $xml['latitude'],	
				'longitude' => (float) $xml['longitude'],	
			);

			// need to add some processing for parent hierarch pish
				
		} catch (exception $e) {
			$data = array(
				'error' => $e->getMessage()
			);
		}

		return $data;
	}

}
?>
