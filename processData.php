<?php
$dataArray = array();
if ($handle = opendir('data')) {
    while (false !== ($file = readdir($handle))) {
        if (pathinfo($file, PATHINFO_EXTENSION) == 'json') {
            $dataArray = array_merge($dataArray, json_decode(file_get_contents('data/'.$file),true));
        }
    }
    closedir($handle);
}

$id = 0;
$resultArray = array();

foreach($dataArray as $entry){
    $resultArray[] = array(
		'uid' => ++$id,
		'arg' => trim($entry['char']),
		'title' => ("" !== trim($entry['shortcut'])) ? $entry['shortcut'] : $entry['name'],
		'subtitle' => trim($entry['name']),
		'icon' => (isset($entry['image'])) ? $entry['image'] : 'images/'.$id.'.png',
		'tags' => trim($entry['tags']).' '.trim($entry['name'])
	);
	if(!isset($entry['image']))
	{
	$tmpImg = imagecreatefrompng('template.png');
    $color = ImageColorAllocate ($tmpImg, 50, 50, 50);
	imagettftext($tmpImg, 20, 0, 10, 32, $color, '/Library/Fonts/Microsoft/Arial.ttf', $entry['char']);
	imagesavealpha($tmpImg, true); 

	imagepng($tmpImg, 'images/'.$id.'.png');
	}
}
file_put_contents('cache/data.php', '<?php $data = '.var_export($resultArray, true).';');