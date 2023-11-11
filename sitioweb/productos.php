<?php
include("template/cabecera.php");
include("administrador/config/bd.php");

$termino_busqueda = '';

if (isset($_GET['buscar'])) {
    $termino_busqueda = '%' . $_GET['buscar'] . '%';
    $sentenciaSQL = $conexion->prepare("SELECT * FROM libros WHERE nombre LIKE :termino");
    $sentenciaSQL->bindParam(':termino', $termino_busqueda);
    $sentenciaSQL->execute();
    $listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
} else {
    $sentenciaSQL = $conexion->prepare("SELECT * FROM libros");
    $sentenciaSQL->execute();
    $listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
}
?>

<h1 class="display-3 text-center">Nuestra Biblioteca ♥</h1>

<form class="form-inline mb-4">
    <div class="input-group">
        <input type="text" class="form-control" name="buscar" placeholder="Buscar por nombre">
        <div class="input-group-append">
            <button class="btn btn-outline-primary" type="submit">Buscar</button>
        </div>
    </div>
</form>

<?php foreach($listaLibros as $libro) { ?>
    <div class="col-md-3">
        <div class="card">
            <img class="card-img-top" src="./img/<?php echo $libro['imagen'];?>" alt="">
            <div class="card-body">
                <h4 class="card-title"><?php echo $libro['nombre'];?></h4>
                <!-- Agregar un enlace a la página de compra -->
                <a class="btn btn-primary" href="template/comprar.php?id=<?php echo $libro['id'];?>" role="button">Comprar</a>
            </div>
        </div>
    </div>
<?php } ?>

<?php include("template/pie.php");?>

