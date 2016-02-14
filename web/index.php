<?php

require('../vendor/autoload.php');
require_once "./ClashAPI/API.class.php";
use Symfony\Component\HttpFoundation\Response;

date_default_timezone_set('Europe/Berlin');



$app = new Silex\Application();
$app['debug'] = true;


$clan = new CoC_Clan("#QVQRYYG");
$clandetails = [
  "badgesm" =>  $clan->getBadgeUrl("small"),
  "badgemd" =>  $clan->getBadgeUrl("medium"),
  "badgelg" =>  $clan->getBadgeUrl("large"),
  "name" => $clan->getName(),
  "level" => $clan->getLevel(),
  "description" => $clan->getDescription(),
];

foreach ($clan->getAllMembers() as $clanmember)
{
	$member = new CoC_Member($clanmember);
  $league = new CoC_League($member->getLeague());
  $donationsReceivedCalc = $member->getDonationsReceived();
	if ($donationsReceivedCalc == 0) $donationsReceivedCalc++;

	$ratio = $member->getDonations() / $donationsReceivedCalc;

  $role = $member->getRole();
  if ($role == 'admin' ){
    $role = 'elder';
  }

  $clanmem[$member->getClanRank()] = [
    "rank" => $member->getClanRank(),
    "name" => $member->getName(),
    "role" => $role,
    "trophies" => $member->getTrophies(),
    "donations" => $member->getDonations(),
    "received" => $member->getDonationsReceived(),
    "ratio" => number_format($ratio, 2),
    "level" => $member->getLevel(),
    "leaguename" => $league->getLeagueName(),
    "leagueicontn" => $league->getLeagueIcon("tiny"),
    "leagueiconsm" => $league->getLeagueIcon("small"),
    "leagueiconmd" => $league->getLeagueIcon("medium"),
  ];

}


$app['clandetails'] = $clandetails;
$app['clanmem'] = $clanmem;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
  'http_cache.cache_dir' => __DIR__.'/cache/',
));


// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers
$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  //return $app['twig']->render('index.twig');
  $body = $app['twig']->render('index.twig');
  return new Response($body, 200, array('Cache-Control' => 's-maxage=3600, public'));
});

$app->get('/clan', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  //return $app['twig']->render('clan.twig');
  $body = $app['twig']->render('clan.twig');
  return new Response($body, 200, array('Cache-Control' => 's-maxage=3600, public'));
});

//$app['http_cache']->run();

if ($app['debug']) {
 $app->run();
 }
 else{
 $app['http_cache']->run();
 }
