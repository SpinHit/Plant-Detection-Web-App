<?php

function encodeImages($images){
	$encoded_images = array();
	foreach($images as $image){
		$encoded_images[] = base64_encode(file_get_contents($image));
	}
	return $encoded_images;
}
function identifyPlants($file_names){
	$encoded_images = encodeImages($file_names);
	$api_key = "7h5NT5OfARgWmqQunNNnpk3s8eej94rRngfhwrxRWF5hqaKjQh";
	$params = array(
		"api_key" => $api_key,
		"images" => $encoded_images,
		// modifiers docs: https://github.com/flowerchecker/Plant-id-API/wiki/Modifiers
		"modifiers" => ["crops_fast", "similar_images"],
		"plant_language" => "fr",
		// plant details docs: https://github.com/flowerchecker/Plant-id-API/wiki/Plant-details
		"plant_details" => array("common_names",
							"url",
							"name_authority",
							"wiki_description",
							"taxonomy",
							"synonyms"),
		);
	$params = json_encode($params);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.plant.id/v2/identify");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json"));
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
$img = $_POST['imgBase64'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$fileData = base64_decode($img);
//saving
$fileName = 'tmp.png';
file_put_contents($fileName, $fileData);


echo identifyPlants(['tmp.png']);

?>