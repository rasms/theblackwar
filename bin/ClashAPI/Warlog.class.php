<?php

class CoC_Warlog
{
	protected $api;
	protected $tag;
	protected $log = NULL;

	/**
	 * Constructor of CoC_Clan
	 * Either pass the clan tag or a stdClass containing all clan information.
	 *
	 * @param $tagOrClass
	 * @param (optional) $isTag
	 */
	public function __construct($tagOrClass)
	{
		$this->api = new ClashOfClans();
		if(is_string($tagOrClass))
		{
			$this->tag = $tagOrClass;
			$this->getWarlog();
		}
		else
		{
			$this->log = $tagOrClass;
		}

		}

		protected function getWarlog()
		{
			if($this->log == NULL)
		{
			$this->log = $this->api->getClanWarlogByTag($this->tag);
		}
		return $this->log;
		}

		public function getTag()
		{
			return $this->tag;
		}

		public function getItems()
		{
			return $this->getWarlog()->items;
		}

	/**
	 * Gets the members name
	 *
	 * @return string, name
	 */
	public function getResult()
	{
		return $this->getWarlog()->result;
	}

	/**
	 * Gets the members role ("member", "admin", "coLeader", "leader")
	 *
	 * @return string, role
	 */
	public function getEndtime()
	{
		return $this->getWarlog()->endTime;
	}

	/**
	 * Gets the members level
	 *
	 * @return int, level
	 */
	public function getTeamsize()
	{
		return $this->getWarlog()->teamSize;
	}

	/**
	 * Gets the members league ID
	 *
	 * @return int, league ID
	 */
	public function getClanTag()
	{
		return $this->getWarlog()->clan->tag;
	}

	public function getClanName()
	{
		return $this->getWarlog()->clan->name;
	}


	public function getClanLevel()
	{
		return $this->getWarlog()->clan->clanLevel;
	}


	public function getClanAttacks()
	{
		return $this->getWarlog()->clan->attacks;
	}

	public function getClanStars()
	{
		return $this->getWarlog()->clan->stars;
	}

	public function getClanDestruction()
	{
		return $this->getWarlog()->clan->destructionPercentage;
	}

	public function getClanExp()
	{
		return $this->getWarlog()->clan->expEarned;
	}

	public function getClanBadgeUrl($size = "") //small, large, medium.
	{
		switch ($size)
		{
			case "small":
				return $this->getWarlog()->clan->badgeUrls->small;
				break;
			case "medium":
				return $this->getWarlog()->clan->badgeUrls->medium;
				break;
			case "large":
				return $this->getWarlog()->clan->badgeUrls->large;
				break;
			default:
				return $this->getWarlog()->clan->badgeUrls->large; //return the largest because it can be resized using HTML
				break;
		}

	}



	/**
	 * Gets the opponent
	 *
	 * @return int, league ID
	 */
	public function getOpponentTag()
	{
		return $this->getWarlog()->opponent->tag;
	}

	public function getOpponentName()
	{
		return $this->getWarlog()->opponent->name;
	}


	public function getOpponentLevel()
	{
		return $this->getWarlog()->opponent->clanLevel;
	}


	public function getOpponentAttacks()
	{
		return $this->getWarlog()->opponent->attacks;
	}

	public function getOpponentStars()
	{
		return $this->getWarlog()->opponent->stars;
	}

	public function getOpponentDestruction()
	{
		return $this->getWarlog()->opponent->destructionPercentage;
	}

	public function getOpponentExp()
	{
		return $this->getWarlog()->opponent->expEarned;
	}

	public function getOpponentBadgeUrl($size = "") //small, large, medium.
	{
		switch ($size)
		{
			case "small":
				return $this->getWarlog()->opponent->badgeUrls->small;
				break;
			case "medium":
				return $this->getWarlog()->opponent->badgeUrls->medium;
				break;
			case "large":
				return $this->getWarlog()->opponent->badgeUrls->large;
				break;
			default:
				return $this->getWarlog()->opponent->badgeUrls->large; //return the largest because it can be resized using HTML
				break;
		}

	}


}

?>
