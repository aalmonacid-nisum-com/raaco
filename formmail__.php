<?php
// Configuración de destinatarios según las opciones del select
$recipient_emails = [
    "1" => "aalmonacid@gmail.com", // Correo para la opción 1
    "2" => "aalmonacid@gmail.com", // Correo para la opción 2
    "3" => "aalmonacid@gmail.com", // Correo para la opción 3
];

$subject = "Mensaje desde el Formulario"; // Asunto del correo

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener los datos del formulario
    $name = htmlspecialchars($_POST['nombre'] ?? 'Sin nombre');
    $email = htmlspecialchars($_POST['email'] ?? 'Sin correo');
    $telefono = htmlspecialchars($_POST['telefono'] ?? 'Sin correo');
    $message = htmlspecialchars($_POST['mensaje'] ?? 'Sin mensaje');
    $option = htmlspecialchars($_POST['option'] ?? '0');

    // Validar que la opción seleccionada tenga un destinatario
    if (!array_key_exists($option, $recipient_emails)) {
        die("Opción no válida.");
    }

    // Establecer el correo destinatario basado en la opción seleccionada
    $recipient_email = $recipient_emails[$option];

    // Validar el correo del remitente
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Correo no válido.");
    }

    // Plantilla HTML para el correo
    $email_template = "
    <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    color: #333;
                }
                .header {
                    background-color: #f4f4f4;
                    padding: 10px;
                    text-align: center;
                }
                .content {
                    padding: 20px;
                    border: 1px solid #ddd;
                }
                .footer {
                    margin-top: 20px;
                    font-size: 12px;
                    color: #888;
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <div class='header'>
                <h2>Nuevo Mensaje desde el Formulario</h2>
            </div>
            <div class='content'>
                <p><strong>Nombre:</strong> {$nombre}</p>
                <p><strong>Correo:</strong> {$email}</p>
                <p><strong>Nombre:</strong> {$telefono}</p>
                <p><strong>Mensaje:</strong><br>{$mensaje}</p>
                <p><strong>Opción Seleccionada:</strong> {$option}</p>
            </div>
            <div class='footer'>
                <p>Este mensaje fue enviado desde tu formulario en el sitio web.</p>
            </div>
        </body>
    </html>";

    // Encabezados del correo
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: {$email}" . "\r\n";

    // Enviar el correo
    if (mail($recipient_email, $subject, $email_template, $headers)) {
        echo "El mensaje se envió correctamente.";
    } else {
        echo "Hubo un error al enviar el mensaje.";
    }
} else {
    echo "Método no permitido.";
}
?>
