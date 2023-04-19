
<?php

session_start();
 
// Provjera jeli korisnik ulogiran
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

//////////////HVATANJE PODATAKA/////////////////////////
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

$theID = $_SESSION["id"];
$all = "SELECT * FROM user WHERE user.UserID = $theID;";
$result = $mysqli->query($all);
$rows = $result->fetch_assoc();

$sqlnumber = "SELECT * from postedservice WHERE ServiceAdderID = $theID;";
if ($result = $mysqli->query($sqlnumber)) {
    $numberOfads = mysqli_num_rows( $result );
 }

$sqlid = "SELECT PostedServiceID from postedservice WHERE ServiceAdderID = $theID;";
$result = $mysqli->query($sqlid);
$rowsAd = [];
while($row = mysqli_fetch_array($result))
{
    $rowsAd[] = $row;
}

$mysqli->close();

//////////////////////////////////////////////


?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Moj profil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        /*
    $(document).ready(function(){
        var id = "<?php echo $rows['UserID']; ?>";

        $.ajax({
            url:"fetch.php",
            method:"POST",
            data: {id:id},
            dataType:"JSON",
            success:function(data)
            { 
             $('#naslov').text(data.naslovUsuge1);
             $('#opis').text(data.opisUsluge1);
             $('#cijena').text(data.cijenaUsluge1);

            } 
         });
    });
    */

    function Izmjeni(currentId) {
        <?php /*
            $_SESSION["currentId"] = $currentId; 
            header("location: izmjenioglas.php");      
            */ 
        ?>

        alert("doso");

    }


    </script>
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class = "container-fluid" id="background" style="width: 100%; height: 100%; position: fixed; left: 0px; top: 0px; z-index: -1;">
        <img src="bi2.jpg" class="BIstretch" style="width:100%;height:100%;"/>
    </div>
    <h1 style="font-weight: bold;"class="my-5">Moj profil <br> ____________________</b> </h1>


    <h5 class="my-5">Korisničko Ime Računa- <b><?php echo $rows['PortalUserName']; ?></b> </h5>
    <h5 class="my-5">Email Adresa Računa- <b><?php echo $rows['UserEmail']; ?></b> </h5>
    <h5 class="my-5">Ime korisnika- <b><?php echo $rows['UserName']; ?></b> </h5>
    <h5 class="my-5">Prezime korisnika - <b><?php echo $rows['UserSurname']; ?></b> </h5>
    <h5 class="my-5">Mobilni telefon - <b><?php echo $rows['UserPhone']; ?></b> </h5>
    <h5 class="my-5">Grad korisnika - <b><?php echo $rows['UserCity']; ?></b> </h5>
    <h5 class="my-5">Adresa korisnika - <b><?php echo $rows['Address']; ?></b> </h5>


 
    <p>
        <a href="logout.php" class="btn btn-secondary ml-3">Odjavi se</a> 
        <a href="reset-password.php" class="btn btn-warning">Promjeni lozinku</a>
        <p><a href="main.php">Vrati se na Home Page</a></p>
        <h2 style="font-weight: bold;"class="my-5"> Moji oglasi (<?php echo $numberOfads; ?>)</b> </h2>
        <a href="dodajoglas.php" button type="button" class="btn btn-success">Dodaj oglas</button></a>
        <h2 style="font-weight: bold;"class="my-5"> _________________________________________________</b> </h2>    

    
    </p>

    <?php 

        if ($numberOfads != 0){
            $mysqli = new mysqli($servername, $user,
                $password, $database);
            if ($mysqli->connect_error) {
                die('Connect Error (' .
                $mysqli->connect_errno . ') '.
                $mysqli->connect_error);
            }

            for ($i=0; $i < $numberOfads; $i++) { 
                $currentId = $rowsAd[$i][0];
                $currentAd = "SELECT * FROM postedservice WHERE PostedServiceID = $currentId;";
                $result = $mysqli->query($currentAd);
                $currentAd = $result->fetch_assoc();

                //Računanje dostupnosti
                if($currentAd['ServiceAvailability'] == 0){
                    $currentStatus = "Dostupno odmah";
                }else if($currentAd['ServiceAvailability'] == 1){
                    $currentStatus = "Dostupno kasnije";
                }
                else if($currentAd['ServiceAvailability'] == 2){
                    $currentStatus = "Trenutno nedostupno";
                }else if($currentAd['ServiceAvailability'] == 3){
                    $currentStatus = "Istekla usluga";
                }
                //

                echo '<div class="container" style="border:1px solid black;">';
                echo '<h4 class="my-5"><b>Naslov Oglasa</b> : ';
                echo '<span id="naslov">';
                echo $currentAd['ServiceName'];
                echo '</span>';
                echo'</h4>';
                echo '<h5 class="my-5"><b>Opis Oglasa</b> : ';
                echo '<span id="opis">';
                echo $currentAd['ServiceDesc'];
                echo '</span>';
                echo'</h5>';
                echo '<h5 class="my-5"><b>Mjesto izvođenja</b> : ';
                echo '<span id="mjizvo">';
                echo $currentAd['ServiceRegion'], ', ', $currentAd['ServiceCity'], ', ',$currentAd['ServiceAddress'];
                echo '</span>';
                echo'</h5>';
                echo '<h5 class="my-5"><b>Cijena Oglasa</b> : ';
                echo '<span id="cijena">';
                echo $currentAd['ServicePrice'];
                echo '</span>';
                echo' eura </h5>';
                echo '<h5 class="my-5"><b>Status Oglasa</b> : ';
                echo '<span id="status">';
                echo $currentStatus;
                echo '</span>';
                echo'</h5>';
                echo "<a button onclick='<?php exit; ?>' type='button' class='btn btn-success'>Izmjeni oglas</button></a>";
                echo ' ';
                echo '<a href="izbrisiOglas.php?currentId=',$currentId,'" button type="button" class="btn btn-danger">Obriši oglas</button></a><br><br><br>';
                echo '</div><br>' ;
 

            }
            $mysqli->close();

            
        }else{
            echo '<h5 class="my-5"><b>Nemate objavljenih oglasa!</b>';

        }



    ?>
    
    <br>
    <br>
    <br>

</body>
</html>