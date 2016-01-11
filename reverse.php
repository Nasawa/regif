<?php
require __DIR__.'/vendor/autoload.php';

use GIFEndec\Color;
use GIFEndec\Encoder;
use GIFEndec\MemoryStream;
use GIFEndec\Decoder;
use GIFEndec\Frame;
use GIFEndec\Renderer;

$local = false;

//$urlData = $_SERVER['REQUEST_URI'];

$urlData;

if($local)
	$urlData = $_SERVER['argv'][1];
else
	$urlData = $_GET['url'];

$uid = uniqid();

if(strpos($urlData,'gif') === false)
{
	die("Not a gif file!");
}

$filenameIn  = $urlData;
$filenameOut = __DIR__ . '/img/' . $uid . '.gif';

$contentOrFalse = file_get_contents($filenameIn);

if($contentOrFalse === false)
	die("Error in file_get_contents!");

$bytesOrFalse = file_put_contents($filenameOut, $contentOrFalse);

if($bytesOrFalse === false)
	die("Something brock'd! {$uid}");

$gifStream = new MemoryStream();
$gifStream->loadFromFile($filenameOut);
$framearr = array();

$gifDecoder = new Decoder($gifStream);

$gif = new Encoder();

$gifDecoder->decode(function (Frame $frame, $index) 
{
    global $framearr;
     $framearr[] = $frame;
});

foreach (array_reverse($framearr) as $frame) 
{
	$gif->addFrame($frame);
}

$gif->addFooter();
$gif->getStream()->copyContentsToFile(__DIR__ . '/out/'. $uid .'.gif');

$returner = './out/'. $uid .'.gif';
$fp = fopen($returner, 'rb');

header("Content-Type: image/gif");
header("Content-Length: " . filesize($returner));

fpassthru($fp);
