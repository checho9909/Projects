<?php
ini_set('display_errors', 'Off');
session_start();
if(!isset($_SESSION['logged'])||$_SESSION['logged'] == false){
    header('Location: index.html');
}
?><?php
       session_start();
       $id = $_SESSION['id'];
       $keys = $_SESSION['keys_array'];
       $arrKeys = array_keys($keys[$id]);
       $arrVal = array_values($keys[$id]);
       ?>
       <!DOCTYPE html>
       <html lang="en">
       <head>
           <meta charset="UTF-8">
           <title>Title</title>
           <link rel="stylesheet" href="indexstyle.css">
       </head>
       <body>
       <div class="navbar">
           <span class="dot">CSV</span><span></span>
           <a href="home.php">Home</a>
           <a id='LogOut' href="index.html">Log Out</a>
       </div>
       <div class="line"></div>
       <h1>Search</h1>
       <div class="table">
           <table style="height: 200px;">
               <tr>
                   <th>Id</th>
                   <th>Year</th>
                   <th>Charges</th>
               </tr>
               <tr>
                   <td><?php echo $id; ?></td>
                   <td><?php echo $arrKeys[0] ?></td>
                   <td><?php echo $arrVal[0] ?></td>
               </tr>

           </table>
       </div>
       <form method="post" action="search.php"><input class="returnHome" class='styleBut1' type="submit" name="return"
                                                      value="Return"></form>
       </body>
       </html>
       <?php
       if (isset($_POST['return'])) {
           echo "<script>
                    window.location.href = 'view.php';
                 </script>";
       }

?>
<div class="viewSource">
<a href="/folder_view/vs.php?s=<?php echo __FILE__?>" target="_blank">View Source</a>
</div>

