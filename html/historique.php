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
	<title>Station Météo</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark"style="height:80px">
            <span class="navbar-brand mb-0 h1" style="margin-left:20px">Station Meteo</span>
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
        <div class="container-fluid" style="margin-top:30px">
            <div class="row">
                <div class="col-sm-6">
                    <p class="h3">Historique des relevés du capteur</p>
                </div>
                <div class="col-sm-6">
                    <p class="h3">Historique des températures extérieures</p>
                </div>
            </div>		
        </div>        
        <div class="container-fluid" style="margin-top:30px;">
            <div class="row">
                <div class="col-sm-6">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope ="col">Température</th>
                                <th scope ="col">Pression</th>
                                <th scope ="col">Humidité</th>
                                <th scope ="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $servername = "localhost";
                            $username = "robertmeteo";
                            $password = "trebor";
                            $database = "meteobdd";

                            $connection = mysqli_connect($servername, $username, $password, $database);

                            if ($connection-> connect_error) {
                                die("Connexion à la base de donnée impossible" . $connection-> connect_error);
                            }

                            $sql = "SELECT temperature,pressure,humidity,date_time FROM readings ORDER BY date_time DESC LIMIT 10";
                            $result = $connection->query($sql);

                            while ($row = $result->fetch_assoc()){
                                echo "<tr>
                                    <td>" . $row["temperature"] . "°C</td>
                                    <td>" . $row["pressure"] . "hPa</td>
                                    <td>" . $row["humidity"] . "%</td>
                                    <td>" . $row["date_time"] . "</td>
                                </tr>";
                            }
                        
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-6">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope ="col">Température</th>
                                <th scope ="col">Ressenti</th>
                                <th scope ="col">Pression</th>
                                <th scope ="col">Humidité</th>
                                <th scope ="col">Description</th>
                                <th scope ="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            $sql = "SELECT temperature,temperature_feels_like,pressure,humidity,description,date_time FROM apireadings ORDER BY date_time DESC LIMIT 10";
                            $result = $connection->query($sql);

                            while ($row = $result->fetch_assoc()){
                                echo "<tr>
                                    <td>" . $row["temperature"] . "°C</td>
                                    <td>" . $row["temperature_feels_like"] . "°C</td>
                                    <td>" . $row["pressure"] . "hPa</td>
                                    <td>" . $row["humidity"] . "%</td>
                                    <td>" . $row["description"] . "</td>
                                    <td>" . $row["date_time"] . "</td>
                                    </tr>";
                            }
                        
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>    
    </body>
</html>
