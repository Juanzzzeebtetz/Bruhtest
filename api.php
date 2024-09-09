<?php

// Configuración de los encabezados para permitir solicitudes desde cualquier origen
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Verificación de si se ha proporcionado el parámetro 'bin_number' en la solicitud GET
if (!isset($_GET['bin_number'])) {
    echo json_encode(['error' => 'El parámetro bin_number es requerido.']);
    exit();
}

// Obtención del número BIN desde la solicitud GET
$bin_number = $_GET['bin_number'];

// Validación básica del número BIN (debería ser un número de 6 dígitos)
if (!preg_match('/^\d{6}$/', $bin_number)) {
    echo json_encode(['error' => 'El bin_number debe ser un número de 6 dígitos.']);
    exit();
}

// URL de la API externa para verificar el BIN
$url = "https://bins.antipublic.cc/bins/{$bin_number}";

// Inicialización de cURL para realizar la solicitud GET
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 1);

// Deshabilitar la verificación SSL
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Eliminar verificación SSL del peer
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Eliminar verificación del host

// Ejecución de la solicitud y obtención de la respuesta
$response = curl_exec($ch);

// Verificación de errores en cURL
if (curl_errno($ch)) {
    echo json_encode(['error' => 'Error en la solicitud a la API externa: ' . curl_error($ch)]);
    curl_close($ch);
    exit();
}

// Cierre de la sesión cURL
curl_close($ch);

// Decodificación de la respuesta JSON
$data = json_decode($response, true);

// Verificación de si la respuesta es válida y contiene la información esperada
if (isset($data['country_name']) && isset($data['country_flag']) && isset($data['bank']) && isset($data['level']) && isset($data['type']) && isset($data['brand'])) {
    // Extracción de la información relevante del resultado
    $country_name = $data['country_name'];
    $flag = $data['country_flag'];
    $bank = $data['bank'];
    $level = $data['level'];
    $type = $data['type'];
    $brand = $data['brand'];

    // Preparación de la respuesta JSON con la información relevante
    $response_array = [
        'country_name' => $country_name,
        'country_flag' => $flag,
        'bank' => $bank,
        'level' => $level,
        'type' => $type,
        'brand' => $brand
    ];

    // Envío de la respuesta en formato JSON
    echo json_encode($response_array);
} else {
    // Si la respuesta no contiene la información esperada, se envía un mensaje de error
    echo json_encode(['error' => 'No se pudo obtener la información del BIN proporcionado.']);
}

?>
