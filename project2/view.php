<?php
ini_set('display_errors', 'Off');
session_start();
if(!isset($_SESSION['logged'])||$_SESSION['logged'] == false){
    header('Location: index.html');
}
?>
    <!DOCTYPE html>
    <html lang="en">\
    <head>
        <meta charset="UTF-8">
        <title>Title</title>
        <link rel="stylesheet" href="indexstyle.css">

    <body>
    <div class="navbar">
        <span class="dot">CSV</span><span></span>
        <a href="home.php">Home</a>
        <a id='LogOut' href="index.html">Log Out</a>
    </div>
    <div class="line"></div>
    <h1>View CSV</h1>
    <div class="searchForm">
        <form action='view.php' method='post'>
            <input type='text' name='search' placeholder='Search by Id'>
            <input class='styleBut1' type='submit' name='searchBut' value='Search'>
        </form>
    </div>
    <div class="table" id="myTable">
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
            ?>
        </table>
    </div>
    <div class="spanAddDiv">
        <form method="post" action="view.php" enctype="multipart/form-data">
            <label for="file">Upload new CSV</label><br><br>
            <input type="file" name="file" class="spanAdd" value="Upload new File"><br><br>
            <input class="styleBut" type="submit" value="Upload">
        </form>
    </div>
    <br>
    <div class="Export">
        <form class="form-horizontal" action="view.php" method="post" enctype="multipart/form-data">
            <label for="Export">Export to Excel</label><br><br>
            <input type="submit" name="Export" onclick="ExportToExcel('myTable')" class="styleBut"
                   value="export to excel"/>
        </form>
    </div>
    <div class="viewSource">
        <a href="/folder_view/vs.php?s=<?php echo __FILE__?>" target="_blank">View Source</a>
        </div>
    </body>
    </html>
    <script>
        function myFunction() {
            document.getElementById("myDropdown").classList.toggle("show");
        }

        window.onclick = function (event) {
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        };

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

    </script>

    <?php
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

    if (isset($_POST['searchBut'])) {
        $id_to_search = $_POST['search'];
        if ($id_to_search <= count($keys)) {
            session_start();
            $_SESSION['keys_array'] = $keys;
            $_SESSION['id'] = $id_to_search;
            echo "<script>
                    window.location.href = 'search.php';
                  </script>";
        } else {
            echo "<script>alert('Id could not be found');</script>";
        }
    }




?>
