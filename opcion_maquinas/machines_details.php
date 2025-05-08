<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../sign_in/sign_in.php');
    exit;
    }
    include 'conection.php';
    if (isset($_POST["url"])) {
	$url = mysqli_real_escape_string($conn,$_POST["url"]);
        $up_id = mysqli_real_escape_string($conn,$_GET["id"]);
        $query = mysqli_prepare($conn, "UPDATE MDP_Maquina SET imgMaquina = ?  WHERE id = ? ;");
        mysqli_stmt_bind_param($query, "ss", $url, $up_id);
	mysqli_stmt_execute($query);
    }
    if (isset($_POST["machine_name"])) {
        $machine_name = mysqli_real_escape_string($conn,$_POST["machine_name"]);
	$up_id = mysqli_real_escape_string($conn,$_GET["id"]);
	$query = mysqli_prepare($conn, "UPDATE MDP_Maquina SET nombre = ?  WHERE id = ? ;");
	mysqli_stmt_bind_param($query, "ss", $machine_name, $up_id);
	mysqli_stmt_execute($query);
    }
    if (isset($_POST["estado"])) {
        $up_est = mysqli_real_escape_string($conn,$_POST["estado"]);
        $up_id = mysqli_real_escape_string($conn,$_GET["id"]);
        $userId = mysqli_real_escape_string($conn,$_SESSION['user_id']);
        try {
            $conn->begin_transaction();
	    $query = mysqli_prepare($conn, "UPDATE MDP_Maquina SET id_estado = ?  WHERE id = ?;");
            mysqli_stmt_bind_param($query, "ss", $up_est, $up_id);
            mysqli_stmt_execute($query);
            $query = mysqli_prepare($conn, "INSERT INTO MDP_Maquina_uso_usuario (id_maquina, Matricula_usuario, estado_maquina) SELECT MDP_Maquina.id AS id_maquina, ? AS Matricula_usuario, MDP_Maquina.id_estado AS estado_maquina FROM MDP_Maquina WHERE MDP_Maquina.id = ? ;");
            mysqli_stmt_bind_param($query, "ss", $userId, $up_id);
	    mysqli_stmt_execute($query);
            $conn->commit();
        }
        catch (Exception $e) {
            $conn->rollback();
            echo "Transacción fallida: " . $e->getMessage();
        }
    }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>maquinas</title>
    <link rel="stylesheet" href="styless.css">
     <script src="selectState.js"></script>
