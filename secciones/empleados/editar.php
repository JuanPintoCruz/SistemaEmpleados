<?php 
include("../../bd.php");


if(isset($_GET['txtID'])) {
    $txtID = (isset($_GET['txtID']) ? $_GET['txtID'] : '');
    
    // Seleccionar la información del puesto específico para editar
    $sentencia = $conexion->prepare("SELECT * FROM `tbl_empleados` WHERE id = :id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);
    $nombredelpuesto=$registro["nombredelpuesto"];

    $primernombre=$registro["primernombre"];
    $segundonombre=$registro["segundonombre"];
    $primerapellido=$registro["primerapellido"];
    $segundoapellido=$registro["segundoapellido"];

    $foto=$registro["foto"];
    $cv=$registro["cv"]; 

    $fechadeingreso=$registro["idpuesto"];
    $fechadeingreso=$registro["fechadeingreso"];
    // Asignar el nombre de archivo al parámetro de la consulta
    $sentencia->bindParam(":foto", $nombreArchivo_cv);

    $sentencia->bindParam(":primernombre", $primernombre);
    $sentencia->bindParam(":segundonombre", $segundonombre);
    $sentencia->bindParam(":primerapellido", $primerapellido);
    $sentencia->bindParam(":segundoapellido", $segundoapellido);
    $sentencia->bindParam(":idpuesto", $idpuesto);
    $sentencia->bindParam(":fechadeingreso", $fechadeingreso);

    }
    // Consulta para obtener la lista de puestos
$sentencia_puestos = $conexion->prepare("SELECT id, nombredelpuesto FROM tbl_puestos");
$sentencia_puestos->execute();
$lista_tbl_puestos = $sentencia_puestos->fetchAll(PDO::FETCH_ASSOC);


// Verifica si se ha enviado el formulario por POST
if($_POST){
    // Recolecta los datos del formulario
    $txtID = isset($_POST["txtID"]) ? $_POST["txtID"] : "";
    $primernombre = isset($_POST["primernombre"]) ? $_POST["primernombre"] : "";
    $segundonombre = isset($_POST["segundonombre"]) ? $_POST["segundonombre"] : "";
    $primerapellido = isset($_POST["primerapellido"]) ? $_POST["primerapellido"] : "";
    $segundoapellido = isset($_POST["segundoapellido"]) ? $_POST["segundoapellido"] : "";
    $idpuesto = isset($_POST["idpuesto"]) ? $_POST["idpuesto"] : "";
    $fechadeingreso = isset($_POST["fechadeingreso"]) ? $_POST["fechadeingreso"] : "";

    $sentencia = $conexion->prepare(" UPDATE  tbl_empleados 
    SET
        primernombre=:primernombre,
        segundonombre=:segundonombre,
        primerapellido=:primerapellido,
        segundoapellido=:segundoapellido,
        idpuesto=:idpuesto,
        fechadeingreso=:fechadeingreso
        WHERE id=:id");

    // Asigna los valores a los parámetros de la consulta
    $sentencia->bindParam(":primernombre", $primernombre);
    $sentencia->bindParam(":segundonombre", $segundonombre);
    $sentencia->bindParam(":primerapellido", $primerapellido);
    $sentencia->bindParam(":segundoapellido", $segundoapellido);
    $sentencia->bindParam(":idpuesto", $idpuesto);
    $sentencia->bindParam(":fechadeingreso", $fechadeingreso);
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

     //Actualizar foto 
    $foto = isset($_FILES["foto"]["name"]) ? $_FILES["foto"]["name"] : "";
    $fecha_actual = new DateTime();
    $nombreArchivo_foto = ($foto != '') ? $fecha_actual->getTimestamp() . "_" . $foto : "";
    $tmp_foto = isset($_FILES["foto"]["tmp_name"]) ? $_FILES["foto"]["tmp_name"] : "";
    if ($tmp_foto != '') {
        move_uploaded_file($tmp_foto, "./img/" . $nombreArchivo_foto);
            //Buscar el archivo relacionado con los empleados
        $sentencia = $conexion->prepare("SELECT foto,cv FROM tbl_empleados WHERE id = :id");
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
        $registro_recuperado=$sentencia->fetch(PDO::FETCH_LAZY);
        if(isset($registro_recuperado["foto"]) && $registro_recuperado["foto"]!=""){
            if(file_exists("./img/".$registro_recuperado["foto"])){
                // borra el registro
                unlink("./img/".$registro_recuperado["foto"]);
            }
        }
        
        $sentencia = $conexion->prepare("UPDATE tbl_empleados SET foto=:foto WHERE id=:id");
        // Asignar el nombre de archivo al parámetro de la consulta
        $sentencia->bindParam(":foto", $nombreArchivo_foto);
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
    }

        //Actualizar cv
        $cv = isset($_FILES["cv"]["name"]) ? $_FILES["cv"]["name"] : "";
        $fecha_actual = new DateTime();
        $nombreArchivo_cv = ($cv != '') ? $fecha_actual->getTimestamp() . "_" . $cv : "";
        $tmp_cv = isset($_FILES["cv"]["tmp_name"]) ? $_FILES["cv"]["tmp_name"] : ""; // Cambiar "foto" a "cv"
        if ($tmp_cv != '') {
            move_uploaded_file($tmp_cv, "./cv/" . $nombreArchivo_cv);
            //Buscar el archivo relacionado con los empleados
            $sentencia = $conexion->prepare("SELECT foto,cv FROM tbl_empleados WHERE id = :id");
            $sentencia->bindParam(":id", $txtID);
            $sentencia->execute();
            $registro_recuperado = $sentencia->fetch(PDO::FETCH_LAZY);
            if (isset($registro_recuperado["cv"]) && $registro_recuperado["cv"] != "") {
                if (file_exists("./cv/" . $registro_recuperado["cv"])) {
                    // borra el registro
                    unlink("./cv/" . $registro_recuperado["cv"]);
                }
            }

            $sentencia = $conexion->prepare("UPDATE tbl_empleados SET cv=:cv WHERE id=:id");
            // Asignar el nombre de archivo al parámetro de la consulta
            $sentencia->bindParam(":cv", $nombreArchivo_cv);
            $sentencia->bindParam(":id", $txtID);
            $sentencia->execute();
        }
        $mensaje="Registro Actualizado";
        header("Location: index.php?mensaje=".urlencode($mensaje));
        exit;
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
                <label for="txtID" class="form-label">ID:</label>
                <input type="text" class="form-control" readonly name="txtID" id="txtID" value="<?php echo $txtID; ?>"/>
            </div>
            <div class="mb-3">
                <label for="primernombre" class="form-label">Primer Nombre</label>
                <input type="text" class="form-control" value="<?php echo $primernombre; ?>" name="primernombre" id="primernombre" placeholder="Escribe tu Primer Nombre" />
            </div>
            <div class="mb-3">
                <label for="segundonombre" class="form-label">Segundo Nombre</label>
                <input type="text" class="form-control" value="<?php echo $segundonombre; ?>" name="segundonombre" id="segundonombre" placeholder=" Escribe tu Segundo Nombre" />
            </div>
            <div class="mb-3">
                <label for="primerapellido" class="form-label">Primer Apellido</label>
                <input type="text" class="form-control" value="<?php echo $primerapellido; ?>" name="primerapellido" id="primerapellido" placeholder="Escribe tu Primer Apellido" />
            </div>
            <div class="mb-3">
                <label for="segundoapellido" class="form-label">Segundo Apellido</label>
                <input type="text" class="form-control" value="<?php echo $segundoapellido; ?>" name="segundoapellido" id="segundoapellido" placeholder="Escribe tu Segundo Apellido" />
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto:</label>
                <br>
                <img id="previewImage" width="100" src="img/<?php echo $foto; ?>" class="img-fluid rounded" alt="Vista previa de la imagen">
                <input type="file" class="form-control" name="foto" id="foto" aria-describedby="helpId" placeholder="Foto" />
            </div>
            <div class="mb-3">
                <label for="cv" class="form-label">CV (PDF):</label>
                <br>
                <a href="<?php echo $cv; ?>"><?php echo $cv; ?></a>
                <input type="file" class="form-control" name="cv" id="cv" placeholder="CV" aria-describedby="fileHelpId" />
            </div>
            <div class="mb-3">
                <label for="idpuesto" class="form-label">Puesto</label>
                <select class="form-select form-select-sm" name="idpuesto" id="idpuesto">
                    <?php foreach($lista_tbl_puestos as $registro_puesto): ?>
                        <option value="<?php echo $registro_puesto['id']; ?>" <?php if ($registro_puesto['id'] == $idpuesto) echo "selected"; ?>><?php echo $registro_puesto['nombredelpuesto']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="fechadeingreso" class="form-label">Fecha de Ingreso</label>
                <input type="date" class="form-control" value="<?php echo $fechadeingreso; ?>" name="fechadeingreso" id="fechadeingreso" aria-describedby="emailHelpId" placeholder="Fecha de Ingreso" />
            </div>
            
            <button type="submit" class="btn btn-success">Actualizar registro</button>
            <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
    <div class="card-footer text-muted">
    </div>
</div>
    <!-- de la imagen o -->
    <script>
        // Función para inicializar la vista previa con la imagen existente
         function initPreviewImage() {
            var existingImageSrc = document.getElementById('previewImage').getAttribute('src');
                if (!existingImageSrc || existingImageSrc === '#') {
                     document.getElementById('previewImage').style.display = 'none';
                } else{
                    document.getElementById('previewImage').style.display = 'block';
                }
            }
            // Llama a la función de inicialización de vista previa al cargar la página
            window.addEventListener('load', initPreviewImage);
            document.getElementById('foto').addEventListener('change', function(event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('previewImage').setAttribute('src', event.target.result);
                    document.getElementById('previewImage').style.display = 'block'; // Asegura que la imagen previa sea visible
                };
                reader.readAsDataURL(file);
            });
            </script>

<?php include("../../templates/footer.php"); ?>