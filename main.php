<?php

require_once __DIR__ . '/Models/Article.php';

$articles = Article::getRemote();
$results = 0;
foreach($articles as $xml){
  if(!Article::exists($xml->guid)){
    $article = Article::parseXml($xml);
    try{
      // print_r($article);
      $article->create();
      $results++;
    }catch(Exception $e){
      echo $e->getMessage() . "\n";
      continue;
    }
  }
}
echo $results . " articles processed\n";
