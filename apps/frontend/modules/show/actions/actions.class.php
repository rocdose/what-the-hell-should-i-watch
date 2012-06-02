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
    $this->genres = $trakt->getGenres();
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
        $this->found_it = false; 
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
      if (($this->results = $trakt->searchGenre($request->getParameter('query'), 'percentage')) === false)
      {
        $this->error = true;
        $this->error_message = 'An error occured';
      }
      else
      {
        $this->found_it = false; 
      }
    }
    else if ($type == 'related')
    {
      if (($this->results = $trakt->searchRelated($request->getParameter('query'), 'percentage')) === false)
      {
        $this->error = true;
        $this->error_message = 'An error occured';
      }
      else
      {
        $this->found_it = false; 
      }
    }
  }
}
