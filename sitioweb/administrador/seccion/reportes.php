<?php
session_start();
if(!isset($_SESSION['usuario'])){
  header("Location:../index.php");
}else{

if($_SESSION['usuario']=="ok"){
$nombreUsuario=$_SESSION["nombreUsuario"];


}

}
ob_start();

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


</head>
<body>
<?php
include("../config/bd.php");
$sentenciaSQL= $conexion->prepare("SELECT * FROM libros");
$sentenciaSQL->execute();
$listaLibros=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);




?>

<h1> Reporte de libros </h1>
<table class="table table-bordered"> 
    <thead>
    <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Imagen</th>
            <th>Cantidad</th>
            <th>Precio</th>


        </tr> 
    </thead>
    <tbody>

    <?php foreach($listaLibros as $libro){ ?>


<tr>
    <td><?php echo $libro['id']; ?></td>
    <td><?php echo $libro['nombre']; ?></td>
    <td>
        <img class="img-thumbnail rounded" src="http://<?php echo $_SERVER['HTTP_HOST'];?>/sitioweb/img/<?php echo $libro['imagen']; ?>" width="50" alt="" srcset="">
    </td>
    <td><?php echo $libro['cantidad']; ?></td>
    <td><?php echo '$' . number_format($libro['precio'], 2); ?></td>
    




    </tr>
  <?php } ?>
</tbody>
</table> 
</body>
</html>
<?php
$html=ob_get_clean();
//echo $html;
require_once '../libreria/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled'=> true)); // Habilita PHP en el HTML (si es necesario)
$dompdf->setOptions($options);

$dompdf->loadHtml("$html"); // Corregí "edgar" a "Edgar"
$dompdf->setPaper('letter'); // Corregí "latter" a "letter"
//


//$dompdf->setPaper('A4','landscape');
$dompdf->render();
$dompdf->stream("archivo.pdf", array("Attachment" => false));





 
?>