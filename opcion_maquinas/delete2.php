<?php
        session_start();
	include 'conection.php';
	$id = 0;
		if(isset($_GET['delete_modal']) AND $_GET['delete_modal'] == 'true') {
                 $id = $_GET['id'];
                 $_SESSION['delete_id'] = $id;
		 $_SESSION['delete_name'] =  $_GET['name'];
                 $_SESSION['delete_modal_confirmation'] = 'True'; 
                 header("Location: prueba.php");
                 exit();
                }
               elseif(isset($_GET['delete_modal']) AND $_GET['delete_modal'] == 'false') {
                $_SESSION['delete_modal_confirmation'] = 'False'; 
                 header("Location: prueba.php");
                 exit();

		}
                 if(isset($_GET['delete_confirm']) AND $_GET['delete_confirm'] == 'true') {
		 $_SESSION['delete_modal_confirmation'] = 'False';
		 $delete_id = mysqli_real_escape_string($conn,$_SESSION['delete_id']);
		 $query = mysqli_prepare($conn, "DELETE FROM MDP_Maquina WHERE id = ? ");
		 mysqli_stmt_bind_param($query, "s", $delete_id);
		 mysqli_stmt_execute($query);
                 header("Location: prueba.php");
                 exit();
                }
?>
