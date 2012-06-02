<?php
  if ($error === true):
?>
<h1>You broke me!</h1>
<?php
    echo $this->error_message;
  elseif ($found_it === true):
?>
<h1>Found it!</h1>
<?php
    echo 'So you like '.$result['genres'][0].', uh?';
?>
<span>I want <?php link_to('MORE', 'show/similar/'.$result['title']); ?></span>
<?php
  elseif (count($results) == 0):
?>
<h1>I got nothing for you, NOTHING!</h1>
<?php
  else:
?>
<h1>Here's what I've got</h1>
<?php foreach ($results as $result): ?>
<div style="width:1351px;height:200px;">
  <div style="float:left;width:250px;">
    <ul>
      <li>
        <b><a href="<?php echo "http://www.imdb.com/title/".$result['imdb_id'] ?>"><?php echo $result['title'] ?></a></b>
      <?php if ($result['ratings']['percentage'] < sfConfig::get('app_ratings_low')): ?>
          Everyone hates this and you should too.
      <?php elseif ($result['ratings']['percentage'] < sfConfig::get('app_ratings_average')): ?>
          BORING, move on.
      <?php elseif ($result['ratings']['percentage'] < sfConfig::get('app_ratings_high')): ?>
          Average boring.
      <?php elseif ($result['ratings']['percentage'] < sfConfig::get('app_ratings_top')): ?>
          Supposedly good.
      <?php else: ?>
          Fucking fantastic.
      <?php endif; ?>
        <ul>
          <li><a href="<?php echo "http://www.imdb.com/title/".$result['imdb_id']."/reviews?filter=hate" ?>">Why this stinks</a></li>
          <li><a href="<?php echo "http://www.imdb.com/title/".$result['imdb_id']."/reviews?filter=love" ?>">What stupid fans say</a></li>
          <li><a href="<?php echo "/show/search?type=related&query=".Doctrine_Inflector::urlize($result['title']); ?>">More things like this please.</a></li>
        </ul>
      </li>
    </ul>
  </div>
  <div style="float:right;width:1051px;display:inline;margin-left: auto; margin-right: 30px">
    <?php echo image_tag($result['images']['banner']) ?>
  </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
