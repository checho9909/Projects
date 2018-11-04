<?php
//1. Validation to see if user and password match
    function validate(){
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
}
//2. Retrieve records and prints them in a table
    function displayRecords(){
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
        $keys = array();
        $x = 2;
        $id = 1;
        while ($x < count($lines) - 1) {
            echo "<tr>" .
                "<td>" . $id . "</td>
                      <td id='$id' class='year'>" . $lines[$x] . "</td>
                      <td id='$id' class='charge'>" . $lines[$x + 1] . "</td>";
            echo "</tr>";
            $totalNum += (double)$lines[$x + 1];
            $keys[$id][$lines[$x]] = $lines[$x + 1];
            $id++;
            $x += 2;
        }

        echo "<br>";
        echo "<tr><td>Total</td><td class='total'>" . $totalNum . "</td></tr>";
    }
//3. Create a new record
    function addNewRecord(){
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
        header("Location: modify.php");
    }
}
//4. Modify an existing record
    function modifyRecord()
{
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
        header("Location: modify.php");
    }
}
//5. Delete a Record
    function deleteRecord(){
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
            header("Location: modify.php");


        }
    }
//6. Search for a Record
    function searchRecord(){
        if (isset($_POST['searchBut'])) {
            $id_to_search = $_POST['search'];
            if ($id_to_search <= count($keys)) {
                session_start();
                $_SESSION['keys_array'] = $keys;
                $_SESSION['id'] = $id_to_search;
                header("Location: search.php");
            } else {
                echo "<script>alert('Id could not be found');</script>";
            }
        }
    }
//7. Export data
    function exportData(){
        echo "<script>
                function ExportToExcel(tableId, filename = '') {
            let downloadlink;
            let dataType = 'application/vnd.ms-excel';
            let tableSelect = document.getElementById(tableId);
            let tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

            filename = filename ? filename + '.xls' : 'excel_data.xls';
            downloadlink = document.createElement('a');
            document.body.appendChild(downloadlink);
            if (navigator.msSaveOrOpenBlob) {
                let blob = new Blob(['\ufeff', tableHTML], {type: dataType});
                navigator.msSaveOrOpenBlob(blob, filename);
            } else {
                downloadlink.href = 'data:' + dataType + ', ' + tableHTML;
                downloadlink.download = filename;
                downloadlink.click();
            }
        }
              </script>";
    }
//8. Import csv file
    function importFile(){
        if (isset($_FILES['file'])) {
            $file_name = $_FILES['file']['name'];
            $file_size = $_FILES['file']['size'];
            $file_temp = $_FILES['file']['tmp_name'];
            $file_type = $_FILES['file']['type'];

            $target_dir = "project2/";
            $ext_allowed = array("csv");

            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            if (in_array($file_ext, $ext_allowed) === false) {
                echo "<script>alert('extension not allowed');</script>";
            } else {
                if (move_uploaded_file($file_temp, $target_dir)) {
                    echo "<script>alert('file is uploaded');</script>";
                } else {
                    echo "problem uploading file!";
                }
            }
            rename('project2', 'temp.csv');
            $content = file_get_contents('temp.csv');
            file_put_contents($file, $content);
            header('Location: view.php');

        }
    }


