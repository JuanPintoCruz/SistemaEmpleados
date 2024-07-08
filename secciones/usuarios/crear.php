<?php
// base de datos
include("../../bd.php");

if($_POST){
  // Recolecta los datos del formulario
  $usuario = (isset($_POST["usuario"]) ? $_POST["usuario"] : "");
  $password = (isset($_POST["password"]) ? $_POST["password"] : "");
  $correo = (isset($_POST["correo"]) ? $_POST["correo"] : "");

  // Prepara la consulta SQL para insertar datos
  $sentencia = $conexion->prepare("INSERT INTO tbl_usuarios (usuario, password, correo) VALUES (:usuario, :password, :correo)");

  // Asigna los valores a los parámetros de la consulta
  $sentencia->bindParam(":usuario", $usuario);
  $sentencia->bindParam(":password", $password);
  $sentencia->bindParam(":correo", $correo);

  // Ejecuta la consulta y redirige después de la inserción
  if ($sentencia->execute()) {
      // Redirige al usuario después de la inserción exitosa
   //Icon 
   $mensaje="Registro agregado";
   header("Location: index.php?mensaje=".urlencode($mensaje));
  } else {
      // Imprime un mensaje de error si la ejecución de la consulta falla
      echo "Error al insertar datos en la base de datos.";
  }
}
?>

<?php include("../../templates/header.php"); ?>

<br>
<div class="card">
  <div class="card-header">
      Agregar Usuario
  </div>
  <div class="card-body">
      <form action="" method="post" enctype="multipart/form-data">
         <div class="mb-3">
          <label for="usuario" class="form-label">Nombre del Usuario:</label>
          <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Nombre del Usuario"/>
         </div>
         <div class="mb-3">
          <label for="password" class="form-label">Contraseña:</label>
          <input type="password" class="form-control" name="password" id="password" placeholder="Escribe tu contraseña"/>
         </div>
         <div class="mb-3">
          <label for="correo" class="form-label">Correo electrónico:</label>
          <input type="email" class="form-control" name="correo" id="correo" placeholder="Escribe tu correo electrónico" />
         </div>
         
          <button type="submit" class="btn btn-success">Agregar</button>
          <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
      
      </form>
  </div>
  <div class="card-footer text-muted">
  </div>
</div>

<?php include("../../templates/footer.php"); ?>