<?php
/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 2018-11-03
 * Time: 2:29 PM
 */
if(isset($_GET['file'])){
    $file = $_GET['file'];
    show_source($file);
}
?>
<div class="viewSource">
<a href="/folder_view/vs.php?s=<?php echo __FILE__?>" target="_blank">View Source</a>
</div>