</head>
<body>
    <div id="mycontainer">

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
                        <td class="lateral_menu_icons_alineacion"><img class="lateral_menu_icons" src="../logos/machines.png"></img></td>
                        <td><a href="../opcion_maquinas/prueba.php"><h4>Máquinas</h4></a></td>
                    </tr>
                </table>
            </div>

            <div>
                <table class= lateral_table>
                    <tr>
                        <td class="lateral_menu_icons_alineacion"><img class="lateral_menu_icons" src="../logos/reports.png"</img></td>
                        <td><a href="../opcion_historial/prueba.php"><h4>Historial</h4></a></td>
                    </tr>
                </table>
            </div>

            <div>
                <table  class= lateral_table>
                    <tr>
                        <td class="lateral_menu_icons_alineacion"><img class="lateral_menu_icons" src="../logos/users.png"></img></td>
                        <td><a href="../opcion_usuarios/prueba.php"><h4>Usuarios</h4></a></td>
                    </tr>
                </table>
            </div>

            <div id="lateral_name">
                <table  class= lateral_table id="logOutSpace">
                    <tr>
                        <td class="img_disps_align"><a href="../logout.php"><img class="lateral_menu_icons" src="../logos/log_out.png"></img></a></td>
                    </tr>
                </table>
            </div>
        </div>

        <div id="right_column">
            <?php
            include 'conection.php';
            if (isset($_GET["id"])) {
                $id = mysqli_real_escape_string($conn,$_GET["id"]);
		$query = mysqli_prepare($conn, "SELECT * FROM MDP_maquina_detalles WHERE id = ? ;");
                mysqli_stmt_bind_param($query, "s", mysqli_real_escape_string($conn,$_GET["id"]));
                mysqli_stmt_execute($query);
                $result = mysqli_stmt_get_result($query);
                $row = mysqli_fetch_assoc($result);
            }

            else {
                echo 'Error en la petición';
            }
            ?>
            <center>
                <?php
                    echo '<form method="post" action="machines_details.php?id='.$id.'">';
                ?>
                <label for="buttomEditMaName"><img id="add_icon" src="https://cdn-icons-png.flaticon.com/512/45/45706.png" height="27px%";></label>
                <input type="checkbox" id="buttomEditMaName">
                <?php
                    echo '<input type="text" class="machine_name" name="machine_name" value="'.$row["nombre"].'">'
                ?>
                </form>
		<?php echo '<p>('.$row["tipo_maquina"].')</p>'; ?>
                <table class="lateral_table">
                    <tbody>
                        <tr>
                            <th colspan="2">
			   	<?php
				   $query = mysqli_prepare($conn, "SELECT * FROM MDP_Maquina WHERE id = ?");
				   mysqli_stmt_bind_param($query, "s", mysqli_real_escape_string($conn,$_GET["id"]));
				   mysqli_stmt_execute($query);
			           $result = mysqli_stmt_get_result($query);
				   $row = mysqli_fetch_assoc($result);
				   if ($row["imgMaquina"] != '') {
                                    echo '<img id="img_maquina_impresora3d"src="'.$row["imgMaquina"].'" width="50%">';
                                   }
                                   else {
                                    echo '<img id="img_maquina_impresora3d"src= "https://img.icons8.com/?size=100&id=j1UxMbqzPi7n&format=png&color=000000" width="50%">';
                                   }
				   //echo '<img id="img_maquina_impresora3d"src="'.$row["imgMaquina"].'" width="50%">';
				?>
                            </th>
                        </tr>
                        <tr>
                            <td colspan = "2" class="img_disps_align">
                              <?php
                                echo '<form method="post" action="machines_details.php?id='.$id.'">';
                              ?>
                                    <label for = "displayMachinePhoto"><img class="delete_icon" src="https://img.icons8.com/?size=100&id=lMK80N2QxxpY&format=png&color=000000"></label>
                                    <input type="checkbox" id="displayMachinePhoto"> <br>
                                    <input type= "url" class="MachinePhoto" placeholder="URL de la nueva imágen" name="url" required>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td id="texto" style="text-align: right;">Estado de la máquina: </td>
                            <td id="texto">
                                <table>
                                    <tr>
                                        <td>
                                            <?php
                                                echo '<img src="../logos/estados/'.$row["id_estado"].'.png" width="40">';
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                echo '<form method="post" action="machines_details.php?id='.$id.'">'
                                            ?>
                                                    <select onchange="this.form.submit()" name="estado">
                                                        <?php
                                                        if($row["id_estado"] == 1) {
                                                            echo '<option value="1">disponible</option>';
                                                            echo '<option value="2">en uso</option>';
                                                            echo '<option value="3">fuera de servicio</option>';
                                                        }
                                                        elseif($row["id_estado"] == 2) {
                                                            echo '<option value="2">en uso</option>';
                                                            echo '<option value="1">disponible</option>';
                                                            echo '<option value="3">fuera de servicio</option>';
                                                        }
                                                        else {
                                                            echo '<option value="3">fuera de servicio</option>';
                                                            echo '<option value="2">en uso</option>';
                                                            echo '<option value="1">disponible</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </form>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="img_disps_align" >
                                <div class="filter_option delete_div"> <label for="display_hist" class="btn-buscar">Historial</label> </div>
                                <?php
                                    if (isset($_POST["buscar"])) {
                                        echo '<input type="checkbox" id="display_hist" checked>';
                                    }
                                    else {
                                        echo  '<input type="checkbox" id="display_hist">';
                                    }
                                ?>

                                <div class="hist_div">
                                    <?php
                                        echo '<form method="post" action="machines_details.php?id='.$_GET["id"].'">';
                                    ?>
                                            <div class="filter_option">
                                                Buscar por:
                                                <select name="parameter" class="filterInputSpace">
                                                <option value="">Mostrar todo</option>
                                                <option value="matricula">Matrícula</option>
                                                <option value="nombre">Nombre</option>
                                                <option value="estado">Estado</option>
                                                </select>
                                            </div>
                                            <div class="filter_option">
                                                <input type="text" placeholder="Buscar" maxlength="100" name="str" class="filterInputSpace">
                                                desde:
                                                <input type="date" name="fecha_init">
                                                hasta:
                                                <input type="date" name="fecha_fin">
                                            </div>
                                            <div class="filter_option">
                                                <input class="btn-buscar" type="submit" value="Buscar" name="buscar">
                                            </div>
                                        </form>


                                    <?php

                                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                            $parameter = htmlspecialchars($_POST['parameter']);
                                            $str = htmlspecialchars($_POST['str']);
                                            if($parameter == '' AND ($_POST["fecha_init"] == '' OR $_POST["fecha_fin"] == '')) {
                                                $query = mysqli_query($conn,"select * FROM MDP_Maquina_uso_usuario_detail WHERE id_maquina = '".$_GET["id"]."' ORDER BY fecha DESC;");
                                            }

                                            elseif($parameter == '' AND $_POST["fecha_init"] != '' AND $_POST["fecha_fin"] != '') {
                                                $query = mysqli_query($conn,"select * FROM MDP_Maquina_uso_usuario_detail WHERE fecha BETWEEN '".$_POST["fecha_init"]." 00:00:00' AND '".$_POST["fecha_fin"]." 23:59:59' AND id_maquina = '".$_GET["id"]."' ORDER BY fecha DESC;");
                                            }

                                            elseif($parameter != '' AND ($_POST["fecha_init"] == '' OR $_POST["fecha_fin"] == '')) {
                                                $query = mysqli_query($conn,"select * FROM MDP_Maquina_uso_usuario_detail WHERE ".$parameter." LIKE '".$str."%' AND id_maquina = '".$_GET["id"]."' ORDER BY fecha DESC;");

                                            }

                                            else {
                                                $query = mysqli_query($conn,"select * FROM MDP_Maquina_uso_usuario_detail WHERE ".$parameter." LIKE '".$str."%' AND id_maquina = '".$_GET["id"]."' AND fecha BETWEEN '".$_POST["fecha_init"]." 00:00:00' AND '".$_POST["fecha_fin"]." 23:59:59' ORDER BY fecha DESC;");
                                            }
                                        }

                                        else {
                                        $query = mysqli_query($conn,"select * FROM MDP_Maquina_uso_usuario_detail WHERE id_maquina = '".$_GET["id"]."' ORDER BY fecha DESC;");
                                        }
                                        if (mysqli_num_rows($query) > 0) {
                                            echo '<table id= "hist_table">';
                                            echo '<tr>';
                                            echo '<th>Matrícula</th>';
                                            echo '<th>Nombre</th>';
                                            echo '<th>Estado de la máquina</th>';
                                            echo '<th>Fecha</th>';
                                            echo '</tr>';
                                            while ($row = mysqli_fetch_assoc($query)) {
                                                echo '<tr>';
                                                echo '<td>'.$row["matricula"].'</td>';
                                                echo '<td>'.$row["nombre"].'</td>';
                                                echo '<td>'.$row["estado"].'</td>';
                                                echo '<td>'.$row["fecha"].'</td>';
                                                echo '</tr>';
                                            }
                                            echo '</table>';
                                        }
                                        else {
                                                
                                        }
                                    ?>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </center>
        </div>
    </div>
</body>
</html>
