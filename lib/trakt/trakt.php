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
    return $this->search("shows", $show_name);
  }

  /**
   * Search show by genre
   *
   * @param genre string
   *
   * @return string JSON
   */
  public function searchGenre($genre)
  {
    $request_body = array(
      "username"          => sfConfig::get('app_trakt_username'),
      "password"          => sfConfig::get('app_trakt_password'),
      "genre"             => $genre,
      "hide_collected"    => false,
      "hide_watchlisted"  => true
    );
    
    $url = "http://api.trakt.tv/recommendations/shows/".sfConfig::get('app_trakt_api_key');

    return $this->call($url, json_encode($request_body));
  }


  /**
   * Get TV shows genres
   *
   * @return string JSON
   */
  public function getGenres()
  {
    $url = "http://api.trakt.tv/genres/shows.json/".sfConfig::get('app_trakt_api_key');

    return $this->call($url);
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
}
