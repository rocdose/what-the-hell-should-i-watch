<?php

/**
 * show actions.
 *
 * @package    what-the-hell-should-i-watch
 * @subpackage show
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class showActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $trakt = new Trakt();
    if (($this->genres = $trakt->getGenres()) !== false)
    {
      $this->genres = json_decode($this->genres);
    }
  }

  /**
   * Executes search action
   *
   * @param sfRequest $request A request object
   */
  public function executeSearch(sfWebRequest $request)
  {
    $query = $request->getParameter('query');
    $type  = $request->getParameter('type');
    $this->error = false;
    $trakt = new Trakt();
    if ($type == 'title')
    {
      if (($results = $trakt->searchShow($query)) === false)
      {
        $this->error = true;
        $this->error_message = 'An error occured';
      }
      else
      {
        $results = json_decode($results, true);
        $this->found_it = false; // we have an exact match
        foreach ($results as $result)
        {
          if (strcasecmp($result['title'], $query) == 0) // match
          {
            $this->found_it = true;
            $this->result   = $result;
            break;
          }
        }
        if (!$this->found_it)
        {
          $this->results = $results;
        }
      }
    }
    else if ($type == 'genre')
    {
      if (($results = $trakt->searchGenre($request->getParameter('query'))) === false)
      {
        $this->error = true;
        $this->error_message = 'An error occured';
      }
      else
      {
        $this->results = json_decode($results, true);
        $this->found_it = false; // we have an exact match
      }
    }
  }
}
