<?php
$message = "";

if (!empty($_POST)) {
    require_once __DIR__.'/../php/configdb.php';

    $link = mysqli_connect($SERVER,$USERNAME,$PASSWORD,$DATABASE);

    if (!$link) {
        $message = "ERROR: Could not connect " . mysqli_connect_error();
    }else {
        $email = mysqli_real_escape_string($link, $_POST["email"]);
        $pwd = $_POST["pwd"];

        $stmt = $link->prepare("SELECT id, correo, contrasena FROM usuario WHERE correo=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($pwd, $row["contrasena"])) {
                $id_usuario = $row["id"];
                session_start();

                // Guardar el correo y el ID del usuario en las variables de sesión
                $_SESSION["currentEmail"] = $email;
                $_SESSION["currentId"] = $id_usuario;

                header('Location: ../html/main.php');
                exit();
            } else {
                $message = "Credenciales incorrectas";
            }
        } else {
            $message = "Credenciales incorrectas";
        }
        $stmt->close();
        mysqli_close($link);
    }
}
