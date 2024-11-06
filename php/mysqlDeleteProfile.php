<?php
session_start(); // Iniciar la sesión
$id_pokedek="";
$pokemon_id = $_POST['pokemonIdToDelete'];
require(__DIR__.'/mysqlProfile.php');
// Establecemos la conexión con la base de datos
$link = mysqli_connect("10.30.212.71","grupo7","Pa$$w0rd","pokewebapp");
// Revisamos que se haya realizado la conexión
if($link == false){
    $message = "ERROR: Could not connect ".mysqli_connect_error();
}else{
    // Obtenemos los datos enviados por el POST
    $userID = $_SESSION["currentId"];
    // Lo primero que necesitamos es el id del pokedek
    $sql = "SELECT p.id FROM Pokedek p
            INNER JOIN Usuario u ON p.id_usuario=u.id
            WHERE u.id = '$userID'";
    // Obtenemos el resultado del query
    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) > 0){
        while ($row = $result->fetch_assoc()){
            foreach($row as $value) $id_pokedek = $value;
        }
        // Ya que tengo el id del pokedek, obtendré el id del 
        // Pokémon que quiero eliminar
        // ...

        // Ya que tenemos el id del pokedek y el id del Pokémon eliminaremos
        // el Pokémon seleccionado dentro de dicho pokedek
        $sql = "DELETE FROM Pokedek_pokemon WHERE id_pokedek = '$id_pokedek' AND id_pokemon = '$pokemon_id'";
        if(mysqli_query($link, $sql)){
            // Agregar pokeballs de forma aleatoria (1 a 3)
            $pokeballsToAdd = rand(1, 3);
            $sql = "UPDATE Usuario SET pokeballs = pokeballs + $pokeballsToAdd WHERE correo = '$email'";
            if(mysqli_query($link, $sql)){
                header('Location: ../html/profile.php');
                exit();
            }else{
                $message = "ERROR: Could not update pokeballs";
                // Close connection
                mysqli_close($link);
            }    	
        }else{
            $message = "ERROR: Could not delete pokemon";
            // Close connection
            mysqli_close($link);
        }
    }else{
        $message = "ERROR: Could not find user";
        // Close connection
        mysqli_close($link);
    }
}
?>
