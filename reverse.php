<?php
require __DIR__.'/vendor/autoload.php';

use GIFEndec\Color;
use GIFEndec\Encoder;
use GIFEndec\MemoryStream;
use GIFEndec\Decoder;
use GIFEndec\Frame;
use GIFEndec\Renderer;

$urlData = $_GET['url'];

$uid = uniqid();

if(strpos($urlData,'gif') === false)
{
	die("Not a gif file!");
}

$filenameIn  = $urlData;
$filenameOut = __DIR__ . '/img/' . $uid . '.gif';

$contentOrFalse = file_get_contents($filenameIn);
$bytesOrFalse = file_put_contents($filenameOut, $contentOrFalse);

$gifStream = new MemoryStream();
$gifStream->loadFromFile($filenameOut);
$gifDecoder = new Decoder($gifStream);
$gif = new Encoder();

$framearr = array();

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
