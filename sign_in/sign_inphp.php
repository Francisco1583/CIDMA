<?php

session_start();

// variables para poderse conectar a la base de datos
$servername = "localhost";
$username = "TC2005B_602_03";
$password = "Gx[IA-apZzok";
$database = "TC2005B_602_03";
// conexión a la base de datos
$conn = mysqli_connect($servername, $username, $password, $database);

if(!$conn) {
  die('error de conexion'.mysqli_connect_error());
}


//$matricula = $_POST["user"];
//$password = $_POST["password"];

// se obtienen los elementos del formulario de sign_in.php y pasan por una función para exceptuar algún caracter especial que se pudiera usar para inyección en
// la base de datos
$matricula = mysqli_real_escape_string($conn, $_POST["user"]);
$password = mysqli_real_escape_string($conn, $_POST["password"]);


// Se prepara la consulta y se insertan los elementos del formularion
$query = mysqli_prepare($conn, "SELECT * FROM MDP_Administrador WHERE Matricula_usuario = ? AND password = SHA2(?, 256)");
mysqli_stmt_bind_param($query, "ss", $matricula, $password);
mysqli_stmt_execute($query);

//Se obtiene el resultado de la query y se verifica que la matricula y contraseña ingresados estén en la entidad de administradores, en caso de si estar,
//usa variables de sesión para guardar la validación y el usuario 
$result = mysqli_stmt_get_result($query);
$nr = mysqli_num_rows($result);
if ($nr == 1) {
  $query1 = mysqli_query($conn,"SELECT nombre FROM MDP_Usuario WHERE matricula = '".$matricula."'");
  $row = mysqli_fetch_assoc($query1);
  $_SESSION['user_name'] = $row["nombre"];
  $_SESSION['validation'] = True;
  $_SESSION['user_id'] = $matricula;
  //$_SESSION['username'] = $password;
  header("Location: ../opcion_maquinas/prueba.php");
}

//en caso contrario redirige al inicio de sesión otra vez y con una variable de sesión guarda como falsa la validación
else {
  $_SESSION['validation'] = False;
  header("Location: sign_in.php");
}

?>
