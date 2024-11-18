<?php
//conexion a la base de datos
require_once __DIR__.'/../php/configdb.php';

$link = mysqli_connect($SERVER,$USERNAME,$PASSWORD,$DATABASE);

if($link == false){
    die("ERROR: Could not connect ".mysqli_connect_error());
}


$pokemonId = $_POST['pokemonId'];

// Query para obtener los datos del Pokemon
$sql = "SELECT p.id, p.img_id,p.especie,p.nombre,p.peso,p.altura,p.baxp FROM pokemon p WHERE p.id ='$pokemonId'";
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0){
    while ($row = $result->fetch_assoc())
    {
        echo json_encode($row);
    }
}else{
	echo json_encode(array('error' => 'No se encontró el Pokemon'));
}
mysqli_close($link);
?>
