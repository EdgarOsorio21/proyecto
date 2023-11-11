<?php include("../template/cabecera.php");?>

<?php
$txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
$txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";
$txtImagen = (isset($_FILES['txtImagen']['name'])) ? $_FILES['txtImagen']['name'] : "";
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";

// Nuevas variables para cantidad y precio
$txtCantidad = (isset($_POST['txtCantidad'])) ? $_POST['txtCantidad'] : 0;
$txtPrecio = (isset($_POST['txtPrecio'])) ? $_POST['txtPrecio'] : 0.00;

include("../config/bd.php");

switch($accion){
    case "Agregar":
        $sentenciaSQL = $conexion->prepare("INSERT INTO libros (nombre, imagen, cantidad, precio) VALUES (:nombre, :imagen, :cantidad, :precio);");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':cantidad', $txtCantidad);
        $sentenciaSQL->bindParam(':precio', $txtPrecio);

        $fecha = new DateTime();
        $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "imagen.jpg";

        $tmpImagen = $_FILES["txtImagen"]["tmp_name"];

        if ($tmpImagen != "") {
            move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);
        }

        $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
        $sentenciaSQL->execute();

        header("Location: productos.php");
        break;

        case "Modificar":
            $sentenciaSQL = $conexion->prepare("UPDATE libros SET nombre=:nombre, cantidad=:cantidad, precio=:precio WHERE id=:id");
            $sentenciaSQL->bindParam(':nombre', $txtNombre);
            $sentenciaSQL->bindParam(':cantidad', $txtCantidad);
            $sentenciaSQL->bindParam(':precio', $txtPrecio);
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();
        
            if ($txtImagen != "") {
                $fecha = new DateTime();
                $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "imagen.jpg";
                $tmpImagen = $_FILES["txtImagen"]["tmp_name"];
        
                move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);
        
                $sentenciaSQL = $conexion->prepare("SELECT imagen FROM libros WHERE id=:id");
                $sentenciaSQL->bindParam(':id', $txtID);
                $sentenciaSQL->execute();
                $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);
        
                if (isset($libro["imagen"]) && ($libro["imagen"] != "imagen.jpg")) {
                    if (file_exists("../../img/" . $libro["imagen"])) {
                        unlink("../../img/" . $libro["imagen"]);
                    }
                }
        
                $sentenciaSQL = $conexion->prepare("UPDATE libros SET imagen=:imagen WHERE id=:id");
                $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
                $sentenciaSQL->bindParam(':id', $txtID);
                $sentenciaSQL->execute();
            }
        
            header("Location: productos.php");
            break;
        

        case"Cancelar":
            header("Location:productos.php");   
            break;

        case"Seleccionar":
            $sentenciaSQL= $conexion->prepare("SELECT * FROM libros WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $libro=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

            $txtNombre=$libro['nombre'];
            $txtImagen=$libro['imagen'];
            //echo "Presionado botón Seleccionar";
            break;
                
        case"Borrar":

            $sentenciaSQL= $conexion->prepare("SELECT imagen FROM libros WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $libro=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

            if(isset($libro["imagen"]) &&($libro["imagen"]!="imagen.jpg")){

                if(file_exists("../../img/".$libro["imagen"])){

                    unlink("../../img/".$libro["imagen"]);

                }
            }

           $sentenciaSQL= $conexion->prepare("DELETE FROM libros WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            
            header("Location:productos.php");   
            break;
      
        
}


$sentenciaSQL= $conexion->prepare("SELECT * FROM libros");
$sentenciaSQL->execute();
$listaLibros=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);



?>


<div class="col-md-5">



<div class="card">
    <div class="card-header">
        Datos de Libro
    </div>

    <div class="card-body">
       
    <form method="POST" enctype="multipart/form-data">

<div class = "form-group">
<label for="txtID">ID:</label>
<input type="text" required readonly class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID"  placeholder="ID">
</div>

<div class = "form-group">
<label for="txtNombre">Nombre:</label>
<input type="text" required  class="form-control"   value="<?php echo $txtNombre;?>" name="txtNombre" id="txtNombre"  placeholder="Nombre del libro">
</div>

<div class = "form-group">
<label for="txtImagen">Imagen:</label>

  <br/>

    <?php if($txtImagen!=""){  ?>

        <img class="img-thumbnail rounded" src="../../img/<?php echo $txtImagen;?>" width="50" alt="" srcset="">

    <?php } ?>





<input type="file" class="form-control"  name="txtImagen" id="txtImagen"  placeholder="Nombre del libro">
</div>
<div class="form-group">
    <label for="txtCantidad">Cantidad:</label>
    <input type="number" required class="form-control" value="<?php echo $txtCantidad; ?>" name="txtCantidad" id="txtCantidad" placeholder="Cantidad de libros">
</div>

<div class="form-group">
    <label for="txtPrecio">Precio:</label>
    <input type="number" step="0.01" required class="form-control" value="<?php echo $txtPrecio; ?>" name="txtPrecio" id="txtPrecio" placeholder="Precio del libro">
</div>


<div class="btn-group" role="group" aria-label="">
    <button type="submit" name="accion"<?php echo ($accion=="Seleccionar")?"disabled":"";?> value="Agregar" class="btn btn-success">Agregar</button>
    <button type="submit" name="accion"<?php echo ($accion!="Seleccionar")?"disabled":"";?> value="Modificar" class="btn btn-warning">Modificar</button>
    <button type="submit" name="accion"<?php echo ($accion!="Seleccionar")?"disabled":"";?> value="Cancelar"  class="btn btn-info">Cancelar</button>
</div>

</form>

    </div>

   
</div>






</div>



<div class="col-md-7">


<a href="reportes.php" class="btn btn-primary mb-2">Generar PDF</a>

<table class="table table-bordered"> 
    <thead>
    <div class="col-md-7">
    <form method="GET">
        <div class="form-group ">
            <input type="text" name="searchID" placeholder="Buscar por ID" class="form-control">
        </div>
        <div class="form-group">
            <input type="text" name="searchNombre" placeholder="Buscar por Nombre" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary mb-2">Buscar</button>
    </form>
</div>

        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Imagen</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Acciones</th>

        </tr> 
    </thead>
    <tbody>
    <?php
$searchID = isset($_GET['searchID']) ? $_GET['searchID'] : "";
$searchNombre = isset($_GET['searchNombre']) ? $_GET['searchNombre'] : "";

// Crear una consulta SQL con búsqueda condicional
$query = "SELECT * FROM libros";
$whereClause = [];

if (!empty($searchID)) {
    $whereClause[] = "id = :searchID";
}

if (!empty($searchNombre)) {
    $whereClause[] = "nombre LIKE :searchNombre";
}

if (!empty($whereClause)) {
    $query .= " WHERE " . implode(" AND ", $whereClause);
}

$sentenciaSQL = $conexion->prepare($query);

if (!empty($searchID)) {
    $sentenciaSQL->bindParam(':searchID', $searchID);
}

if (!empty($searchNombre)) {
    $searchNombre = '%' . $searchNombre . '%'; // Añadir comodines % para la búsqueda parcial
    $sentenciaSQL->bindParam(':searchNombre', $searchNombre);
}

$sentenciaSQL->execute();
$listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<?php foreach($listaLibros as $libro){ ?>


    <tr>
        <td><?php echo $libro['id']; ?></td>
        <td><?php echo $libro['nombre']; ?></td>
        <td>
            <img class="img-thumbnail rounded" src="../../img/<?php echo $libro['imagen']; ?>" width="50" alt="" srcset="">
        </td>
        <td><?php echo $libro['cantidad']; ?></td>
        <td><?php echo '$' . number_format($libro['precio'], 2); ?></td>
        <td>
               

                <form method="post">

                <input type="hidden" name="txtID" id="txtID" value="<?php echo $libro['id'];?>"/>

                <input type="submit" name="accion"value="Seleccionar" class="btn btn-primary"/>

                <input type="submit" name="accion"value="Borrar" class="btn btn-danger"/>

                </form>
            
            </td>





        </tr>
      <?php } ?>
    </tbody>
</table>


</div>


<?php include("../template/pie.php");?>