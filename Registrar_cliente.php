<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BD_SOFTWARE_HOTEL";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtiene los datos del cliente desde la solicitud POST
$dni_cliente = $_POST['dni_cliente'] ?? null;
$nombres = $_POST['nombres'] ?? null;
$apellidos = $_POST['apellidos'] ?? null;
$telefono = $_POST['telefono'] ?? 'Sin Teléfono';
$observacion = $_POST['observacion'] ?? 'Ninguna';
$accion = $_POST['accion'] ?? ''; // Puede ser 'registrar' o 'actualizar'

// Verifica si los datos requeridos están presentes
if (empty($dni_cliente) || empty($nombres) || empty($apellidos)) {
    $_SESSION['mensaje'] = 'Datos incompletos'; // Mensaje de error
    header("Location: registroclientes.html"); // Redirige a registrocliente.html
    exit;
}

// Define la consulta según la acción
if ($accion === 'registrar') {
    // Consulta para insertar el cliente
    $sql = "INSERT INTO CLIENTE (DNI_CLIENTE, Nombres, Apellidos, Telefono, Observacion) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $dni_cliente, $nombres, $apellidos, $telefono, $observacion);
} elseif ($accion === 'actualizar') {
    // Procedimiento almacenado para actualizar el cliente
    $sql = "CALL ActualizarCliente(?, ?, ?)"; // Usar parámetros con "?"
    
    // Prepara la consulta
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        // Si hay error en la preparación de la consulta
        die('Error de preparación: ' . $conn->error);
    }
    
    // Enlaza los parámetros de forma segura
    $stmt->bind_param("sss", $_POST['dni_cliente'], $_POST['telefono'], $_POST['observacion']);
} else {
    $_SESSION['mensaje'] = 'Acción no válida';
    header("Location: registroclientes.html");
    exit;
}

// Verifica que la consulta se preparó correctamente
if (!$stmt) {
    $_SESSION['mensaje'] = 'Error de preparación: ' . $conn->error; // Mensaje de error
    header("Location: registroclientes.html"); // Redirige a registrocliente.html
    exit;
}

// Ejecuta la consulta
if ($stmt->execute()) {
    $_SESSION['mensaje'] = 'Cliente registrado/actualizado con éxito.'; // Mensaje de éxito
} else {
    $_SESSION['mensaje'] = 'Error al registrar/actualizar el cliente: ' . $stmt->error; // Mensaje de error
}

// Cierra la declaración y la conexión
$stmt->close();
$conn->close();

// Redirige a registroclientes.html después de procesar la solicitud
header("Location: registroclientes.html"); // Redirige a registroclientes.html
exit;
?>
