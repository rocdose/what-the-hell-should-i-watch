<?php

class Trakt
{
  public function searchShow($show_name)
  {
    return $this->search("shows", $show_name);
  }

  private function search($type, $query)
  {
    $query = str_replace(' ', '+', $query);
    $search = "http://api.trakt.tv/search/".$type.".json/".sfConfig::get('app_trakt_api_key')."/".$query;

    return $this->call($search);
  }

  private function call($url)
  {
    $context = stream_context_create(array(
      'http' => array(
        'header'  => "Authorization: Basic " . base64_encode(sfConfig::get('app_trakt_username').':'.sfConfig::get('app_trakt_password'))
      )
    ));
    if (($response = file_get_contents($url, false, $context)) === false)
      return false;

    if (strlen($response) <= 0)
      return false;

    return $response;
  }
}
