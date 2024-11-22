<?php
// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BD_SOFTWARE_HOTEL";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Parámetros de entrada
$accion = $_POST['accion'] ?? null;

if (!$accion) {
    echo json_encode(['success' => false, 'error' => 'Faltan datos necesarios.']);
    $conn->close();
    exit;
}

// Función para manejar el resultado de una consulta
function manejarResultado($stmt, $conn, $exitoMsg) {
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => $exitoMsg]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
}

// Manejar la acción de reserva
if ($accion === 'reservar') {
    $fechaReserva = $_POST['FechaReserva'] ?? null;
    $modoReserva = $_POST['ModoReserva'] ?? null;
    $metodoPago = $_POST['MetodoPago'] ?? null;
    $clienteDNI = $_POST['CLIENTE_DNI'] ?? null;
    $habitacionID = $_POST['HABITACION_ID'] ?? null;
    $usuarioID = $_POST['USUARIO_ID'] ?? null;

    if (!$fechaReserva || !$modoReserva || !$metodoPago || !$clienteDNI || !$habitacionID || !$usuarioID) {
        echo json_encode(['success' => false, 'error' => 'Faltan datos para registrar la reserva.']);
        $conn->close();
        exit;
    }

    $sql = "CALL RegistrarReserva(?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => $conn->error]);
        $conn->close();
        exit;
    }
    $stmt->bind_param("ssssss", $fechaReserva, $modoReserva, $metodoPago, $clienteDNI, $habitacionID, $usuarioID);

    manejarResultado($stmt, $conn, 'Reserva registrada correctamente.');
}

// Manejar la acción de entrada
if ($accion === 'entrada') {
    $idReserva = $_POST['id_reserva'] ?? null;
    if (!$idReserva) {
        echo json_encode(['success' => false, 'error' => 'ID de reserva es necesario.']);
        $conn->close();
        exit;
    }

    $sql = "CALL RegistrarEntrada(?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => $conn->error]);
        $conn->close();
        exit;
    }
    $stmt->bind_param("i", $idReserva);

    manejarResultado($stmt, $conn, 'Entrada registrada correctamente.');
}

// Manejar la acción de salida
if ($accion === 'salida') {
    $idReserva = $_POST['id_reserva'] ?? null;
    if (!$idReserva) {
        echo json_encode(['success' => false, 'error' => 'ID de reserva es necesario.']);
        $conn->close();
        exit;
    }

    $sql = "CALL RegistrarSalida(?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => $conn->error]);
        $conn->close();
        exit;
    }
    $stmt->bind_param("i", $idReserva);

    manejarResultado($stmt, $conn, 'Salida registrada correctamente.');
}

// Manejar la acción de cancelar reserva
if ($accion === 'cancelar') {
    $idReserva = $_POST['id_reserva'] ?? null;
    if (!$idReserva) {
        echo json_encode(['success' => false, 'error' => 'ID de reserva es necesario.']);
        $conn->close();
        exit;
    }

    $sql = "CALL CancelarReserva(?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => $conn->error]);
        $conn->close();
        exit;
    }
    $stmt->bind_param("i", $idReserva);

    manejarResultado($stmt, $conn, 'Reserva cancelada correctamente.');
}

// Redirigir al HTML de reservas después de completar la acción
header("Location: reservas.html");
$conn->close();
exit;
?>
