<?php
// base de datos
include("../../bd.php");

// seleccionar informacion
$sentencia = $conexion->prepare("SELECT *,
-- Subconsulta para obtener el nombre del puesto del empleado
(
    -- Inicia la subconsulta
    SELECT nombredelpuesto 
    FROM tbl_puestos 
    -- Selecciona el nombre del puesto 
    -- donde el id del puesto en tbl_puestos coincida con el idpuesto en tbl_empleados
    WHERE tbl_puestos.id = tbl_empleados.idpuesto 
    -- Limita la subconsulta a un único resultado
    LIMIT 1
) as puesto

 FROM `tbl_empleados`");
$sentencia->execute();
$lista_tbl_empleados = $sentencia->fetchAll(PDO::FETCH_ASSOC);


// eliminar datos
if(isset($_GET['txtID'])) {
    $txtID = isset($_GET['txtID']) ? $_GET['txtID'] : '';

    //Buscar el archivo relacionado con los empleados
    $sentencia = $conexion->prepare("SELECT foto,cv FROM tbl_empleados WHERE id = :id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $registro_recuperado=$sentencia->fetch(PDO::FETCH_LAZY);
    if(isset($registro_recuperado["foto"]) && $registro_recuperado["foto"]!=""){
        if(file_exists("./img/".$registro_recuperado["foto"])){
              unlink("./img/".$registro_recuperado["foto"]);
        }
    }
    if(isset($registro_recuperado["cv"]) && $registro_recuperado["cv"]!=""){
        if(file_exists("./cv/".$registro_recuperado["cv"])){
              unlink("./cv/".$registro_recuperado["cv"]);
        }
    }    
    $sentencia = $conexion->prepare("DELETE FROM tbl_empleados WHERE id = :id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $mensaje="Registro eliminado";
    header("Location: index.php?mensaje=".$mensaje);
    exit; 
}
?>
<?php include("../../templates/header.php"); ?>
<?php if(isset($_GET["mensaje"])) {?>
    <script>
        Swaill.fire({icon:"success",title:"<?php echo $_GET['mensaje']; ?>"});
    </script>
    <?php } ?>
<BR>

<h4>Empleados</h4>
<div class="card">
    <div class="card-header">
    <a name="" id="" class="btn btn-primary" href="crear.php" 
    role="button"> Agregar Registro</a >
    
    </div>

    <div class="card-body">
        <div class="table-responsive-sm">
            <table
                class="table" id="tabla_id">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Foto</th>
                        <th scope="col">CV</th>
                        <th scope="col">Puesto</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($lista_tbl_empleados as $registro){?>
                    <tr class="">
                        <td ><?php echo $registro['id'];?></td>
                        <td scope="row">
                        <?php echo $registro['primernombre'];?>
                        <?php echo $registro['segundonombre'];?>
                        <?php echo $registro['primerapellido'];?>
                        <?php echo $registro['segundoapellido'];?>                   
                    </td>
                    <td>
                        <img style="border-radius: 8px; border: 2px solid black;" width="100" 
                        height="100" src="img/<?php echo $registro['foto'];?>" alt=""/>
                    </td>
                        <td> <a href="cv/<?php echo $registro['cv'];?> ">
                            <?php echo $registro['cv'];?></a>
                        
                        </td>
                        <td><?php echo $registro['puesto'];?></td>
                        <td><?php echo $registro['fechadeingreso'];?></td>
                        <td>
                        <a class="btn btn-success" 
                            href="carta_recomendacion.php?txtID=<?php echo $registro ['id'] ;?>" 
                            role="button">Carta</a>
                          <a class="btn btn-primary" 
                            href="editar.php?txtID=<?php echo $registro ['id'] ;?>"
                            role="button">Editar</a>
                            <a class="btn btn-danger" 
                            href="javascript:borrar(<?php echo $registro ['id'] ;?>);" 
                            role="button">Eliminar</a>
                        </td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
        
    </div>
    <div class="card-footer text-muted">
    
    </div>
</div>

<script> 
function borrar(id) {

     Swal.fire({ title: '¿Desea borrar el registro?'
        , showCancelButton: true, 
        confirmButtonText: 'Si, Borrar' }).
        then((result) => { 
        if (result.isConfirmed)
        { window.location="index.php?txtID=" + id; 
        } 
    })
    }
 </script>
<?php include("../../templates/footer.php"); ?>