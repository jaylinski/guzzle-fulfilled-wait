<?php

require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\Promise;

$data = [1, 2, 3];

// Normal promise
echo 'Testing normal promise:' . PHP_EOL . PHP_EOL;
$promise = new Promise(function () use (&$promise, $data) {
    echo '1 $promise resolving wait fn' . PHP_EOL;
    $promise->resolve($data);
});
$promise->then(function () use ($data) {
    echo '2 $promise->then() called' . PHP_EOL;
});

echo '3 BEFORE $promise->wait()' . PHP_EOL;
$promise->wait();
echo '4 AFTER $promise->wait()' . PHP_EOL . PHP_EOL;

// Fulfilled promise
echo 'Testing fulfilled promise:' . PHP_EOL . PHP_EOL;
$promiseFulFilled = new FulfilledPromise($data);
$promiseFulFilled->then(function () use ($data) {
    echo '1 $promise resolving wait fn' . PHP_EOL; // <- will only get called if we run the global queue promise
});

echo '2 BEFORE $promiseFulFilled->wait()' . PHP_EOL;
$promiseFulFilled->wait();
echo '3 AFTER $promiseFulFilled->wait()' . PHP_EOL . PHP_EOL;

// Fulfilled promise (working as expected)
echo 'Testing fulfilled promise (fixed):' . PHP_EOL . PHP_EOL;
$promiseFulFilledFixed = new FulfilledPromise($data);
$promiseFulFilledPromise = $promiseFulFilledFixed->then(function () use ($data) {
    echo '1 $promiseFulFilledFixed->then() called' . PHP_EOL;
});

echo '2 BEFORE $promiseFulFilledPromise->wait()' . PHP_EOL;
$promiseFulFilledPromise->wait();
echo '3 AFTER $promiseFulFilledPromise->wait()' . PHP_EOL . PHP_EOL;