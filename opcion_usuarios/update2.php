<?php
  session_start();
  include '../opcion_maquinas/conection.php';

 if(isset($_GET["update_confirm"]) AND $_GET["update_confirm"] == "true") {
   $_SESSION["gbl_update"] = "True";
   $_SESSION["global_update_id"] = $_GET["id"];
   header("Location: prueba.php");
   exit;
 }

  elseif(isset($_GET["update_confirm"]) AND $_GET["update_confirm"] == "false") {
   $_SESSION["gbl_update"] = "False";
   header("Location: prueba.php");
   exit;
  }

  if(isset($_POST["update_send"]) AND $_POST["update_send"] == "true") {
   $nombre = $_POST["nombre"];
   $ape_p = $_POST["ape_p"];
   $ape_m = $_POST["ape_m"];
   $matricula = $_SESSION["global_update_id"];
   $query = mysqli_prepare($conn,"UPDATE  MDP_Usuario SET nombre = ? ,Apellido_paterno = ? , Apellido_materno = ? WHERE matricula = ?");
   mysqli_stmt_bind_param($query, "ssss", $nombre, $ape_p, $ape_m, $matricula);
   mysqli_stmt_execute($query);
   $_SESSION["gbl_update"] = "False";
   header("Location: prueba.php");
   exit;
  }
?>
