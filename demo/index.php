<?php
try {
	require '../vendor/autoload.php';

	$demo = new \WebProxyDemo\WebProxyDemo();
	$demo->run();
} catch (\Throwable $exception) {
	echo $exception->getMessage();
	echo '<pre>';
	print_r($exception);
}