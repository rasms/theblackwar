<?php

require_once('../vendor/autoload.php');
use Symfony\Component\HttpFoundation\Response;

date_default_timezone_set('Europe/Berlin');

$redis = new Predis\Client(getenv('REDIS_URL'));

$app = new Silex\Application();
$app['debug'] = false;


$clandetails = $redis->get('clandetails');
$clanmem = $redis->get('clanmem');
$clandetails = json_decode($clandetails);
$clanmem = json_decode($clanmem);

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
