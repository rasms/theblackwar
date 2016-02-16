<?php

require_once "League.class.php";
require_once "Location.class.php";
require_once "Clan.class.php";
require_once "Member.class.php";


/**
 * Class to get JSON-decoded arrays containing information provided by SuperCell's official Clash of Clans API located at https://developer.clashofclans.com
 */



class ClashOfClans
{

private $_apiKey = null;
	/**
	 * Send a Request to SuperCell's Servers and contains the authorization-Token.
	 *
	 * @param string $url
	 * @return string; response from API (json)
	 */
	protected function sendRequest($url)
	{
		$this->_apiKey = getenv('COC_KEY');
		$proxy = getenv('FIXIE_URL');


		// create a new persistent client
		$m = new Memcached("memcached_pool");
		$m->setOption(Memcached::OPT_BINARY_PROTOCOL, TRUE);

		// some nicer default options
		$m->setOption(Memcached::OPT_NO_BLOCK, TRUE);
		$m->setOption(Memcached::OPT_AUTO_EJECT_HOSTS, TRUE);
		$m->setOption(Memcached::OPT_CONNECT_TIMEOUT, 2000);
		$m->setOption(Memcached::OPT_POLL_TIMEOUT, 2000);
		$m->setOption(Memcached::OPT_RETRY_TIMEOUT, 2);

		// setup authentication
		$m->setSaslAuthData( getenv("MEMCACHIER_USERNAME")
		                   , getenv("MEMCACHIER_PASSWORD") );

		// We use a consistent connection to memcached, so only add in the
		// servers first time through otherwise we end up duplicating our
		// connections to the server.
		if (!$m->getServerList()) {
		    // parse server config
		    $servers = explode(",", getenv("MEMCACHIER_SERVERS"));
		    foreach ($servers as $s) {
		        $parts = explode(":", $s);
		        $m->addServer($parts[0], $parts[1]);
		    }
		}

		if ($m->get($url)) {
		    // Get cached value
		    $output = $m->get($url);
		} else {
		    // Fetch filters from Stackla REST API
				// File is too old, refresh cache
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_PROXY, $proxy);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'authorization: Bearer '.$this->_apiKey //
				));
				$output = curl_exec($ch);
				curl_close($ch);
		    // Cache filters for the next 30 seconds
		    $m->set($url, $output, time() + 3600);
		}



		//
		// $ch = curl_init();
		// curl_setopt($ch, CURLOPT_URL, $url);
		// curl_setopt($ch, CURLOPT_PROXY, $proxy);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  	// 		'authorization: Bearer '.$this->_apiKey //
		// ));
		// $output = curl_exec($ch);
		// curl_close($ch);
		//
		 return $output;
	}

	/**
	 * Search all clans by name
	 *
	 * @param $searchString, the clan name, e.g. foxforcefürth
	 * @return array, search results.
	 */
	public function searchClanByName($searchString)
	{
		$json = $this->sendRequest("https://api.clashofclans.com/v1/clans?name=".urlencode($searchString));
		return json_decode($json);
	}

	/**
	 * Search for clans by using multiple parameters
	 *
	 * @param array
	 * @return array
	 */
	public function searchClan($parameters)
	{
		/*
		Array can have these indexes:
		* name (string)
		* warFrequency (string, {"always", "moreThanOncePerWeek", "oncePerWeek", "lessThanOncePerWeek", "never", "unknown"})
		* locationId (integer)
		* minMembers (integer)
		* maxMembers (integer)
		* minClanPoints (integer)
		* minClanLevel (integer)
		* limit (integer)
		* after (integer)
		* before (integer)
		For more information, take a look at the official documentation: https://developer.clashofclans.com/#/documentation
		*/

		$json = $this->sendRequest("https://api.clashofclans.com/v1/clans?".http_build_query($parameters));
		return json_decode($json);
	}


	/**
	 * Get information of a clan
	 *
	 * @param $tag, clantag. (e.g. #22UCCU0J)
	 * @return array, clan information.
	 */
	public function getClanByTag($tag) //#22UCCU0J = foxforcefürth
	{
		$json = $this->sendRequest("https://api.clashofclans.com/v1/clans/".urlencode($tag));
		return json_decode($json);
	}

	/**
	 * Get information about the membersof a clan
	 *
	 * @param $tag, clantag. (e.g. #22UCCU0J)
	 * @return array, member information.
	 */
	public function getClanMembersByTag($tag)
	{
		$json = $this->sendRequest("https://api.clashofclans.com/v1/clans/".urlencode($tag)."/members");
		return json_decode($json);
	}

	/**
	 * Get a list of all locations supported by SuperCell's Clan-System
	 *
	 * @return array, all locations.
	 */
	public function getLocationList()
	{
		$json = $this->sendRequest("https://api.clashofclans.com/v1/locations");
		return json_decode($json);
	}

	/**
	 * Get information about a location by providing it's id.
	 *
	 * @param $locationId
	 * @return array, location info.
	 */
	public function getLocationInfo($locationId) //32000094 = Germany
	{
		$json = $this->sendRequest("https://api.clashofclans.com/v1/locations/".$locationId);
		return json_decode($json);
	}

	/**
	 * Get information about all leages.
	 *
	 * @return array, league info.
	 */
	public function getLeagueList()
	{
		$json = $this->sendRequest("https://api.clashofclans.com/v1/leagues");
		return json_decode($json);
	}

	/**
	 * Get ranklist information about players or clans
	 *
	 * @param $locationId (tip: 32000006 is "International")
	 * @param (optional) $clans
	 * @return array, location info.
	 */
	public function getRankList($locationId, $clans = false) //if clans is not set to true, return player ranklist
	{
		if ($clans)
		{
			$json = $this->sendRequest("https://api.clashofclans.com/v1/locations/".$locationId."/rankings/clans");
		}
		else
		{
			$json = $this->sendRequest("https://api.clashofclans.com/v1/locations/".$locationId."/rankings/clans");
		}
		return json_decode($json);
	}
};

?>
