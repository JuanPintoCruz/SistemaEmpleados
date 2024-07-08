<?php 
session_start(); // Inicia la sesión antes de verificar si el usuario está autenticado

$url_base = "http://localhost/aplicacion/";

// Preguntar si el usuario no está autenticado
if(!isset($_SESSION["usuario"])){
    // Redirigir al usuario a la página de inicio de sesión
    header("Location:".$url_base."login.php");
    exit; // Asegúrate de salir del script después de redirigir al usuario
}
?>

<!doctype html>
<html lang="en">

<head>
  <title>Administrador del Sitio Web</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
 <!-- jQuery -->
 <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
  
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
  
  <!-- DataTables JavaScript -->
  <script type="text/javascript" charset="utf-8" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>


  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  </head>

<body>
    <header>
        <!-- place navbar here -->
    </header>
     <nav class="navbar navbar-expand navbar-light bg-light">
        <ul class="nav navbar-nav mx-auto">
            <li class="nav-item">
                <a class="nav-link active" href="#" aria-current="page"> <strong>
                Sistema</strong><span class="visually-hidden">(current)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>secciones/empleados/">Empleados</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>secciones/puestos/">Puestos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>secciones/usuarios/">Usuarios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>cerrar.php">Cerrar Sesion</a>
            </li>
        </ul>
     </nav>
     <main class="container">

     <!-- tmb puedes guardarlo en header -->
<?php if(isset($_GET["mensaje"])) { ?>
    <script>
        Swal.fire({icon:"success",title:"<?php echo htmlspecialchars($_GET['mensaje']); ?>"});
    </script>
<?php } ?>