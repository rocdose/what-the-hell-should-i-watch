<h1>What do you like?</h1>
<h3>Search by show</h3>
<form action="show/search?type=title" class="search-form">
  <input type="text" name="query" class="search-query"/>
  <input type="submit" class="btn"/>
</form>

<h3>Search by genre</h3>
<?php
  if ($genres === false):
    echo "I don't know anything for some reason.";
  else:
?>
<ul>
<?php foreach ($genres as $genre): ?>
  <li><a href="show/search/?type=genre&query=<?php echo $genre->slug ?>"><?php echo $genre->name ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
