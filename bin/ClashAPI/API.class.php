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
				$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);
		//     // Cache response for the next 1 hour if not empty
		// 		if (($retcode == 200) && (!empty($output))){
		// 			$redis->setEx($url, 3600, $output);
		// 			$redis->set($lt, $output);
		// 			$redis->persist($lt);
		// 		}
		// 		elseif ($redis->exists($lt)) {
		// 			$redis->setEx('apicrash', 3600 , '');
		// 			$output = $redis->get($lt);
		// 		}
		// 		else {
		// 			exit('Clash of Clans API nicht verfügbar. Bitte probiere es später nocheinmal.');
		// 		}
		// }


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
