<?php

include 'conection.php';

if(isset($_POST['add_type']))
{
    $tipo_maquina = mysqli_real_escape_string($conn,strtolower($_POST["tipo_maquina"]));
    $imagen = mysqli_real_escape_string($conn,$_POST["imagen"]);
    $query = mysqli_prepare($conn, "INSERT INTO MDP_tipo_maquina (tipo_maquina,img) VALUES (?, ?)");
    mysqli_stmt_bind_param($query, "ss", $tipo_maquina, $imagen);
    mysqli_stmt_execute($query);
    header("Location: add_type.php?add=true");
}


elseif(isset($_POST['delete-type']))
{
    $nombre =  mysqli_real_escape_string($conn,strtolower($_POST['nombre']));
    $tip_maquina =  mysqli_real_escape_string($conn,$_POST["tip_maquina"]);
    $query = mysqli_prepare($conn, "DELETE FROM  MDP_tipo_maquina WHERE id = ? ");
    mysqli_stmt_bind_param($query, "s", $tip_maquina);
    mysqli_stmt_execute($query);
    header("Location: add_type.php?delete=true");
}


elseif(isset($_POST['update-type'])){
  if($_POST['nombre'] != '') {
  	$nombre =  mysqli_real_escape_string($conn,strtolower($_POST['nombre']));
  	$tip_maquina =  mysqli_real_escape_string($conn,$_POST["tip_maquina"]);
  	$query = mysqli_prepare($conn, "UPDATE MDP_tipo_maquina SET tipo_maquina = ? WHERE id = ? ;");
    	mysqli_stmt_bind_param($query, "ss", $nombre, $tip_maquina);
    	mysqli_stmt_execute($query);
  }

  if($_POST['url'] != '') {
        $url =  mysqli_real_escape_string($conn,$_POST['url']);
        $tip_maquina =  mysqli_real_escape_string($conn,$_POST["tip_maquina"]);
        $query = mysqli_prepare($conn, "UPDATE MDP_tipo_maquina SET img  = ? WHERE id = ? ;");
        mysqli_stmt_bind_param($query, "ss", $url, $tip_maquina);
        mysqli_stmt_execute($query);
  }
   header("Location: add_type.php?update=true");

}


?>
