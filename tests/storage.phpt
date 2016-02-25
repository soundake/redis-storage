<?php
use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../src/RedisStorage.php';

\Tester\Environment::setup();

$client = new \Predis\Client();

$o = new \sndk\RedisStorage($client);

$client->set('test1','test1data');
$client->set('test2','test2data');
$client->set('test3','test3data');

Assert::null($o->write('test4','test4data',[]));

Assert::same('test1data', $o->read('test1'));

Assert::same('test4data', $o->read('test4'));

Assert::null($o->remove('test1'));
Assert::null($client->get('test1'));


Assert::null($o->clean(['test2','test3','test4']));
Assert::null($client->get('test2'));
Assert::null($client->get('test3'));
Assert::null($client->get('test4'));
