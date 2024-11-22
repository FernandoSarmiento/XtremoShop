<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BD_SOFTWARE_HOTEL";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}

// Especificar que el contenido es JSON
header('Content-Type: application/json');

// Obtener los parámetros de filtrado
$estado = isset($_GET['estado']) ? $conn->real_escape_string($_GET['estado']) : null;
$id_reserva = isset($_GET['id']) ? intval($_GET['id']) : null;
$cliente_dni = isset($_GET['dni']) ? $conn->real_escape_string($_GET['dni']) : null;
$fecha_reserva = isset($_GET['fecha']) ? $conn->real_escape_string($_GET['fecha']) : null;

// Inicializar la consulta SQL
$sql = "SELECT * FROM RESERVA WHERE 1=1"; // Base para agregar condiciones
$params = [];
$types = ""; // Para los tipos de parámetros

if ($estado) {
    // Preparar la cláusula WHERE para el estado
    $sql .= " AND Estado = ?";
    $params[] = $estado;
    $types .= "s"; // 's' para string
}

if ($id_reserva) {
    // Preparar la cláusula WHERE para el ID de la reserva
    $sql .= " AND ID_RESERVA = ?";
    $params[] = $id_reserva;
    $types .= "i"; // 'i' para integer
}

if ($cliente_dni) {
    // Preparar la cláusula WHERE para el DNI del cliente
    $sql .= " AND CLIENTE_DNI = ?";
    $params[] = $cliente_dni;
    $types .= "s"; // 's' para string
}

if ($fecha_reserva) {
    // Preparar la cláusula WHERE para la fecha de reserva
    $sql .= " AND FechaReserva = ?";
    $params[] = $fecha_reserva;
    $types .= "s"; // 's' para string
}

// Preparar la consulta
$stmt = $conn->prepare($sql);

if ($types) {
    // Solo enlazar parámetros si hay alguno
    $stmt->bind_param($types, ...$params);
}

// Ejecutar consulta
$stmt->execute();

// Obtener resultados
$result = $stmt->get_result();

if (!$result) {
    die(json_encode(['error' => 'Error en la consulta: ' . $stmt->error]));
}

// Procesar resultados de la consulta
$reservas = [];
while ($row = $result->fetch_assoc()) {
    $reservas[] = $row;
}

// Devolver el resultado en formato JSON
echo json_encode($reservas);

// Cerrar conexión
$stmt->close();
$conn->close();
?>
