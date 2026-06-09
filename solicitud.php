<?php
// Configuración
//$destinatario = "teochirino@gmail.com";
$destinatario = "info@extintoresprotec.com";
$asunto = "Nueva solicitud de contacto - PRO-TEC Extintores";

// Validar que el formulario fue enviado por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtener y sanitizar los datos del formulario
    $nombre = isset($_POST['nombre']) ? strip_tags(trim($_POST['nombre'])) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $telefono = isset($_POST['telefono']) ? strip_tags(trim($_POST['telefono'])) : '';
    $asunto_form = isset($_POST['asunto']) ? strip_tags(trim($_POST['asunto'])) : '';
    $mensaje = isset($_POST['mensaje']) ? strip_tags(trim($_POST['mensaje'])) : '';
    
    // Validar que todos los campos estén completos
    if (empty($nombre) || empty($email) || empty($telefono) || empty($asunto_form) || empty($mensaje)) {
        header("Location: index.html#contacto?error=campos_vacios");
        exit;
    }
    
    // Validar formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.html#contacto?error=email_invalido");
        exit;
    }
    
    // Construir el cuerpo del mensaje
    $cuerpo = "Ha recibido una nueva solicitud de contacto desde el sitio web de PRO-TEC Extintores.\n\n";
    $cuerpo .= "DETALLES DE LA SOLICITUD:\n";
    $cuerpo .= "========================\n\n";
    $cuerpo .= "Nombre: " . $nombre . "\n";
    $cuerpo .= "Email: " . $email . "\n";
    $cuerpo .= "Teléfono: " . $telefono . "\n";
    $cuerpo .= "Asunto: " . $asunto_form . "\n\n";
    $cuerpo .= "MENSAJE:\n";
    $cuerpo .= "--------\n";
    $cuerpo .= $mensaje . "\n\n";
    $cuerpo .= "========================\n";
    $cuerpo .= "Fecha y hora: " . date('d/m/Y H:i:s') . "\n";
    $cuerpo .= "IP del solicitante: " . $_SERVER['REMOTE_ADDR'] . "\n";
    
    // Configurar headers del correo
    $headers = "From: noreply@extintoresprotec.com\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // Intentar enviar el correo
    if (mail($destinatario, $asunto, $cuerpo, $headers)) {
        // Éxito - redirigir con mensaje de éxito
        header("Location: index.html#contacto?success=1");
        exit;
    } else {
        // Error al enviar - redirigir con mensaje de error
        header("Location: index.html#contacto?error=envio_fallido");
        exit;
    }
    
} else {
    // Si no es POST, redirigir a index.html
    header("Location: index.html#contacto");
    exit;
}
?>
