<?php
ini_set('display_errors', 'Off');
session_start();
if(!isset($_SESSION['logged'])||$_SESSION['logged'] == false){
    header('Location: index.html');
}
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
            <a id='LogOut' href="logOut.php">Log Out</a>
        </div>
        <div class="line"></div>
        <div class="lineBar"></div>
        <h1 class="title">CSV MANAGER</h1><br>
        <h1 style="padding-left: 460px;">What do you want to do?</h1>
        <div class="container">
            <div class="edit">
                <div class="img"><img src="edit.png" height="100" width="100"><br><br>
                    <form action="modify.php"><input type="submit" class="editBut" name="editBut" value="Edit CSV">
                    </form>
                </div>
            </div>
            <div class="space"></div>
            <div class="see">
                <div class="img1"><img src="view.png" height="70" width="100"><br><br>
                    <form action="view.php"><input type="submit" class="editBut" name="viewBut" value="View CSV"></form>
                </div>
            </div> <!--<button class="editBut">View CSV</button></div></div>-->
        </div>
        <div class="lineBar" style="margin-top: 40px"></div>
        <div class="about">
            <h1>About CSV Manager</h1>
            <p style="margin-top: 100px">With CSV Manager you are able to: </p>
            <ul>
                <li>Upload a CSV file</li>
                <li>View your CSV file</li>
                <li>Edit a CSV file</li>
            </ul>
        </div>

        </body>
        </html>
        <?php
        $_SESSION['logged'] = true;
        if (isset($_POST['editBut'])) {
            header("Location: modify.php");
        }
        if (isset($_POST['viewBut'])) {
            header("Location: view.php");
        }


?>
<div class="viewSource">
<a href="/folder_view/vs.php?s=<?php echo __FILE__?>" target="_blank">View Source</a>
</div>

