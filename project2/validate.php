<?php
ini_set('display_errors', 'Off');
/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 2018-10-12
 * Time: 1:41 PM
 */

$file = 'logInfo.csv';
$content = file_get_contents($file);
$lines = explode("\n", $content);

$keys = array();
$i = 0;

for($x = 0; $x<count($lines);$x++){
    $temp = explode(".", $lines[$x]);
    $keys[$i] = $temp[0];
    $keys[$i+1] = $temp[1];
    $i+=2;
}

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if(empty($username)||!filter_var($username)){
        $emailErr = "Please enter a username";
    }
    if(empty($password)||!filter_var($password)){
        $passwordErr = "Please enter a password";
    }
}
$success = false;

for($x = 0; $x<count($keys);$x++){
    $keys[$x] = trim($keys[$x]);
    if($keys[$x] == $username){
        $keys[$x+1] = trim($keys[$x+1]);
        if($keys[$x+1]==$password){
            $success = true;
            session_start();
            $_SESSION["logged"] = true;
            include('home.php');
            break;
        }else{
            $_SESSION['logged'] = false;
            $passwordErr = "Incorrect Password";
        }
    }
}
if($success == false){
    echo "<script>alert('Could not find username');</script>";
}
if(!empty($emailErr)){
    echo "<script>alert('$emailErr');</script>";

}
if(!empty($passwordErr)){
    echo "<script>alert('$passwordErr');</script>";
}
?>
<div class="viewSource">
<a href="/folder_view/vs.php?s=<?php echo __FILE__?>" target="_blank">View Source</a>
</div>


