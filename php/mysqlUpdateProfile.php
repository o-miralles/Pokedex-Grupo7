<?php
// Archivo principal
use MyNamespace\MySQLProfile;

$id_pokedek = "";
$profile = new MySQLProfile(); // Creas una instancia de la clase MySQLProfile

//establecemos la conexión con la base de datos
require_once __DIR__.'/../php/configdb.php';

$link = mysqli_connect($SERVER,$USERNAME,$PASSWORD,$DATABASE);
//revisamos que se haya realizado la conexión
if (mysqli_connect_errno()) {
  $message = "ERROR: Could not connect " . mysqli_connect_error();
}else{
	//obtenemos los datos enviados por el post
	$email = $_COOKIE["currentEmail"];
	//lo primero que necesitamos es el id del pokedek
	$sql = "SELECT p.id FROM Pokedek p
INNER JOIN usuario u ON p.id_usuario=u.id
WHERE u.correo = '$email'";
	//obtenemos el resultado del query
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0){
    while ($row = $result->fetch_assoc())
    {
      foreach($row as $value) $id_pokedek = $value;
    }
    //ya que tengo el id del pokedek, obtendre el id del 
    //pokemon que quiero eliminar
    $img_id=$myArr[0]["img_id"];
    $id_pokemon="";
    $sql="SELECT id FROM Pokemon WHERE img_id='$img_id'";
    //obtenemos el resultado del query
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0){
    while ($row = $result->fetch_assoc())
    {
      foreach($row as $value) $id_pokemon = $value;
    }
    $poke_name = $_POST["poke_name"];
    if($poke_name!=""){
    $sql = "UPDATE Pokemon SET nombre  = '$poke_name'  WHERE id = '$id_pokemon'";
    if(mysqli_query($link, $sql)){
    	header('Location: ../html/profile.php');
		exit();
    }else{
    	$message = "ERROR: Could not update pokemon";
	// Close connection
	mysqli_close($link);
        }
    }else{
        header('Location: ../html/profile.php');
        exit();
    }
}else{
	$message = "ERROR: Could not find pokemon ";
	// Close connection
	mysqli_close($link);
}
    
}else{
	$message = "ERROR: Could not find useer ";
	// Close connection
	mysqli_close($link);
	}
}