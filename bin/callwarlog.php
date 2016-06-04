<?php

require_once(__DIR__.'/../vendor/autoload.php');
require_once(__DIR__.'/ClashAPI/API.class.php');

date_default_timezone_set('Europe/Berlin');

$redis = new Predis\Client(getenv('REDIS_URL'));


$wl = new CoC_Warlog("#QVQRYYG");


$logcount = 1;

foreach ($wl->getItems() as $warlog)
{
  $log = new CoC_Warlog($warlog);

  $wars[$logcount] = [
    "result" => $log->getResult(),
    "endtime" => $log->getEndtime(),
    "size" => $log->getTeamsize(),
    "ctag" => $log->getClanTag(),
    "cname" => $log->getClanName(),
    "clvl" => $log->getClanLevel(),
    "cattacks" => $log->getClanAttacks(),
    "cstars" => $log->getClanStars(),
    "cdestruct" => number_format($log->getClanDestruction(), 2),
    "cexp" => $log->getClanExp(),
    "cbadgesm" =>  $log->getClanBadgeUrl("small"),
    "cbadgemd" =>  $log->getClanBadgeUrl("medium"),
    "cbadgelg" =>  $log->getClanBadgeUrl("large"),
    "otag" => $log->getOpponentTag(),
    "oname" => $log->getOpponentName(),
    "olvl" => $log->getOpponentLevel(),
    "oattacks" => $log->getOpponentAttacks(),
    "ostars" => $log->getOpponentStars(),
    "odestruct" => number_format($log->getOpponentDestruction(), 2),
    "oexp" => $log->getOpponentExp(),
    "obadgesm" =>  $log->getOpponentBadgeUrl("small"),
    "obadgemd" =>  $log->getOpponentBadgeUrl("medium"),
    "obadgelg" =>  $log->getOpponentBadgeUrl("large"),
  ];

$logcount++;

}

$redis->set('warlog', serialize($wars));
