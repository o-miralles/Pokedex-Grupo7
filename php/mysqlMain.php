<?php
$name = "";

require_once __DIR__.'/../php/configdb.php';

$link = mysqli_connect($SERVER,$USERNAME,$PASSWORD,$DATABASE);

if ($link == false) {
  $message = "ERROR: Could not connect " . mysqli_connect_error();
} else {
  $email = $_SESSION["currentEmail"];

  $sql = "SELECT nombre FROM usuario WHERE correo='$email'";
  $result = mysqli_query($link, $sql);

  if (mysqli_num_rows($result) > 0) {
    while ($row = $result->fetch_assoc()) {
      foreach($row as $value) $name = $value;
    }

    $sql = "SELECT * FROM usuario WHERE correo != '$email'";
    $result = mysqli_query($link, $sql);

    if (mysqli_num_rows($result) > 0) {
      $users = array();
      $used = array();
      $usersToUse = array();
      $counters = array();

      while ($row = $result->fetch_assoc()) {
        $users[] = $row;
      }

      $maxi = sizeof($users);

      for ($i = 0; $i < $maxi; $i++) {
        $random = rand(0, sizeof($users) - 1);

        for ($j = 0; $j < sizeof($used); $j++) {
          if ($random == $used[$j]) {
            while ($random == $used[$j]) {
              $random = rand(0, sizeof($users) - 1);
            }
          } else {
            break;
          }
        }

        $used[$i] = $random;
        $usersToUse[$i] = $users[$random];
      }

      for ($i = 0; $i < $maxi; $i++) {
        $id_usuario = $usersToUse[$i]["id"];
        $sql = "SELECT COUNT(p.id) AS 'n' FROM pokemon p
                INNER JOIN pokedek_pokemon pp ON p.id = pp.id_pokemon 
                INNER JOIN pokedek pk ON pp.id_pokedek = pk.id
                INNER JOIN usuario u ON pk.id_usuario = u.id WHERE u.id ='$id_usuario'";
        $result = mysqli_query($link, $sql);

        if (mysqli_num_rows($result) > 0) {
          while ($row = $result->fetch_assoc()) {
            array_push($counters, $row);
          }
        }
      }
    }
  }

  // Close connection if not already closed
  if ($link) {
    mysqli_close($link);
}
}
?>
