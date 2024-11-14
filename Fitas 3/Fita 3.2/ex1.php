<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fita 3.2</title>
    <style>
        table,
        td {
            border: 1px solid black;
            border-spacing: 0px;
        }
    </style>
</head>

<body>
    <h1>Filtra llengües</h1>

    <form action="ex1.php" method="post">
        <label for="language">Llenguatge: </label>
        <input type="text" name="language" id="language" required>
        <br>
        <input type="submit" value="Filtrar">
    </form>
    <br>
    <?php
    # (1.0) Definim els paràmetres de la pàgina web
    $resultat = null;
    $language = null;
    if (isset($_POST['language'])) {
        $language = $_POST['language'];
    }

    # (1.1) Connectem a MySQL (host,usuari,contrassenya)
    $conn = mysqli_connect('localhost', 'admin', 'SQL no me gusta!');

    # (1.2) Triem la base de dades amb la que treballarem
    mysqli_select_db($conn, 'mundo');

    # (2.1) creem el string de la consulta (query)
    $consulta = "SELECT DISTINCT Language, IsOfficial FROM countrylanguage ORDER BY Language ASC;";
    if ($language != null) {
        $consulta = "SELECT DISTINCT Language, IsOfficial FROM countrylanguage WHERE Language LIKE '%$language%' ORDER BY Language ASC;";
    }

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

    <!-- (3.1) aquí va la taula HTML que omplirem amb dades de la BBDD -->
    <table>
        <!-- la capçalera de la taula l'hem de fer nosaltres -->
        <thead>
            <tr>
                <th colspan="4" align="center" bgcolor="cyan">Llistat de llengües</th>
            </tr>
        </thead>
        <tbody>
            <?php

            # (3.2) Bucle while
            if ($resultat != null) {
                while ($registre = mysqli_fetch_assoc($resultat)) {
                    # els \t (tabulador) i els \n (salt de línia) son perquè el codi font quedi llegible

                    # (3.3) obrim fila de la taula HTML amb <tr>
                    echo "\t\t\t<tr>\n";

                    # (3.4) cadascuna de les columnes ha d'anar precedida d'un <td>
                    #	després concatenar el contingut del camp del registre
                    #	i tancar amb un </td>
                    echo "\t\t\t\t<td>" . $registre["Language"];
                    if ($registre["IsOfficial"] == "T") {
                        echo " [OFICIAL]";
                    }
                    echo "</td>\n";

                    # (3.5) tanquem la fila
                    echo "\t\t\t</tr>\n";
                }
                if (mysqli_num_rows($resultat) < 1) {
                    echo "\t\t\t<tr>\n";
                    echo "\t\t\t\t<td colspan=\"4\">No hi ha dades</td>\n";
                    echo "\t\t\t</tr>\n";
                }
            } else {
                echo "\t\t\t<tr>\n";
                echo "\t\t\t\t<td colspan=\"4\">No hi ha dades</td>\n";
                echo "\t\t\t</tr>\n";
            }
            ?>
        </tbody>
        <!-- (3.6) tanquem la taula -->
    </table>
</body>

</html>