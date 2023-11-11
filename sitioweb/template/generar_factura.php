<?php
require_once 'libreria/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_GET['id'])) {
    
    // Recupera los datos de la compra, por ejemplo, desde una base de datos
    // Asegúrate de ajustar esto de acuerdo a tu estructura de datos
    $compras = array(
        array('EL Rinoceronte', 10, 100.00, 1000.00),
        
    );

    // Crear un objeto Dompdf
    $options = new Options();
    $options->set('isPhpEnabled', true); // Habilitar PHP en el contenido del PDF
    $dompdf = new Dompdf($options);

    // Crear el contenido HTML del PDF
    $html = '<html>';
    $html .= '<head><title>Factura de Compra</title></head>';
    $html .= '<body>';
    $html .= '<h1 style="text-align: center; font-size: 24px;">Factura de Compra</h1>';
    $html .= '<table border="1" cellpadding="5" style="width: 100%;">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th style="text-align: center; font-size: 18px;">Nombre del Libro</th>';
    $html .= '<th style="text-align: center; font-size: 18px;">Cantidad</th>';
    $html .= '<th style="text-align: center; font-size: 18px;">Precio Unitario</th>';
    $html .= '<th style="text-align: center; font-size: 18px;">Total</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    // Resto de tu código para llenar la tabla
    

    foreach ($compras as $compra) {
        $html .= '<tr>';
        $html .= '<td>' . $compra[0] . '</td>';
        $html .= '<td>' . $compra[1] . '</td>';
        $html .= '<td>$' . number_format($compra[2], 2) . '</td>';
        $html .= '<td>$' . number_format($compra[3], 2) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '</body>';
    $html .= '</html>';

    $dompdf->loadHtml($html);

    // Renderizar el PDF
    $dompdf->render();

    // Enviar el PDF generado al navegador
    $dompdf->stream('factura.pdf', array('Attachment' => 0));
}
?>
