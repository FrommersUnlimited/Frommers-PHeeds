<?php
namespace FrommersPheeds;
/**
 * Frommers Pheeds FeedService Interface
 * 
 * Describes all the public methods that must be implemented by any Frommers
 * Pheeds Service for interacting with the Frommers Unlimited XML feeds
 */
interface FeedServiceIF {

	/**
	 * 
	 * Returns the feed 'domain' name
	 */
	public public function get_domain();

	/**
	 * Retreives the ItemOfInterest IOI feed data as a simple dictionary
	 *
	 * @param The IOI id $id
	 * @return The associative array IOI
	 */
	public function get_ioi($id);
	
	/**
	 * 
	 * Retrieves the POI by its item_of_interest ID
	 * 
	 * @param The itemOfInterestId $id
	 */
	public function get_poi($id);
	 
	/**
	 * Retrieves the event for a particular IOI id
	 * @param The event IOI id
	 */
	public function get_event($id);

	/**
	 * 
	 * Retrieves an Attraction by its ODF IOI id
	 * @param The attraction IOI id $id
	 */
	public function get_attraction($id);
	/**
	 * 
	 * Retrieves a Nightlife POI by its oDF id
	 * @param The Nightlife POI id $id
	 */
	public function get_nightlife($id);
	
	/**
	 * 
	 * Retrieves a Hotel by its ODF IOI id
	 * @param The Hotel IOI id $id
	 */
	public function get_hotel($id);
	
	/**
	 * 
	 * Retrieves a Restaurant by its ODF IOI id
	 * @param The Restaurant IOI id $id
	 */
	public function get_restaurant($id);
	
	/**
	 *
	 * Retrieves a Shop by its ODF IOI id
	 * @param The Shopping POI id $id
	 */
	public function get_shop($id);

	/**
	 *
	 * Perform the poi_search
	 * @param dictionary of conditions $conditions
	 */
	public function poi_search($conditions=null);

	/**
	 *
	 * Load the location.feed when supplied with the locationID
	 * @param int $id The location id of the feed to parse
	 * @return data the 'view-friendly' dictionary ready for use
	 */
	public function get_location($id);

}
?>
