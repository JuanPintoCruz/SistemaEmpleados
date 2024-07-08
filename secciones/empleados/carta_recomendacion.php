<?php
// Incluye el archivo de conexión a la base de datos
include("../../bd.php");

if(isset($_GET['txtID'])) {
    $txtID = (isset($_GET['txtID']) ? $_GET['txtID'] : '');
    
    // Seleccionar la información del puesto específico para editar
    $sentencia = $conexion->prepare("SELECT tbl_empleados.*, 
    (SELECT nombredelpuesto 
    FROM tbl_puestos 
    WHERE tbl_puestos.id = tbl_empleados.idpuesto) as nombredelpuesto 
    FROM tbl_empleados 
    WHERE id = :id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);

    $primernombre = $registro["primernombre"];
    $segundonombre = $registro["segundonombre"];
    $primerapellido = $registro["primerapellido"];
    $segundoapellido = $registro["segundoapellido"];
    $nombreCompleto = $primernombre . " " . $segundonombre . " " . $primerapellido . " " . $segundoapellido;

    $foto = $registro["foto"];
    $cv = $registro["cv"]; 
    $idpuesto = $registro["idpuesto"];
    $nombredelpuesto = $registro["nombredelpuesto"];
    $fechadeingreso = $registro["fechadeingreso"];

    $fechaInicio = new DateTime($fechadeingreso);
    $fechaFin = new DateTime(date('Y-m-d'));
    $diferencia = date_diff($fechaInicio, $fechaFin);
}
ob_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carta de Recomendación</title>
</head>
<body>
    <h1>Carta de Recomendación Laboral</h1>
    <br><br>
    Santa Clara, Perú <strong>01/06/2024</strong>
    <br><br>
    A quien le pueda interesar:
    <br><br>
    Reciba un cordial y respetuoso saludo.
    <br><br>
    A través de estas líneas deseo hacer de su conocimiento que el Sr(a)
    <strong><?php echo $nombreCompleto; ?></strong>, quien laboró en mi organización durante  
    <strong><?php echo $diferencia->y; ?> años</strong>,
    es un ciudadano de buenos valores. Ha demostrado ser un excelente trabajador,
    comprometido, responsable y fiel cumplidor de sus tareas.
    Siempre ha manifestado preocupación por mejorar, capacitarse y actualizar sus conocimientos.
    Durante estos años se ha desempeñado como: <strong><?php echo $nombredelpuesto; ?></strong>.
    <br><br>
    Sin más a qué referirme y esperando que esta misiva sea tomada en cuenta,
    dejo mi número de contacto para cualquier información de interés.
    <br><br><br>
    Atentamente,
    <br><br><br>
    Ing. Juan Martínez
</body>
</html>
<?php
$HTML = ob_get_clean();

// Debug: Verificar contenido HTML
file_put_contents("debug.html", $HTML);

require_once("../../libs/autoload.inc.php");
use Dompdf\Dompdf;

// Crear una instancia de Dompdf
$dompdf = new Dompdf();

$opciones = $dompdf->getOptions();
$opciones->set(array("isRemoteEnabled" => true));
$dompdf->setOptions($opciones);

$dompdf->loadHTML($HTML); // Aquí faltaba un punto y coma
$dompdf->setPaper('letter');
$dompdf->render();

// Debug: Verificar errores de Dompdf
$dompdf->stream("archivo.pdf", array("Attachment" => false));

// Debug: Verificar errores de renderizado
if ($dompdf->get_canvas()) {
    echo "Renderización exitosa";
} else {
    echo "Error en la renderización";
}
?>