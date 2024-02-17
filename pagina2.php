<?php
// Activa la visualización de errores en el servidor
error_reporting(-1);
ini_set('display_errors', 'On');

// Función personalizada para manejar errores
function custom_error_handler($errno, $errstr, $errfile, $errline) {
    error_log("Error: [$errno] $errstr");
    // También podrías redirigir a una página de error o realizar otras acciones aquí
}

set_error_handler("custom_error_handler");

if(isset($_FILES['foto'])){
	$errors= array();
	$file_name = $_FILES['foto']['name'];
	$file_size = $_FILES['foto']['size'];
	$file_tmp = $_FILES['foto']['tmp_name'];
	$file_type = $_FILES['foto']['type'];
	$file_ext=strtolower(end(explode('.',$_FILES['foto']['name'])));
   
	$url = dirname(__FILE__)."/uploads/".$file_name;
   
	$extensions= array("jpeg","jpg","png","pdf","zip", "rar");
   
	if(in_array($file_ext,$expensions)=== false){
		$errors[]="Estensión no permitida.";
	}
   
	if($file_size > 10485760) {
		$errors[]='File size must be excately 10 MB';
	}

	if (empty($errors)==true) {
		if(move_uploaded_file($file_tmp,$url)){
			echo "subida con exito";
			chmod($url, 0777);
		}else{
			echo "problema en la subida";
		}
	}
}

// Verificar si se han enviado los datos del formulario
if(isset($_POST['nombre']) && isset($_POST['email']) && isset($_POST['asunto']) && isset($_POST['mensaje']) && isset($_POST['term'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $asunto = $_POST['asunto'];
    $mensaje = $_POST['mensaje'];

    // Validar el correo electrónico del remitente
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Construir el mensaje
        $subject = "Mensaje de $nombre - $asunto";
        $msg = $mensaje;

        // Configurar los encabezados del correo electrónico
        $to = "ecordoba08@misena.edu.co";
        $headers = "From: $email\r\n"; 
        $headers .= "Reply-To: $email\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n"; 
        
        // Enviar el correo electrónico
        if(mail($to, $subject, $msg, $headers)){
            echo "Mail enviado exitosamente";
        } else {
            echo "Error al enviar el correo electrónico";
            var_dump($custom_error_handler);
        }
    } else {
        echo "Dirección de correo electrónico inválida";
    }
} else {
    echo "Faltan datos del formulario";
}