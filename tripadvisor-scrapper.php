<?php

header('Content-type: application/json');

$url = "https://www.tripadvisor.com.br/Restaurant_Review-g303628-d3261455-Reviews-Vila_Dionisio-Sao_Jose_Do_Rio_Preto_State_of_Sao_Paulo.html";
$ch = curl_init();
$timeout = 5;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$html = curl_exec($ch);
curl_close($ch);

$dom = new DOMDocument();

@$dom->loadHTML($html);

$classname_nome = "heading_title";
$classname_telefone = "phone";
$classname_reviews = "rating";
$classname_imagens = "carousel";

$finder = new DomXPath($dom);
$spaner_nome = $finder->query("//*[contains(@class, '$classname_nome')]");
$spaner_telefone = $finder->query("//*[contains(@class, '$classname_telefone')]");
$spaner_reviews = $finder->query("//*[contains(@class, '$classname_reviews')]");
$spaner_imagens = $finder->query("//*[contains(@class, '$classname_imagens')]");

$nome = trim(preg_replace('/\s+/', ' ', strip_tags($dom->saveHtml($spaner_nome[0]))));
$telefone = trim(preg_replace('/\s+/', ' ', strip_tags($dom->saveHtml($spaner_telefone[0]))));
$reviews = strtok(preg_replace('/\s+/', ' ', strip_tags($dom->saveHtml($spaner_reviews[0]))), " ");
$imagens = $dom->saveHtml($spaner_imagens[0]);


//foreach(array_slice($imagens->saveHtml('img[data-src]'), 0, 3) as $imgsrc ):
//    $imglinks[] = $imgsrc;

$response = [$nome, $telefone, $reviews];
//$response = [$nome, $telefone, $reviews, $imagens];
//$response = [$nome, $telefone, $reviews, $imglinks];

echo json_encode( $response, JSON_UNESCAPED_UNICODE );
//echo json_encode( $response);