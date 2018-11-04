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
        <a id='LogOut' href="index.html">Log Out</a>
    </div>
    <div class="line"></div>
    <h1>Edit CSV</h1>
    <div class="table">
        <table>
            <tr>
                <th>Id</th>
                <th>Year</th>
                <th>Charges</th>
            </tr>
            <?php
            $file = 'csv.csv';
            $content = file_get_contents($file);
            $lines = explode(",", $content);
            $totalNum = count($lines) - 1;
            $totalInd = $lines[$totalNum - 1];
            $totalNum = 0;
            for ($x = 3; $x <= count($lines); $x += 3) {
                $temp = $lines[$x];
                $temp .= ",";
                $temp .= $lines[$x + 1];
                $lines[$x] = $temp;
                unset($lines[$x + 1]);
            }
            $lines = array_values($lines);
            array_pop($lines);
            array_pop($lines);
            //print_r($lines);
            $keys = array();
            $x = 2;
            $id = 1;
            while ($x < count($lines) - 1) {
                echo "<tr>" .
                    "<td>" . $id . "</td>
                      <td id='$id' class='year'>" . $lines[$x] . "</td>
                      <td id='$id' class='charge'>" . $lines[$x + 1] . "</td>
                      <td><form method='post' action='modify.php'>
                            <input class='but' id='$id' type='submit' name='edit' value='Edit'></form></td>
                      <td><form method='post' action='modify.php'>
                            <input class='but' id='$id' type='submit' onclick='deleteOnclick(this.id)' name='delete' value='Delete'</form></td>";
                echo "</tr>";
                $totalNum += (double)$lines[$x + 1];
                $keys[$id][$lines[$x]] = $lines[$x + 1];
                $id++;
                $x += 2;
            }

            echo "<br>";
            echo "<tr><td>Total</td><td class='total'>" . $totalNum . "</td></tr>";
            ?>
        </table>
    </div>
        <div class="spanAddDiv">
            <form method="post" action="modify.php">
                <input type="submit" name="add" class="spanAdd" value="Add new Value"></form>
            <br>
            <?php
            if (isset($_POST['add'])) {
                echo "<br><br>";
                echo "<form method='post' action='modify.php'><label for='newYear'>Enter new Year:</label><br>
              <input type='text' name='newYear' placeholder='mm-mm-YYYY'><br>
              <label for='newCharge'>Enter new Charge:</label><br>
              <input type='text' name='newCharge'><br><br>
              <input type='submit' name='submitNew' value='Add'></form>";
            }
            if (isset($_POST['edit'])) {
                echo "<br><br>";
                echo "<form method='post' action='modify.php'><label for='newYearValue'>Enter new Year:</label><br>
              <input type='text' name='newYearValue' placeholder='mm-mm-YYYY'><br>
              <label for='newChargeValue'>Enter new Charge:</label><br>
              <input type='text' name='newChargeValue'><br>
              <label for='id'>Enter Id:</label>
              <input type='number' name='id'><br><br>
              <input type='submit' name='editSubmit' value='Add'></form>";
            }
            ?>
        </div>
        <script>
            function deleteOnclick(id) {
                let deleteBut = document.getElementsByClassName('delete');
                let confi = confirm('Are you sure you want to delete row with id: ' + id);
                if (confi) {
                    window.location.href = "modify.php?id=" + id;
                }
            }
            function refresh(){
                window.location.href = "modify.php";
            }
        </script>

    </body>
    </html>
    <?php
    if (isset($_POST['submitNew'])) {
        $newYear = $_POST['newYear'];
        $newCharge = $_POST['newCharge'];
        array_push($lines, $newYear);
        array_push($lines, $newCharge);
        array_push($lines, 'Total');
        $totalNum = (double)$totalNum;
        $totalNum += (double)$newCharge;
        array_push($lines, $totalNum);
        $temp = implode(",", $lines);
        file_put_contents($file, $temp);
        echo "<script>refresh()</script>";
    }

    if (isset($_POST['editSubmit'])) {
        $newYearValue = $_POST['newYearValue'];
        $newChargeValue = $_POST['newChargeValue'];
        $tempArray = array();
        array_shift($lines);
        array_shift($lines);
        $totalNum = (double)$totalNum;
        $id = $_POST['id'];
        $x = $id;
        for ($ind = 0; $ind < count($keys[$x]); $ind++) {
            unset($keys[$x]);
            $keys[$x][$newYearValue] = $newChargeValue;
        }
        $linesInd = 0;
        for ($x = 1; $x <= count($keys); $x++) {
            $keyArray = array_keys($keys[$x]);
            foreach ($keys[$x] as $year => $charge) {
                $tempArray[$linesInd] = $year;
                $tempArray[$linesInd + 1] = $charge;
                $linesInd += 2;
            }
        }
        array_push($tempArray, 'Total');
        array_push($tempArray, $totalNum);
        array_unshift($tempArray, 'CHARGES');
        array_unshift($tempArray, 'YEAR');
        $temp = implode(",", $tempArray);
        file_put_contents($file, $temp);
        echo "<script>refresh()</script>";
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        if ($id == 1) {
            for ($x = 1; $x <= count($keys); $x++) {
                if ($x == $id) {
                    unset($keys[$x]);
                }
                $keys[$x] = $keys[$x + 1];
                unset($keys[$x + 1]);

            }
        } else {
            for ($x = 1; $x <= count($keys); $x++) {
                if ($x == $id) {
                    if ($x == count($keys)) {
                        unset($keys[$x]);
                    } else {
                        $keys[$x] = $keys[$x + 1];
                        unset($keys[$x + 1]);
                    }
                }

            }
        }
        $linesInd = 0;
        for ($x = 1; $x <= count($keys); $x++) {
            $keyArray = array_keys($keys[$x]);
            foreach ($keys[$x] as $year => $charge) {
                $tempArray[$linesInd] = $year;
                $tempArray[$linesInd + 1] = $charge;
                $linesInd += 2;
            }
        }
        array_push($tempArray, 'Total');
        array_push($tempArray, $totalNum);
        array_unshift($tempArray, 'CHARGES');
        array_unshift($tempArray, 'YEAR');
        $temp = implode(",", $tempArray);
        file_put_contents($file, $temp);
        echo "<script>refresh()</script>";


    }



?>

<div class="viewSource">
<a href="/folder_view/vs.php?s=<?php echo __FILE__?>" target="_blank">View Source</a>
</div>



