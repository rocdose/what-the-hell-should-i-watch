<?php
  if ($error === true):
    echo $this->error_message;
  elseif ($found_it === true):
    echo 'So you like '.$result['genres'][0].', uh?';
  elseif (count($results) == 0):
    echo 'I got nothing';
  else:
    echo "I couldn't find an exact match so here's what I've got:";
?>
<ul>
<?php foreach ($results as $result): ?>
<li><?php echo $result['title']; ?></li>
</ul>
<?php endforeach; ?>
<?php endif; ?>
