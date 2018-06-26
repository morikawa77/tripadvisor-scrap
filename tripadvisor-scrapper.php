<?php

header('Content-type: application/json; charset=utf-8');

if ( isset($_POST["url"]) ) {

    $url = $_POST["url"];
    $status = '';

    if( strstr($url, "Restaurant", true) ) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $html = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }
        curl_close($ch);

        $dom = new DOMDocument();

        @$dom->loadHTML($html);

        $classname_nome = "heading_title";
        $classname_telefone = "phone";
        $classname_reviews = "rating";
        $classname_imagens = "onDemandImg";

        $finder = new DomXPath($dom);
        $spaner_nome = $finder->query("//*[contains(@class, '$classname_nome')]");
        $spaner_telefone = $finder->query("//*[contains(@class, '$classname_telefone')]");
        $spaner_reviews = $finder->query("//*[contains(@class, '$classname_reviews')]");
        $spaner_imagens = $finder->query("//*[contains(@class, '$classname_imagens')]");

        $nome = trim(preg_replace('/\s+/', ' ', strip_tags($dom->saveHtml($spaner_nome[0]))));
        $telefone = trim(preg_replace('/\s+/', ' ', strip_tags($dom->saveHtml($spaner_telefone[0]))));
        $reviews = strtok(preg_replace('/\s+/', ' ', strip_tags($dom->saveHtml($spaner_reviews[0]))), " ");

        $imagens = [];

        foreach ($spaner_imagens as $spaner_imagem) {
            $imagens[] = preg_replace('/\s+/', '', $spaner_imagem->getAttribute('data-src'));
        }


        $response = [nome => $nome, telefone => $telefone, reviews => $reviews, imagens => $imagens];

        $json = json_encode($response, JSON_UNESCAPED_SLASHES);

        echo $json;
    }else{
        $status = "Critério da pesquisa inválido, verifique os dados e tente novamente";
        $response = [ status => $status];
        $json = json_encode($response, JSON_UNESCAPED_SLASHES);

        echo $json;
    }
}
?>