<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'php/conexion_be.php';
require('pdf/fpdf.php');

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$pedido_id = isset($_GET['pedido_id']) ? $_GET['pedido_id'] : null;
$metodo_pago = isset($_POST['payment_method']) ? $_POST['payment_method'] : null;
$address = isset($_POST['address']) ? $_POST['address'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$delivery_time = isset($_POST['delivery_time']) ? $_POST['delivery_time'] : ''; 

if ($pedido_id && $metodo_pago) {
    switch ($metodo_pago) {
        case 'card': $id_metodo_pago = 1; break;
        case 'paypal': $id_metodo_pago = 2; break;
        case 'transfer': $id_metodo_pago = 3; break;
        case 'cash': $id_metodo_pago = 4; break;
        default: $id_metodo_pago = null; break;
    }

    $horario_entrega = $delivery_time == 'morning' ? '9:00 AM - 12:00 PM' : '12:00 PM - 4:00 PM';

    if ($id_metodo_pago) {
        $fecha_hoy = date('Y-m-d');
        $sql = "INSERT INTO OrdenPago (id_pedido, id_metodopago, horario, fecha) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iiss", $pedido_id, $id_metodo_pago, $horario_entrega, $fecha_hoy);

        if ($stmt->execute()) {
            // Obtener datos del cliente
            $sql_cliente = "SELECT p.id_cliente, u.nombres, u.correo, u.telefono, u.direccion 
                            FROM Pedido p
                            JOIN usuarios u ON p.id_cliente = u.id
                            WHERE p.id = ?";
            $stmt_cliente = $conexion->prepare($sql_cliente);
            $stmt_cliente->bind_param("i", $pedido_id);
            $stmt_cliente->execute();
            $stmt_cliente->bind_result($id_cliente, $nombre_cliente, $correo_cliente, $telefono_cliente, $direccion_cliente);
            $stmt_cliente->fetch();
            $stmt_cliente->close();

            // Obtener detalles del pedido
            $sql_detalle = "SELECT dp.cantidad, pr.nombre, pr.precio 
                            FROM DetallePedido dp
                            JOIN productos pr ON dp.id_producto = pr.id
                            WHERE dp.id_pedido = ?";
            $stmt_detalle = $conexion->prepare($sql_detalle);
            $stmt_detalle->bind_param("i", $pedido_id);
            $stmt_detalle->execute();
            $stmt_detalle->bind_result($cantidad, $nombre_producto, $precio_producto);

            $detalles = [];
            while ($stmt_detalle->fetch()) {
                $detalles[] = [
                    'cantidad' => $cantidad,
                    'nombre' => $nombre_producto,
                    'precio' => $precio_producto
                ];
            }
            $stmt_detalle->close();

            // Obtener el método de pago
            $sql_metodo = "SELECT m.Descripcion
                           FROM MetodoPago m
                           JOIN OrdenPago o ON m.ID = o.id_metodopago
                           WHERE o.id_pedido = ?";
            $stmt_metodo = $conexion->prepare($sql_metodo);
            $stmt_metodo->bind_param("i", $pedido_id);
            $stmt_metodo->execute();
            $stmt_metodo->bind_result($descripcion_metodo_pago);
            $stmt_metodo->fetch();
            $stmt_metodo->close();

            // Crear PDF
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetMargins(20, 20, 20);

            // Logo y título
            $pdf->Image('logoC.png', 50, 15, 40);
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'FACTURA ELECTRONICA', 0, 1, 'C');
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'CARNES B&R', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 6, 'Calle 79b#70A-31', 0, 1, 'C');
            $pdf->Cell(0, 6, 'Tel: +573182575587', 0, 1, 'C');

            // Información de la factura
            $pdf->SetXY(120, 15);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(70, 6, 'Factura No: A-00' . $pedido_id, 0, 1, 'R');
            $pdf->SetXY(120, 21);
            $pdf->Cell(70, 6, 'Fecha: ' . date('d/m/Y'), 0, 1, 'R');
            $pdf->SetXY(120, 27);

            // Datos del cliente
            $pdf->SetXY(20, 60);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(30, 6, 'Datos del Cliente:', 0, 1);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(100, 6, 'Nombre: ' . $nombre_cliente, 0, 1);
            $pdf->Cell(100, 6, 'Direccion: ' . $direccion_cliente, 0, 1);
            $pdf->Cell(100, 6, 'Telefono: ' . $telefono_cliente, 0, 1);
            $pdf->Cell(100, 6, 'Forma de Pago: ' . $descripcion_metodo_pago, 0, 1);
            $pdf->Cell(100, 6, 'Horario de entrega: ' . $horario_entrega, 0, 1);

            // Tabla de productos
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C', true);
            $pdf->Cell(80, 10, 'Descripcion', 1, 0, 'C', true);
            $pdf->Cell(30, 10, 'Precio Unit.', 1, 0, 'C', true);
            $pdf->Cell(30, 10, 'Importe', 1, 1, 'C', true);

            // Contenido de la tabla de productos
            $pdf->SetFont('Arial', '', 11);
            $total = 0;
            $cantidad_total = 0;
            $costo_envio = 0;

            foreach ($detalles as $detalle) {
                $cantidad = $detalle['cantidad'];
                $nombre = $detalle['nombre'];
                $precio = $detalle['precio'];
                $subtotal = $cantidad * $precio;
                $total += $subtotal;
                $cantidad_total += $cantidad;

                $pdf->Cell(30, 10, $cantidad, 1, 0, 'C');
                $pdf->Cell(80, 10, $nombre, 1, 0, 'L');
                $pdf->Cell(30, 10, '$' . number_format($precio, 0), 1, 0, 'R');
                $pdf->Cell(30, 10, '$' . number_format($subtotal, 0), 1, 1, 'R');
            }

            if ($cantidad_total < 7) {
                $costo_envio = 30000;
            }
            $total += $costo_envio;

            // Costos adicionales
            $pdf->Ln(5);
            $pdf->SetX(40);
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(100, 10, 'Costo de Envio: $' . number_format($costo_envio, 2), 0, 1, 'R');
            $pdf->Cell(100, 10, 'Total: $' . number_format($total, 2), 0, 1, 'R');

            // Generar y enviar PDF
$pdfOutput = 'factura.pdf';
$pdf->Output('F', $pdfOutput);

// Enviar correo con PDF adjunto
$to = $correo_cliente;
$subject = 'Formulario de Envío';
$message = 'Se ha recibido un formulario con los siguientes datos:';
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"boundary\"\r\n";
$headers .= "From: remitente@ejemplo.com\r\n";

// Cuerpo del mensaje
$body = "--boundary\r\n";
$body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
$body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$body .= $message . "\r\n";

// Adjuntar el archivo PDF
$pdfContent = file_get_contents($pdfOutput);
$body .= "--boundary\r\n";
$body .= "Content-Type: application/pdf; name=\"{$pdfOutput}\"\r\n";
$body .= "Content-Disposition: attachment; filename=\"{$pdfOutput}\"\r\n";
$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
$body .= chunk_split(base64_encode($pdfContent)) . "\r\n";
$body .= "--boundary--";

// Enviar correo
mail($to, $subject, $body, $headers);

// Eliminar el archivo PDF del servidor después de enviarlo
unlink($pdfOutput);

echo "<script>alert('Pago procesado con éxito. Revisa tu factura en tu correo.'); window.location.href='index2.php';</script>";
} else {
    echo "Error al procesar el pago: " . $stmt->error;
}

$stmt->close();
} else {
    echo "Método de pago no válido.";
}
} else {
    echo "Faltan datos de pago o pedido.";
}

$conexion->close();
?>