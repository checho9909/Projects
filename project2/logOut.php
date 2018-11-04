<?php
session_start();
session_destroy();
header('Location: index.html');
?>
<div class="viewSource">
<a href="/folder_view/vs.php?s=<?php echo __FILE__?>" target="_blank">View Source</a>
</div>
