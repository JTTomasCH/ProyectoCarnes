<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('pdf/fpdf.php'); // Asegúrate de tener la librería FPDF

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];

    // Crear el PDF con FPDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(40, 10, 'Nombre: ' . $nombre);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Apellido: ' . $apellido);
    $pdfOutput = 'archivo.pdf';
    $pdf->Output('F', $pdfOutput); // Guardar el PDF en el servidor

    // Enviar el correo con el PDF adjunto
    $to = 'ac481861@gmail.com'; // Cambia por el correo del destinatario
    $subject = 'Formulario de Envío';
    $message = 'Se ha recibido un formulario con los siguientes datos:';

    // Cabeceras para el correo
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"boundary\"" . "\r\n";
    $headers .= "From: remitente@ejemplo.com" . "\r\n"; // Cambia por tu correo

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

    // Enviar el correo
    if (mail($to, $subject, $body, $headers)) {
        echo 'Correo enviado con éxito!';
    } else {
        echo 'Error al enviar el correo.';
    }

    // Eliminar el archivo PDF del servidor después de enviarlo
    unlink($pdfOutput);
}
?>
