<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fita 4.2</title>
    <style>
        .container {
            width: 100%;
            overflow: auto;
        }

        .left {
            float: left;
            width: 10%;
        }

        .right {
            float: right;
            width: 90%;
        }
    </style>
</head>

<body>
    <?php
    # (1.0) Definim els paràmetres de la pàgina web
    $resultat = null;

    # (1.1) Connectem a MySQL (host,usuari,contrassenya)
    $conn = mysqli_connect('localhost', 'admin', 'SQL no me gusta!');

    # (1.2) Triem la base de dades amb la que treballarem
    mysqli_select_db($conn, 'mundo');

    # (2.1) creem el string de la consulta (query)
    $consulta = "SELECT DISTINCT Continent FROM country;";

    # (2.2) enviem la query al SGBD per obtenir el resultat
    $resultat = mysqli_query($conn, $consulta);

    # (2.3) si no hi ha resultat (0 files o bé hi ha algun error a la sintaxi)
    #     posem un missatge d'error i acabem (die) l'execució de la pàgina web
    if (!$resultat) {
        $message  = 'Consulta invàlida: ' . mysqli_error($conn) . "\n";
        $message .= 'Consulta realitzada: ' . $consulta;
        die($message);
    }

    ?>
    <h1>Filtra països per continents:</h1>

    <div class="container">
        <div class="left">
            <form action="ex1.php" method="post">
                <?php
                if ($resultat != null) {
                    while ($registre = mysqli_fetch_assoc($resultat)) {
                        $checked = isset($_POST['continents']) && in_array($registre["Continent"], $_POST['continents']) ? 'checked' : '';
                        echo '<input type="checkbox" name="continents[]" value="' . $registre["Continent"] . '" . ' . $checked . '>' . $registre["Continent"] . '<br>';
                    }
                }
                ?>
                <input type="submit" value="Tramet la consulta">
            </form>
        </div>


        <?php
        $resultCountries = null;
        $continentsChecked = array();
        if (isset($_POST['continents'])) {
            $continentsChecked = $_POST['continents'];
        }

        if (count($continentsChecked) > 0) {
            # (2.1) creem el string de la consulta (query)
            $consulta = "SELECT Name FROM country WHERE Continent IN ('" . implode("','", $continentsChecked) . "');";

            # (2.2) enviem la query al SGBD per obtenir el resultat
            $resultCountries = mysqli_query($conn, $consulta);

            # (2.3) si no hi ha resultat (0 files o bé hi ha algun error a la sintaxi)
            #     posem un missatge d'error i acabem (die) l'execució de la pàgina web
            if (!$resultCountries) {
                $message  = 'Consulta invàlida: ' . mysqli_error($conn) . "\n";
                $message .= 'Consulta realitzada: ' . $consulta;
                die($message);
            }
        }

        ?>

        <div class="right">
            <ul>
                <?php

                # (3.2) Bucle while
                if ($resultCountries != null) {
                    echo '<h2>Paîsos del continents marcats:</h2>';
                    while ($registre = mysqli_fetch_assoc($resultCountries)) {
                        echo "\t<li>" . $registre["Name"] . "</li>\n";
                    }
                    if (mysqli_num_rows($resultat) < 1) {
                        echo "\t<li>No hi ha dades</li>\n";
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</body>

</html>