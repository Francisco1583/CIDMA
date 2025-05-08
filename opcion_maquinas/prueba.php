<?php
 session_start();
 if (!isset($_SESSION['user_id'])) {
   header('Location: ../sign_in/sign_in.php');
   exit;
 }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>maquinas</title>
    <link rel="stylesheet" href="styless.css">
</head>
<body>
    <div id="mycontainer">
	<!-- Todo dentro de "left_column" es el menú lateral -->
        <div id="left_column">
	    <br></br>
            <div id="userimg_config">
                <img id="user_img" src="https://www.logolynx.com/images/logolynx/eb/ebb57b64c932f54ffced3c416b4f652d.jpeg" >
            </div>
            <div id="lateral_name">
                <h4><?php echo $_SESSION['user_name']; ?></h4>
            </div>
            <div>
                <table class= lateral_table>
                    <tr>
			<!-- Botón de interfaz 'Máquinas' -->
                        <td class="lateral_menu_icons_alineacion"><img class="lateral_menu_icons" src="../logos/machines.png"></img></td>
                        <td><a href="../opcion_maquinas/prueba.php"><h4>Máquinas</h4></a></td>
                    </tr>
                </table>
            </div>
            <div>
                <table class= lateral_table>
                    <tr>
			<!-- Botón de interfaz 'Historial' -->
                        <td class="lateral_menu_icons_alineacion"><img class="lateral_menu_icons" src="../logos/reports.png"</img></td>
                        <td><a href="../opcion_historial/prueba.php"><h4>Historial</h4></a></td>
                    </tr>
                </table>
            </div>
            <div>
                <table  class= lateral_table>
                    <tr>
			<!-- Botón de interfaz 'Usuarios' -->
                        <td class="lateral_menu_icons_alineacion"><img class="lateral_menu_icons" src="../logos/users.png"></img></td>
                        <td><a href="../opcion_usuarios/prueba.php"><h4>Usuarios</h4></a></td>
                    </tr>
                </table>
            </div>

            <div id="lateral_name">
                <table  class= lateral_table id="logOutSpace">
                    <tr>
			<!-- Botón para cerrar sesión -->
                        <td class="img_disps_align"><a href="../logout.php"><img class="lateral_menu_icons" src="../logos/log_out.png"></img></a></td>
                    </tr>
                </table>
            </div>
        </div>
        
	<!-- Todo dentro de "right_column" es la interfaz de la página -->
        <div id="right_column">
            <div id="title">
                <h1>Máquinas</h1>
            </div>
            <div id="machines_div">
                <?php
                include 'conection.php';
                $query = mysqli_query($conn,"SELECT * FROM MDP_maquinas_opcion ORDER BY id_tipo_maquina");
		// Cuando el número de máquinas registradas es mayor que 0, las muestra
                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                    echo '<div class="margin_machines">';
                        echo '<table class="tamano_dentro_td_disps">';
                            echo '<tr>';
                                echo '<td><img src="../logos/estados/'.$row["id_estado"].'.png" width="40"></td>';
                                echo '<td class="lateral_menu_icons_alineacion">
                                        <div class="delete_div">
                                            <a href="delete2.php?id='.$row["id"].'&delete_modal=true&name='.$row["nombre"].'"><img class="delete_icon" src="../logos/delete.png"></a>';
                                   echo'</div>';
                                echo '</td>';
                            echo '</tr>';
                            echo '<tr>';
                                echo '<td colspan="2" class="img_disps_align"><a href="machines_details.php?id='.$row["id"].'">';
				// Si la máquina tiene una imágen, la muestra
                                if ($row["img"] != '') {
                                    echo '<img class="img_disps"src="'.$row["img"].'"></img></a></td>';
                                }
				// Si no, muestra una imágen predeterminada
                                else {
                                    echo '<img class="img_disps"src="https://img.icons8.com/?size=100&id=j1UxMbqzPi7n&format=png&color=000000"></img></a></td>';
                                }
                            echo '</tr>';
                            echo '<tr>';
                                echo '<td colspan="2" class="img_disps_align"><h5>'. substr(strtoupper($row["nombre"]),0,20).'</h5>
                                <h6> ('.substr($row["id"],0,20).')</h6></td>';
                            echo '</tr>';
                        echo '</table>';
                    echo '</div>';

                    }
                }

		// Eliminar una máquina
                if(isset($_SESSION['delete_modal_confirmation']) AND $_SESSION['delete_modal_confirmation'] == 'True') {
                echo '<div id="add_div">
                        <div class="modal1">
                            <div class="container">
                                <div class="">
                                    <h2>Está a punto de eliminar "'.$_SESSION['delete_name'].'"</h2>
                                </div>
                                <h3>Al momento de eliminar una máquina también se eliminarán sus registros</h3>
                                <h3>¿Está seguro de eliminar la máquina seleccionada?</h3>
                                <a class="btn btn-red" href="delete2.php?delete_confirm=true">Eliminar</a>
                                <a class="btn btn-gray" href="delete2.php?delete_modal=false">Cancelar</a>
                            </div>
                        </div>
                      </div>';
                }

                mysqli_close($conn);
                ?>
                <div>
                    <table   class="tamano_dentro_td_disps">
                        <tr>
                            <td class="img_disps_align">
                                <div class="delete_div">
                                    <input type="checkbox" id="btn-modal-up" <?php  if (isset($_SESSION['error']) AND $_SESSION['error'] == True) { echo 'checked';}?>>
                                    <label for="btn-modal-up"><img class="img_disps" src="https://iconape.com/wp-content/png_logo_vector/add-40.png"></label>
                                    <!-- Botón para agregar máquinas -->
				    <h5>Agregar Máquinas</h5>
                                    <div class="modal">
                                        <div id="container-up">
                                            <div class="">
						<!-- Mensaje emergente donde se ingresan los datos de la nueva máquina -->
                                                <h2>Agregar Máquina</h2>
                                            </div>
                                            <form class="" action="create2.php" method="post">
                                                <div>
                                                    <div>
                                                        <h3><?php  if (isset($_SESSION['error']) AND $_SESSION['error'] == True) { echo '*ID existente, intente con otra*';}?></h3>
                                                    </div>
                                                    <div>
                                                        <input type="text" name="id" placeholder="ID" maxlength="25" required>
                                                    </div>
                                                    <div>
                                                        <input type="text" name="nombre" placeholder="Nombre" maxlength="100" required>
                                                    </div>
                                                    <select name = "tip_maquina" required>
                                                        <option value="">Tipo de máquina</option>
                                                        <?php
                                                            include 'conection.php';
                                                            $query = mysqli_query($conn,"SELECT * FROM MDP_tipo_maquina");
                                                            if (mysqli_num_rows($query) > 0) {
                                                                while ($row = mysqli_fetch_assoc($query)) {
                                                                echo '<option  value="'.$row["id"].'">'.$row["tipo_maquina"].'</option>';
                                                                }
                                                            }
                                                            else {
                                                                echo '<option value="">Sin tipos de máquina</option>';
                                                            }
                                                        ?>
                                                    </select>
						    <!-- Agregar o editar tipos de máquina, lleva a otra interfaz -->
                                                    <p>Para editar o agregar tipos de máquina, dar click  <a href="add_type.php">aquí</a></p>
                                                </div>
                                                <button type="submit" class="btn btn-buscar">Agregar</button>
                                                <a class="btn btn-gray" href="create2.php?cancel=True">Cancelar</a>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
