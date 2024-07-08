<?php
$servidor="localhost";
$baseDeDatos="app";
$usuario="root";
$contrasenia="";

try { 
    $conexion = new PDO("mysql:host=$servidor;dbname=$baseDeDatos",$usuario,$contrasenia);
}
catch(PDOException $e) {
    echo"mensaje correcto". $e->getMessage();
}
?>