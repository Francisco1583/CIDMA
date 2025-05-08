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
    <meta charset="utf-8">
    <link rel="stylesheet" href="styless.css">
</head>
<body>
    <div>
        <input type="checkbox" id="btn-modal-up" checked>
        <div class="tipMaOpciones">
            

            

            <div class="tipoMaquinaOpc" id="add_display">
		<!-- Editar datos de un tipo de máquina existente -->
                <div class="">
                    <h2>Editar tipo de máquina</h2>
                    <?php
                    if(isset($_GET["update"])) {
                        echo '<h3 class="confirmation_succes">Actualizado correctamente</h3>';
                    }
                    ?>
                </div>
                <form class="" action="save_image.php" method="post">
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
                    <div>
			<!-- Se ingresa la información -->
                        <input type="text" name="nombre" placeholder="Nuevo nombre" maxlength="100">
                    </div>
                    <div>
                        <input type="url" name="url" placeholder="Nueva URL de imagen" maxlength="1000">
                    </div>
                    <button type="submit" class="btn btn-buscar" name="update-type">Editar</button>
                    
                </form>

		<!-- Agregar nuevo tipo de máquina -->
                <div>
                    <h2>Agregar tipo de máquina<h2>
                </div>
                <?php
                    if(isset($_GET["add"])) {
                        echo '<h3 class="confirmation_succes">Agregado correctamente</h3>';
                    }
                ?>
                <form class="" action="save_image.php" method="post">
                    <div>
                        <input type="text" name="tipo_maquina" placeholder="Nuevo tipo de máquina" maxlength="100" required>
                    </div>
                    <div>
                        
                        <input type="url" name="imagen" placeholder="URL de imagen" maxlength="1000" required>
                    </div>
                    <div>
                        <input type="submit" class="btn btn btn-buscar" name = "add_type" value="Agregar"></input>
                        
                    </div>
                </form>

		<!-- Eliminar tipo de máquina ya existente -->
                <div class="">
                    <h2>Eliminar tipo de máquina</h2>
                    <?php
                    if(isset($_GET["delete"])) {
                        echo '<h3 class="confirmation_succes">eliminado correctamente</h3>';
                    }
                    ?>
                </div>
                <form class="" action="save_image.php" method="post">
                    <select name = "tip_maquina" required>
                        <option value="">Tipo de máquina</option>
                        <?php
                            include 'conection.php';
                            $query = mysqli_query($conn,"SELECT * FROM MDP_tipo_maquina WHERE id NOT IN(SELECT id_tipo_maquina FROM MDP_Maquina)");
                            if (mysqli_num_rows($query) > 0) {
                                while ($row = mysqli_fetch_assoc($query)) {
                                    echo '<option  value="'.$row["id"].'">'.$row["tipo_maquina"].'</option>';
                                }
                            }
                            else {
                                echo '<option value="">Sin tipos de máquina para eliminar</option>';
                            }
                        ?>
                    </select>
                    <div>
                      <button type="submit" class="btn btn-red" name="delete-type">Eliminar</button>
                      <a class="btn btn-gray" href="prueba.php">Regresar</a>
                    </div>
                </form>


            </div>
            
        </div>
    </div>
</body>
</html>
