<?php
// Incluye el archivo de conexión a la base de datos
include("../../bd.php");

// Verifica si se está solicitando editar un usuario
if(isset($_GET['txtID'])) {
    $txtID = $_GET['txtID'];

    // Selecciona la información del usuario específico para editar
    $sentencia = $conexion->prepare("SELECT * FROM `tbl_usuarios` WHERE id = :id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $usuario = $sentencia->fetch(PDO::FETCH_ASSOC);

    // Asigna los valores del usuario a las variables correspondientes
    $usuarioNombre = $usuario["usuario"];
    $usuarioPassword = $usuario["password"];
    $usuarioCorreo = $usuario["correo"];
}

// Procesa el formulario enviado por POST
if($_POST){
    // Recolecta los datos del formulario
    $txtID = (isset($_POST["txtID"]) ? $_POST["txtID"] : "");
    $usuario = (isset($_POST["usuario"]) ? $_POST["usuario"] : "");
    $password = (isset($_POST["password"]) ? $_POST["password"] : "");
    $correo = (isset($_POST["correo"]) ? $_POST["correo"] : "");

    // Prepara la consulta SQL para actualizar los datos del usuario
    $sentencia = $conexion->prepare("UPDATE tbl_usuarios SET 
    usuario = :usuario,
     password = :password, correo = :correo WHERE id = :id");

    // Asigna los valores a los parámetros de la consulta
    $sentencia->bindParam(":usuario", $usuario);
    $sentencia->bindParam(":password", $password);
    $sentencia->bindParam(":correo", $correo);
    $sentencia->bindParam(":id", $txtID);

    // Ejecuta la consulta
    $sentencia->execute();
    $mensaje="Registro Actualizado";
    header("Location: index.php?mensaje=".urlencode($mensaje));
    exit;
}
?>

<?php include("../../templates/header.php"); ?>
<br>
<div class="card">
    <div class="card-header">
        Editar Usuario
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="txtID" class="form-label">ID:</label>
                <input type="text" value="<?php echo $txtID; ?>" class="form-control" readonly name="txtID" id="txtID" aria-describedby="helpId"/>
            </div>
            <div class="mb-3">
                <label for="usuario" class="form-label">Nombre del Usuario:</label>
                <input type="text" value="<?php echo $usuarioNombre; ?>" class="form-control" name="usuario" id="usuario" placeholder="Nombre del Usuario"/>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" value="<?php echo $usuarioPassword; ?>" class="form-control" name="password" id="password" placeholder="Escribe tu contraseña"/>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo electrónico:</label>
                <input type="email" value="<?php echo $usuarioCorreo; ?>" class="form-control" name="correo" id="correo" placeholder="Escribe tu correo electrónico"/>
            </div>
           
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
    <div class="card-footer text-muted">
    </div>
</div>

<?php include("../../templates/footer.php"); ?>