<?php
    if (isset($_GET["searchValue"])) {
      $searchValue = $_GET["searchValue"];
    }
    if (isset($_GET["orderBy"])) {
      $orderBy = $_GET["orderBy"];
    }
    require_once('Include/db_config.php');
    $result;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmtString = "SELECT username, fullname, signup FROM users";

        if ($searchValue != NULL) {
            $stmtString .= " WHERE (:username = '$searchValue'
                                OR :fullname = '$searchValue'
                                OR :signup = '$searchValue')";
        }
        $stmtString .= " ORDER BY $orderBy;";

        $stmt = $conn->prepare($stmtString);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_NUM); // FETCH_NUM -> returnerer array indexeret i colonner angivet i tal.
                                             // Andre return methoder er beskrevet her: https://www.php.net/manual/en/pdostatement.fetch.php
        $result = $stmt->fetchAll();
    }
    catch(PDOException $e)
    {
        echo "<br><p> Connection failed: " . $e->getMessage() . ". code: " . $e->getCode() . "</p>";
    }

    /*if (!empty($result)) {
        $rows = count($result);
        $cols = count($result[0]);
        $echoString = '';
        for ($i=0; $i < $rows; $i++) {
            $echoString .= "<tr>";
            for ($j=0; $j < $cols; $j++) {
                $echoString .= '<td>' . $result[$i][$j] . '</td>';
            }
            $echoString .= "</tr>";
        }
        echo $echoString;
    }*/

    $conn = null;
?>