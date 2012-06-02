<?php

class Trakt
{
  /**
   * Search show by name
   *
   * @param show_name string
   *
   * @return string JSON
   */
  public function searchShow($show_name)
  {
    if (($results = $this->search("shows", $show_name)) === false)
      return false;

    return json_decode($results);
  }

  /**
   * Search show by genre
   *
   * @param genre string
   *
   * @return string JSON
   */
  public function searchGenre($genre, $order_by = false)
  {
    $request_body = array(
      "username"          => sfConfig::get('app_trakt_username'),
      "password"          => sfConfig::get('app_trakt_password'),
      "genre"             => $genre,
      "hide_collected"    => false,
      "hide_watchlisted"  => true
    );
    
    $url = "http://api.trakt.tv/recommendations/shows/".sfConfig::get('app_trakt_api_key');

    if (($results = $this->call($url, json_encode($request_body))) === false)
      return false;

    $results = json_decode($results, true);

    return $order_by === false ? $results : $this->sort($results, $order_by);
  }


  /**
   * Get TV shows genres
   *
   * @return string JSON
   */
  public function getGenres()
  {
    $url = "http://api.trakt.tv/genres/shows.json/".sfConfig::get('app_trakt_api_key');

    if (($results = $this->call($url)) === false)
      return false;

    return json_decode($results);
  }

  /**
   * Search related shows
   *
   * @param show_name string
   *
   * @return string JSON
   */
  public function searchRelated($show_name, $order_by = false)
  {
    $url = "http://api.trakt.tv/show/related.json/".sfConfig::get('app_trakt_api_key')."/".$show_name;
    if (($results = $this->call($url)) === false)
      return false;

    $results = json_decode($results, true);

    return $order_by === false ? $results : $this->sort($results, $order_by);
  }


  /**
   * API search function
   *
   * @param type string
   * @param query string
   *
   * @return string JSON
   */
  private function search($type, $query)
  {
    $query = str_replace(' ', '+', $query);
    $url = "http://api.trakt.tv/search/".$type.".json/".sfConfig::get('app_trakt_api_key')."/".$query;

    return $this->call($url);
  }

  /**
   * API call
   *
   * @param url string
   *
   * @return string JSON
   */
  private function call($url, $post = false)
  {
    $context        = null;
    $auth_header    = "Authorization: Basic " . base64_encode(sfConfig::get('app_trakt_username').':'.sfConfig::get('app_trakt_password'));

    if ($post === false) // GET
    {
      $context = stream_context_create(array(
        'http' => array(
          'header'  => $auth_header,
        )
      ));
    }
    else // POST
    {
      $context = stream_context_create(array(
        'http' => array(
          'header'  => $auth_header,
          'method'  => 'POST',
          'content' => $post,
        ),
      ));
    }

    if (($response = @file_get_contents($url, false, $context)) === false) // can't put up with its annoying notices
      return false;

    if (strlen($response) <= 0)
      return false;

    return $response;
  }

  /**
   * Sort an array of API results by criteria
   * Proxy to sort functions
   *
   * @param $elements An array of results
   * @param $criteria An array key
   *
   * @return sorted array
   */
  private function sort($elements, $criteria)
  {
    switch ($criteria)
    {
    case 'percentage': return $this->sortByPercentage($elements);
    default: return $elements;
    }
  }

  /**
   * Sort an array of API results by ratings
   *
   * @param $elements An array of results
   *
   * @return sorted array
   */
  private function sortByPercentage($elements)
  {
    usort($elements, function($a, $b){
      return $a['ratings']['percentage'] < $b['ratings']['percentage'];
    });

    return $elements;
  }

}
