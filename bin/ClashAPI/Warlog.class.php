<?php

class CoC_Warlog
{
	protected $warlogObj;

	/**
	 * Constructor of CoC_Member
	 *
	 * @param $warlogObj, obtained by Clan.class.php
	 */
	public function __construct($warlogObj)
	{
		$this->warlogObj = $warlogObj;
	}

	/**
	 * Gets the members name
	 *
	 * @return string, name
	 */
	public function getResult()
	{
		return $this->warlogObj->result;
	}

	/**
	 * Gets the members role ("member", "admin", "coLeader", "leader")
	 *
	 * @return string, role
	 */
	public function getEndtime()
	{
		return $this->warlogObj->endTime;
	}

	/**
	 * Gets the members level
	 *
	 * @return int, level
	 */
	public function getTeamsize()
	{
		return $this->warlogObj->teamSize;
	}

	/**
	 * Gets the members league ID
	 *
	 * @return int, league ID
	 */
	public function getClanTag()
	{
		return $this->warlogObj->clan->tag;
	}

	public function getClanName()
	{
		return $this->warlogObj->clan->name;
	}


	public function getClanLevel()
	{
		return $this->warlogObj->clan->clanLevel;
	}


	public function getClanAttacks()
	{
		return $this->warlogObj->clan->attacks;
	}

	public function getClanStars()
	{
		return $this->warlogObj->clan->stars;
	}

	public function getClanDestruction()
	{
		return $this->warlogObj->clan->destructionPercentage;
	}

	public function getClanExp()
	{
		return $this->warlogObj->clan->expEarned;
	}

	public function getClanBadgeUrl($size = "") //small, large, medium.
	{
		switch ($size)
		{
			case "small":
				return $this->warlogObj->clan->badgeUrls->small;
				break;
			case "medium":
				return $this->warlogObj->clan->badgeUrls->medium;
				break;
			case "large":
				return $this->warlogObj->clan->badgeUrls->large;
				break;
			default:
				return $this->warlogObj->clan->badgeUrls->large; //return the largest because it can be resized using HTML
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
		return $this->warlogObj->opponent->tag;
	}

	public function getOpponentName()
	{
		return $this->warlogObj->opponent->name;
	}


	public function getOpponentLevel()
	{
		return $this->warlogObj->opponent->clanLevel;
	}


	public function getOpponentAttacks()
	{
		return $this->warlogObj->opponent->attacks;
	}

	public function getOpponentStars()
	{
		return $this->warlogObj->opponent->stars;
	}

	public function getOpponentDestruction()
	{
		return $this->warlogObj->opponent->destructionPercentage;
	}

	public function getOpponentExp()
	{
		return $this->warlogObj->opponent->expEarned;
	}

	public function getOpponentBadgeUrl($size = "") //small, large, medium.
	{
		switch ($size)
		{
			case "small":
				return $this->warlogObj->opponent->badgeUrls->small;
				break;
			case "medium":
				return $this->warlogObj->opponent->badgeUrls->medium;
				break;
			case "large":
				return $this->warlogObj->opponent->badgeUrls->large;
				break;
			default:
				return $this->warlogObj->opponent->badgeUrls->large; //return the largest because it can be resized using HTML
				break;
		}

	}


}

?>
