<?php
include 'db_config.php';
try 
{
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Har vi en bruger med samme brugernavn?
    function trim_input(&$input) {
        $input = trim($input);
    }
    array_filter($_POST, 'trim_input');

    $inputArr = 
    array("fullname" => filter_var($_POST["fullname"], FILTER_SANITIZE_STRING), // Strip tags, optionally strip or encode special characters.
          "username" => filter_var($_POST["newusername"], FILTER_SANITIZE_STRING),
          "passw" => filter_var($_POST["newpassword"], FILTER_SANITIZE_STRING),
          "phone" => filter_var($_POST["phone"], FILTER_SANITIZE_NUMBER_INT), // Remove all characters except digits, plus and minus sign.
          "email" => filter_var($_POST["email"], FILTER_SANITIZE_EMAIL)); // Remove all characters except letters, digits and !#$%&'*+-=?^_`{|}~@.[].
    print_r($inputArr);

    // Indset ny bruger.
    $stmt = $conn->prepare("INSERT INTO users (username, passw, fullname, phone, email)
                           VALUES (:username, :passw, :fullname, :phone, :email);");
    

    $stmt->bindParam(':username', $inputArr["username"]);
    $stmt->bindParam(':passw', $inputArr["passw"]);
    $stmt->bindParam(':fullname', $inputArr["fullname"]);
    $stmt->bindParam(':phone', $inputArr["phone"]);
    $stmt->bindParam(':email', $inputArr["email"]);

    /*foreach ($inputArr as $key => $value) {
        $stmt->bindParam(':$key', $value);
        echo " <br>key: $key value: $value";
    }
    echo "<br>";
    print_r($inputArr);
    echo "<br>";*/

    $result = $stmt->execute();

    echo "Connected successfully with result: " . $result;
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage() . ".\n code " . $e->getCode();
}
?>