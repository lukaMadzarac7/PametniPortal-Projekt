<?php

session_start();

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

$theID = $_SESSION["id"];
$all = "SELECT * FROM user WHERE user.UserID = $theID;";
$result = $mysqli->query($all);
$mysqli->close();
$rows = $result->fetch_assoc();

/////////////////////////////////////////////////////////////////

//Provjera jeli korisnik prijavljen
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

$opis = $cijena = $naslov= $grad = $zupanija = $adresa = "";
$ntradio = 0;

//Ova ce se funkcija pokrenuti nakon submitanja
if($_SERVER["REQUEST_METHOD"] == "POST"){

 
    $naslov = trim($_POST["naslov"]);
    $opis = trim($_POST["opis"]);
    $cijena = trim($_POST["cijena"]);
    $status = $_POST['OdabirStatusa'];
    $vlasnik = $rows['UserID'];
    $ntRadio = $_POST['nudimtrazim'];

    if($ntRadio == "nudim"){
        $ntRadio = 0;
    }else if($ntRadio == "trazim"){
        $ntRadio = 1;

    }

    if($status == "dosOd"){
        $status = 0;
    }else if($status == "dosKas"){
        $status = 1;
    }else if($status == "nijeDos"){
        $status = 2;

    }
    else if($status == "isUs"){
        $status = 3;

    }

  

    $odabranoMjesto = $_POST['OdabirMjesta'];

    if($odabranoMjesto == "mojaAd"){
        $grad = $rows['UserCity'];
        $adresa = $rows['Address'];
        $zupanija = $rows['UserRegion'];
    }else if($odabranoMjesto == "njAd"){
        $zupanija = $_POST['OdabirZupanija'];
        $grad = $_POST['OdabirGrada'];   
        $adresa = "Adresa druge osobe";  
    }



        $sql = "INSERT INTO postedservice (ServiceAdderID, ServiceName, ServiceDesc, ServicePrice, ServiceCity, ServiceAddress, NudimTrazim, ServiceRegion, ServiceAvailability) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sssssssss", $param_vlasnik, $param_naslov, $param_opis, $param_cijena, 
            $param_grad, $param_adresa, $param_ntRadio, $param_zupanija, $param_status);

            $param_vlasnik = $vlasnik;
            $param_naslov = $naslov;
            $param_opis = $opis;
            $param_cijena = $cijena;
            $param_grad = $grad;
            $param_adresa = $adresa;
            $param_ntRadio = $ntRadio;
            $param_zupanija = $zupanija;
            $param_id = $_SESSION["id"];
            $param_status = $status;
            

            if(mysqli_stmt_execute($stmt)){
                header("location: welcome.php");
                exit();
            } else{
                echo "Nešto je pošlo po krivu. Pokušajte kasnije!";
            }

            mysqli_stmt_close($stmt);
        }
    }
 
    mysqli_close($link);

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dodaj oglas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
    </script>
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>


</head>
<body>
    <div class = "container-fluid" id="background" style="width: 100%; height: 100%; position: fixed; left: 0px; top: 0px; z-index: -1;">
        <img src="bi2.jpg" class="BIstretch" style="width:100%;height:100%;"/>
    </div>
    <div class="wrapper" style="  margin: auto; width: 50%; border: 3px ; padding: 100px;">
        <h2>Dodaj oglas na <br><b>Pametnom Portalu</b></h2>
        <p>Popunite podatke kako biste objavili oglas.</p>
        <p>___________________________________________<p>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group">
                <label>Nudite li uslugu ili tražite?</label><br>
                <select id="nudimtrazim" name = "nudimtrazim">
                <option value = "nudim"> Nudim
                </option>
                <option value = "trazim"> Trazim
                </select>
            </div>
            <div class="form-group">
                <label>Naziv usluge koju nudite/tražite</label>
                <input id="naslov" type="text" name="naslov" class ="form-control" value="">
            </div>
                
            <div class="form-group">
                <label>Opis usluge koju nudite/tražite</label>
                <textarea name="opis" id="opis" class="form-control" value=""></textarea>
                
            </div>

            <div>
                <label>Cijena/Placanje usluge (u eurima)</label>
                <input id="cijena" type="text" name="cijena" class ="form-control" value="">
            </div>
            <br>
            <div>
                <label> Mjesto izvođenja: </label>
                <select id="OdabirMjesta" name = "OdabirMjesta">
                <option value = "mojaAd"> Moja adresa s profila
                </option>
                <option value = "njAd"> Adresa druge osobe
                </select>
            </div>
            <div>
                <p>----------------------------------------------------------</p>
                <h5> Koje područje pokriva vaša usluga? </h5>
                <label> Županija izvođenja: </label>
                <select id="OdabirZupanija" name="OdabirZupanija">
                <option value = "osbar"> Osiječko-baranjska
                </option>
                <option value = "vusri"> Vukovarsko-srijemska
                </option>
                <option value = "bropo"> Brodsko-posavska
                </option>
                <option value = "svez"> Bilo koja županija
                </option>
                </select>
            </div>
            <div>
                <label> Grad izvođenja: </label>
                <select id="OdabirGrada" name="OdabirGrada">
                <option value = "Osijek"> Osijek 
                </option>
                <option value = "Vinkovci"> Vinkovci 
                </option>
                <option value = "Zagreb"> Zagreb
                </option>
                <option value = "Split"> Split
                </option>
                </option>
                <option value = "svig"> Bilo koji grad
                </option>
                </select>
                <p>----------------------------------------------------------</p>

            </div>
            <div>
                <label> Status usluge: </label>
                <select id="OdabirStatusa" name="OdabirStatusa">
                <option value = "dosOd"> Dostupno odmah 
                </option>
                <option value = "dosKas"> Dostupno kasnije (nadopunit) 
                </option>
                <option value = "nijeDos"> Trenutno nije dostupno
                </option>
                <option value = "isUs"> Istekla usluga
                </option>
                </select>
            </div>

            <br>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
        <p><a href="welcome.php">Vrati se na Moj Profil</a></p>
    </div>    
</body>
</html>