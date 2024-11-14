<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fita 4.1</title>
</head>

<body>
    <?php
    # (1.0) Definim els paràmetres de la pàgina web
    $resultat = null;

    $continent = null;
    if (isset($_POST['continent'])) {
        $continent = $_POST['continent'];
    }

    # (1.1) Connectem a MySQL (host,usuari,contrassenya)
    $conn = mysqli_connect('localhost', 'admin', 'SQL no me gusta!');

    # (1.2) Triem la base de dades amb la que treballarem
    mysqli_select_db($conn, 'mundo');

    # (2.1) creem el string de la consulta (query)
    $consulta = "SELECT DISTINCT Continent FROM country ORDER BY Continent ASC;";

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

    <form action="ex1.php" method="post">
        <select name="continent" id="continent">
            <?php
            if ($resultat != null) {
                while ($registre = mysqli_fetch_assoc($resultat)) {
                    $isContinentSelected = ($registre["Continent"] == $continent);
                    echo '<option value="' . $registre["Continent"] . '" ' . ($isContinentSelected ? "selected" : "") . '>' . $registre["Continent"] . '</option>\n';
                }
                if (mysqli_num_rows($resultat) < 1) {
                    echo "\t<option>No hi ha dades</option>\n";
                }
            }
            ?>
        </select>
        <input type="submit" value="Tramet la consulta">
    </form>
    <?php
    $resultCountries = null;

    if ($continent != null) {
        # (2.1) creem el string de la consulta (query)
        $consulta = "SELECT Name FROM country WHERE Continent = '$continent' ORDER BY Continent ASC;";

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

    <ul>
        <?php

        # (3.2) Bucle while
        if ($resultCountries != null) {
            echo '<h2>Paîsos del continent ' . $continent . ':</h2>';
            while ($registre = mysqli_fetch_assoc($resultCountries)) {
                echo "\t<li>" . $registre["Name"] . "</li>\n";
            }
            if (mysqli_num_rows($resultat) < 1) {
                echo "\t<li>No hi ha dades</li>\n";
            }
        }
        ?>
    </ul>
</body>

</html>