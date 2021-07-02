<?php

require_once __DIR__ . '/../Libs/Record/Record.php';

class Article extends Record{

  const DB = 'random_data';
  const TABLE = 'google_news';
  const PRIMARYKEY = 'id';
  const APIEND = 'https://news.google.com/rss?hl=en-US&gl=US&ceid=US:en';

  public $id;
  public $title;
  public $link;
  public $guid;
  public $pubDate;
  public $description;
  public $source;

  public function __construct($guid = null){
    parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$guid);
  }
  public static function getRemote(){
    $str = file_get_contents(self::APIEND);
    $xml = new SimpleXMLElement($str);
    return $xml->channel->item;
  }
  public static function parseXml($xml){
    $a = new self();
    $a->title = (string)$xml->title;
    $a->link = (string)$xml->link;
    $a->guid = (string)$xml->guid;
    $a->pubDate = date('Y-m-d H:i:s',strtotime((string)$xml->pubDate));
    $a->description = (string)$xml->description;
    $a->source = (string)$xml->source;
    return $a;
  }
  public static function exists($guid){
    $results = $GLOBALS['db']
      ->database(self::DB)
      ->table(self::TABLE)
      ->select(self::PRIMARYKEY)
      ->where(self::PRIMARYKEY,"=","'" . $guid . "'")
      ->get();
    if(!mysqli_num_rows($results)){
      return false;
    }
    return true;
  }
}
