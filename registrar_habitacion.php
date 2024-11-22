<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BD_SOFTWARE_HOTEL";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$accion = $_POST['accion'] ?? '';

// Validación y manejo de acciones
if ($accion === 'registrar') {
    // Registrar habitación
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $tipo = $conn->real_escape_string($_POST['tipo']);
    $capacidad = (int)$_POST['capacidad'];
    $precio = (float)$_POST['precio'];

    // Llamar al procedimiento almacenado para registrar la habitación
    $stmt = $conn->prepare("CALL RegistrarHabitacion(?, ?, ?, ?)");
    $stmt->bind_param("ssds", $nombre, $tipo, $capacidad, $precio);

    if ($stmt->execute()) {
        // Redirigir después de registrar
        header("Location: registrarhabitacion.html?mensaje=registrado");
        exit();
    } else {
        echo "Error al registrar: " . $stmt->error;
    }

} elseif ($accion === 'actualizar') {
    // Actualizar habitación
    $id_habitacion = (int)$_POST['id_habitacion'];
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $tipo = $conn->real_escape_string($_POST['tipo']);
    $capacidad = (int)$_POST['capacidad'];
    $precio = (float)$_POST['precio'];
    $estado = $conn->real_escape_string($_POST['estado']);

    // Llamar al procedimiento almacenado para actualizar la habitación
    $stmt = $conn->prepare("CALL ActualizarHabitacion(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssidsi", $id_habitacion, $nombre, $tipo, $capacidad, $precio, $estado);

    if ($stmt->execute()) {
        // Redirigir después de actualizar
        header("Location: registrarhabitacion.html?mensaje=actualizado");
        exit();
    } else {
        echo "Error al actualizar: " . $stmt->error;
    }
} else {
    echo "Acción no especificada o inválida.";
}

$conn->close();
?>
