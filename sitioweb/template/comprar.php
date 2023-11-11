<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Libro</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<?php 
    include("../template/cabecera.php");
    include("../administrador/config/bd.php");

    // Inicializa un array para registrar las compras
    $compras = array();

    // Verificar si se ha proporcionado un ID de libro
    if (isset($_GET['id'])) {
        $libro_id = $_GET['id'];

        // Aquí debes consultar la información del libro según el ID
        $sentenciaSQL = $conexion->prepare("SELECT * FROM libros WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $libro_id);
        $sentenciaSQL->execute();
        $libro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);

        if ($libro) {
            // Mostrar la imagen del libro con un tamaño personalizado
            echo "<img src='../img/" . $libro['imagen'] . "' alt='" . $libro['nombre'] . "' style='width: 300px; height: 400px;'>";

            if (isset($_POST['comprar'])) {
                // Registra la compra en el array de compras
                $cantidad = $_POST['cantidad'];
                $precio = $libro['precio'];
                $total = $cantidad * $precio; // Calcular el total
                $compras[] = array(
                    'cantidad' => $cantidad,
                    'nombre' => $libro['nombre'],
                    'precio' => $precio,
                    'total' => $total
                );

                // Mostrar un mensaje de confirmación
                echo "<p <h1 class='display-3 text-center'>Agregado al carrito </h1></p>";
                echo "<p class='text-center'>Has comprado " . $cantidad . " copias del libro: " . $libro['nombre'] . " por un total de $" . $total . "</p>";

                // Agregar el botón de regreso
                echo "<div class ='text-center'>";
                echo "<button class='btn btn-secondary' onclick='window.history.back()'>Regresar</button>";
                echo "</div>";
                
                // Agregar botón para comprar más copias
                echo "<div class ='text-center'>";
                echo "<button class='btn btn-primary' onclick='window.history.back()'>Comprar Más</button>";
                echo "</div>";

               
            } else {
                
                // Mostrar el formulario de compra
                echo "<form method='post'>";
                echo "<div class='form-group'>";
                echo "<label for='cantidad'>Cantidad:</label>";
                echo "<input type='number' class='form-control' id='cantidad' name='cantidad' required>";
                echo "</div>";
                echo "<button type='submit' name='comprar' class='btn btn-primary'>Comprar</button>";
                echo "<button type='button' class='btn btn-danger' onclick='cancelarCompra()'>Cancelar</button>";
                echo "</form>";
            }
        } else {
            // El libro no se encontró
            echo "<h1 class='display-3 text-center'>Libro no encontrado</h1>";
        }
    } else {
        // No se proporcionó un ID de libro
        echo "<h1 class='display-3 text-center'>ID de libro no proporcionado</h1>";
    }
    
?>


<button type="button" class="btn btn-success" style="width: 150px; height: 40px; margin-right: 200px; margin-top: 85px;" onclick="generarFactura()">Finalizar Compra</button>


<!-- Agregar una tabla para mostrar las compras -->
<table class="table">
    <thead>
        <tr>
            <th>Nombre del Libro</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($compras as $compra) { ?>
            <tr>
                <td><?php echo $compra['nombre']; ?></td>
                <td><?php echo $compra['cantidad']; ?></td>
                <td>$<?php echo $compra['precio']; ?></td>
                <td>$<?php echo $compra['total']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>
    function cancelarCompra() {
        alert('Compra cancelada');
        // Puedes redirigir a una página diferente o realizar alguna otra acción según tus necesidades.
    }
    
    function comprarMas() {
        // Redirigir al mismo detalle del libro para comprar más copias
        window.location.href = 'detalle_libro.php?id=<?php echo $libro_id; ?>';
       
    }
    function generarFactura() {
        // Redirigir a la página que genera el PDF
        window.location.href = 'generar_factura.php?id=<?php echo $libro_id; ?>';
    }
</script>

<?php include("../template/pie.php");?>
</body>
</html>
