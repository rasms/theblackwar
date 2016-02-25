<?php

require('../vendor/autoload.php');
require_once "./ClashAPI/API.class.php";
use Symfony\Component\HttpFoundation\Response;

date_default_timezone_set('Europe/Berlin');


$app = new Silex\Application();
$app['debug'] = false;


$clan = new CoC_Clan("#QVQRYYG");


$tottroph = 0;
$totlvl = 0;

foreach ($clan->getAllMembers() as $clanmember)
{
	$member = new CoC_Member($clanmember);
  $league = new CoC_League($member->getLeague());
  $donationsReceivedCalc = $member->getDonationsReceived();
	if ($donationsReceivedCalc == 0) $donationsReceivedCalc++;

	$ratio = $member->getDonations() / $donationsReceivedCalc;

  $tottroph = $tottroph + $member->getTrophies();
  $totlvl = $totlvl + $member->getLevel();

  $clanmem[$member->getClanRank()] = [
    "rank" => $member->getClanRank(),
    "prevrank" => $member->getPreviousClanRank(),
    "name" => $member->getName(),
    "role" => $member->getRole(),
    "trophies" => $member->getTrophies(),
    "donations" => $member->getDonations(),
    "received" => $member->getDonationsReceived(),
    "ratio" => number_format($ratio, 2),
    "level" => $member->getLevel(),
    "leaguename" => $league->getLeagueName(),
    "leagueid" => $league->getLeagueId(),
    "leagueicontn" => $league->getLeagueIcon("tiny"),
    "leagueiconsm" => $league->getLeagueIcon("small"),
    "leagueiconmd" => $league->getLeagueIcon("medium"),
  ];

}

$avgtroph = round($tottroph / $clan->getMemberCount(), 0;
$avglvl = round($totlvl / $clan->getMemberCount(), 0);

$clandetails = [
  "badgesm" =>  $clan->getBadgeUrl("small"),
  "badgemd" =>  $clan->getBadgeUrl("medium"),
  "badgelg" =>  $clan->getBadgeUrl("large"),
  "name" => $clan->getName(),
  "level" => $clan->getLevel(),
  "description" => $clan->getDescription(),
  "wins" => $clan->getWarWins(),
  "points" => $clan->getPoints(),
  "freq" => $clan->getWarFrequency(),
  "membercount" => $clan->getMemberCount(),
  "avgtroph" => $avgtroph,
  "avglvl" => $avglvl,
];

$app['clandetails'] = $clandetails;
$app['clanmem'] = $clanmem;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));


// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers
$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});

$app->get('/mitglieder', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('mitglieder.twig');
});

$app->get('/clanregeln', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('clanregeln.twig');
});

$app->get('/clanwar', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('clanwar.twig');
});

$app->get('/impressum', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('impressum.twig');
});

$app->get('/datenschutz', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('datenschutz.twig');
});

$app->error(function (\Exception $e, $code) use($app) {
    switch ($code) {
        case 404:
            $message = $app['twig']->render('404.twig');
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    return new Response($message);
});


$app->run();
