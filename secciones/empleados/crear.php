<?php
// Incluye el archivo de conexión a la base de datos
include("../../bd.php");

// Verifica si se ha enviado el formulario por POST
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Recolecta los datos del formulario
    $primernombre = isset($_POST["primernombre"]) ? $_POST["primernombre"] : "";
    $segundonombre = isset($_POST["segundonombre"]) ? $_POST["segundonombre"] : "";
    $primerapellido = isset($_POST["primerapellido"]) ? $_POST["primerapellido"] : "";
    $segundoapellido = isset($_POST["segundoapellido"]) ? $_POST["segundoapellido"] : "";
    $idpuesto = isset($_POST["idpuesto"]) ? $_POST["idpuesto"] : "";
    $fechadeingreso = isset($_POST["fechadeingreso"]) ? $_POST["fechadeingreso"] : "";

    // Recolecta los nombres de los archivos de las imágenes enviadas
    $foto = isset($_FILES["foto"]["name"]) ? $_FILES["foto"]["name"] : "";
    $cv = isset($_FILES["cv"]["name"]) ? $_FILES["cv"]["name"] : "";

    // Prepara la consulta SQL para insertar los datos del empleado
    $sentencia = $conexion->prepare("INSERT INTO `tbl_empleados` 
    (`primernombre`, `segundonombre`, `primerapellido`, `segundoapellido`, 
    `idpuesto`, `fechadeingreso`, `foto`, `cv`) 
    VALUES (:primernombre, :segundonombre, :primerapellido, 
    :segundoapellido, :idpuesto, :fechadeingreso, :foto, :cv)");

    // Asigna los valores a los parámetros de la consulta
    $sentencia->bindParam(":primernombre", $primernombre);
    $sentencia->bindParam(":segundonombre", $segundonombre);
    $sentencia->bindParam(":primerapellido", $primerapellido);
    $sentencia->bindParam(":segundoapellido", $segundoapellido);
    $sentencia->bindParam(":idpuesto", $idpuesto);
    $sentencia->bindParam(":fechadeingreso", $fechadeingreso);

    // Obtener la fecha actual para el nombre de archivo único
    $fecha_actual = new DateTime();
    $nombreArchivo_foto = ($foto != '') ? $fecha_actual->getTimestamp() . "_" . $foto : "";
    // Obtener la ubicación temporal del archivo cargado
    $tmp_foto = isset($_FILES["foto"]["tmp_name"]) ? $_FILES["foto"]["tmp_name"] : "";
    // Si se cargó una foto, mueve el archivo a la ubicación deseada
    if ($tmp_foto != '') {
        move_uploaded_file($tmp_foto, "./img/" . $nombreArchivo_foto);
    }
    // Asignar el nombre de archivo al parámetro de la consulta
    $sentencia->bindParam(":foto", $nombreArchivo_foto);
     
    // --cv
    $nombreArchivo_cv = ($cv != '') ? $fecha_actual->getTimestamp() . "_" . $cv : "";
    $tmp_cv = isset($_FILES["cv"]["tmp_name"]) ? $_FILES["cv"]["tmp_name"] : "";
    if ($tmp_cv != '') {
        move_uploaded_file($tmp_cv, "./cv/" . $nombreArchivo_cv);
    }
    // Asignar el nombre de archivo al parámetro de la consulta
    $sentencia->bindParam(":cv", $nombreArchivo_cv);

    // Ejecuta la consulta SQL
    $sentencia->execute();
   //Icon 
   $mensaje="Registro agregado";
   header("Location: index.php?mensaje=".urlencode($mensaje));
    exit; // Asegura que el script se detenga después de la redirección
}

// Selecciona la información de los puestos
$sentencia_puestos = $conexion->prepare("SELECT * FROM `tbl_puestos`");
$sentencia_puestos->execute();
$lista_tbl_puestos = $sentencia_puestos->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include("../../templates/header.php"); ?>

<br>
<div class="card">
    <div class="card-header">Datos del empleado</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="primernombre" class="form-label">Primer Nombre</label>
                <input type="text" class="form-control" name="primernombre" id="primernombre" aria-describedby="helpId"  placeholder="Escribe tu Primer Nombre" />
            </div>
            <div class="mb-3">
                <label for="segundonombre" class="form-label">Segundo Nombre</label>
                <input type="text" class="form-control" name="segundonombre" id="segundonombre" aria-describedby="helpId"  placeholder=" Escribe tu Segundo Nombre" />
            </div>
            <div class="mb-3">
                <label for="primerapellido" class="form-label">Primer Apellido</label>
                <input type="text" class="form-control" name="primerapellido" id="primerapellido" aria-describedby="helpId"  placeholder="Escribe tu Primer Apellido" />
            </div>
            <div class="mb-3">
                <label for="segundoapellido" class="form-label">Segundo Apellido</label>
                <input type="text" class="form-control" name="segundoapellido" id="segundoapellido" aria-describedby="helpId"  placeholder="Escribe tu Segundo Apellido" />
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto:</label>
                <input type="file" class="form-control" name="foto" id="foto" aria-describedby="helpId"  placeholder="Foto" />
            </div>
            <div class="mb-3">
                <label for="cv" class="form-label">CV (PDF):</label>
                <input type="file" class="form-control" name="cv" id="cv" placeholder="CV" aria-describedby="fileHelpId" />
            </div>
            <div class="mb-3">
                <label for="idpuesto" class="form-label">Puesto</label>
                <select class="form-select form-select-sm" name="idpuesto" id="idpuesto">
                    <?php foreach($lista_tbl_puestos as $registro_puesto): ?>
                        <option value="<?php echo $registro_puesto['id']; ?>"><?php echo $registro_puesto['nombredelpuesto']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="fechadeingreso" class="form-label">Fecha de Ingreso</label>
                <input type="date" class="form-control" name="fechadeingreso" id="fechadeingreso" aria-describedby="emailHelpId" placeholder="Fecha de Ingreso" max="9999-12-31" />
            </div>
            
            <button type="submit" class="btn btn-success">Agregar Registros</button>
            <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
    <div class="card-footer text-muted">
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
