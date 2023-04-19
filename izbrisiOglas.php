<?php


/////////////////////////////UHVATI PODATKE////////////////////////////////////

$user = 'root';
$password = '';
$database = 'pametniportal';


$servername='localhost';
$mysqli = new mysqli($servername, $user,
                $password, $database);


if ($mysqli->connect_error) {
    die('Connect Error (' .
    $mysqli->connect_errno . ') '.
    $mysqli->connect_error);
}


$currentId = $_GET['currentId']; 

/*
$theID = $_SESSION["id"];
$all = "SELECT * FROM postedservice WHERE PostedServiceID = $currentId;";
$result = $mysqli->query($all);
$rows = $result->fetch_assoc();
$mysqli->close();
*/

/////////////////////////////////////////////////////////////////

 
//Provjera jeli korisnik ulogiran
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 

require_once "config.php";

 
/*
    $opis = $cijena = $naslov= "";

    $naslov = "";
    $opis = "";
    $cijena = 0;
    */


        $sql = "DELETE FROM postedservice WHERE ServiceName = $currentId;";

        
        if($stmt = mysqli_prepare($link, $sql)){
            if(mysqli_stmt_execute($stmt)){
                header("location: login.php");
                exit();
            } else{
                echo "Nešto je pošlo po krivu. Pokušajte kasnije!";
            }

            mysqli_stmt_close($stmt);
        }
    
    
    mysqli_close($link);


?>
 
