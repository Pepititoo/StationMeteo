
<?php
// Activer l'affichage des erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
       	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
	<title>Station Météo</title>
    </head>
    <body>
	    <nav class="navbar navbar-expand-lg navbar-dark bg-dark"style="height:80px">
            <span class="navbar-brand mb-0 h1" style="margin-left:20px">Station Météo</span>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="index.php">Accueil</a>
                        </li>   
                       <li class="nav-item">
                            <a class="nav-link" href="historique.php">Historique</a>
                        </li>
                     </ul>
                </div>
        </nav>
        <div class="container" style="margin-top: 30px">
	        <div class="row">
	            <div class="col-sm-6">
		            <p class="h3 text-center">Température intérieure</p>
	            </div>
	            <div class="col-sm-6">
		            <p class="h3 text-center">Température extérieure</p>
	             </div>
	        </div>		
	    </div>
        <?php
            $servername = "localhost";
            $username = "robertmeteo";
            $password = "trebor";
            $database = "meteobdd";

            $connection = mysqli_connect($servername,$username,$password,$database);

            $sqlCapteur = "SELECT * FROM readings ORDER BY date_time DESC LIMIT 1";
            $result = $connection->query($sqlCapteur);
            $row = $result->fetch_assoc();

            date_default_timezone_set( 'CET' );
        ?>
        <div class="container">
	        <div class="row py-5">
                <div class="col-sm-6">
                    <div class="card text-body" style=" border-radius: 35px;">
                        <div class="card-body p-4">
                            <div class="d-flex">
                            <h6 class="flex-grow-1">Pau</h6>
                            <h6><?=date("H:i")?></h6>
                            </div>

                            <div class="d-flex flex-column text-center mt-5 mb-4">
                            <h6 class="display-4 mb-0 font-weight-bold"><?=$row['temperature']?> °C</h6>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1" style="font-size: 1rem;">
                                    <div>
                                        <i class="fas fa-tint fa-fw" style="color: #868B94;"></i> 
                                        <span class="ms-1"> <?=$row['humidity']?> %</span>
                                    </div>
                                    <div>
                                        <span class="ms-1"> <?=$row['pressure']?> hPa</span>
                                    </div>

                                </div>
                                <div>
                                <img src="https://media.istockphoto.com/id/1354219060/fr/vectoriel/dessin-anim%C3%A9-vectoriel-solaire-logo-vectoriel-pour-la-conception-web-illustration.jpg?s=612x612&w=0&k=20&c=vlSl5Lbf1VfpI5xiNigZe4uj9ckKN7nGSOhHEdK3I8Y=" width="100px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <?php

                $sqlApi = "SELECT * FROM apireadings ORDER BY date_time DESC LIMIT 1";
                $result = $connection->query($sqlApi);
                $row = $result->fetch_assoc();

                ?>
                <div class="col-sm-6">
                    <div class="card text-body" style=" border-radius: 35px;">
                        <div class="card-body p-4">
                            <div class="d-flex">
                            <h6 class="flex-grow-1">Pau</h6>
                            <h6><?=date("H:i")?></h6>
                            </div>

                            <div class="d-flex flex-column text-center mt-5 mb-4">
                            <h6 class="display-4 mb-0 font-weight-bold"><?=$row['temperature']?> °C</h6>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1" style="font-size: 1rem;">
                                    <div>
                                        <i class="fas fa-tint fa-fw" style="color: #868B94;"></i> 
                                        <span class="ms-1"> <?=$row['humidity']?> %</span>
                                    </div>
                                    <div>
                                        <span class="ms-1"> <?=$row['pressure']?> hPa</span>
                                     </div>
                                </div>
                                <div>
                                <?php
                                switch ($row['description']){
                                    case "partiellement nuageux":
                                        echo'<img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-weather/ilu1.webp" width="100px">';
                                        break;
                                    case "ciel dégagé":
                                        echo'<img src="https://media.istockphoto.com/id/1354219060/fr/vectoriel/dessin-anim%C3%A9-vectoriel-solaire-logo-vectoriel-pour-la-conception-web-illustration.jpg?s=612x612&w=0&k=20&c=vlSl5Lbf1VfpI5xiNigZe4uj9ckKN7nGSOhHEdK3I8Y=" width="100px">';
                                        break;
                                    }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
	        </div>  
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>    
    </body>
</html>
