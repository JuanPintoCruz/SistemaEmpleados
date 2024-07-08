<?php
// base de datos
include("../../bd.php");

if(isset($_GET['txtID'])) {
    $txtID = (isset($_GET['txtID']) ? $_GET['txtID'] : '');
    
    // Seleccionar la información del puesto específico para editar
    $sentencia = $conexion->prepare("SELECT * FROM `tbl_puestos` WHERE id = :id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $registro = $sentencia->fetch(PDO::FETCH_ASSOC);
    $nombredelpuesto=$registro["nombredelpuesto"];
    }
// Manejar la actualización del puesto si se envía el formulario
if($_POST){
    // Recolectar los datos del método POST
    $nombredelpuesto = (isset($_POST["nombredelpuesto"]) ? $_POST["nombredelpuesto"] : "");
    $txtID = (isset($_POST["txtID"]) ? $_POST["txtID"] : "");
    
    // Preparar la actualización de los datos
    $sentencia = $conexion->prepare("UPDATE tbl_puestos SET nombredelpuesto = :nombredelpuesto WHERE id = :id");
    $sentencia->bindParam(":nombredelpuesto", $nombredelpuesto);
    $sentencia->bindParam(":id", $txtID);
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
        Editar Puesto
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="txtID" class="form-label">ID:</label>
                <input type="text" class="form-control"  readonly name="txtID" 
                id="txtID" aria-describedby="helpId" 
                value="<?php echo $txtID; ?>"/>
           </div>
           <div class="mb-3">
                <label for="nombredelpuesto" class="form-label">Nombre del puesto:</label>
                <input type="text" class="form-control" name="nombredelpuesto" id="nombredelpuesto" aria-describedby="helpId" placeholder="Nombre del puesto" value="<?php echo isset($puesto['nombredelpuesto']) ? $puesto['nombredelpuesto'] : ''; ?>"/>
           </div>
           <button type="submit" class="btn btn-success">Actualizar</button>
           <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
    <div class="card-footer text-muted"></div>
</div>
<?php include("../../templates/footer.php"); ?>