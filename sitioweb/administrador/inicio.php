<?php include ('template/cabecera.php'); ?>

<style>
    body {
        background-color: #f5f5f5; /* Cambia este color de fondo según tus preferencias */
    }

    .jumbotron {
        background-color: #007BFF; /* Cambia este color de fondo según tus preferencias */
        color: #fff; /* Cambia el color del texto según tus preferencias */
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="jumbotron">
                <h1 class="display-3">BIENVENIDO </h1>
                <p class="lead">VAMOS A ADMINISTRAR NUESTROS LIBROS EN EL SITIO WEB</p>
                <hr class="my-4">
                <p>More info</p>
                <p class="lead">
                    <a class="btn btn-primary btn-lg" href="seccion/productos.php" role="button">Administrar libros</a>
                    <!-- Agregar enlace al archivo PDF -->
                    <a class="btn btn-secondary btn-lg" href="manual/manual programa.pdf" target="_blank" role="button">MANUAL DE USUARIO</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include ('template/pie.php'); ?>


     

    