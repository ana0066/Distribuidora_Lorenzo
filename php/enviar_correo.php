<?php
function enviarCorreoVerificacion($correoDestino, $nombre, $token) {
    $apiKey = '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';
    $url = 'https://api.brevo.com/v3/smtp/email';

    $enlace = "http://distribuidoralorenzo.onlinewebshop.net/php/verificar.php?token=" . urlencode($token);

    $data = [
        'sender' => [
            'name' => 'Distribuidora Lorenzo',
            'email' => 'distribuidoralorenzo19@gmail.com'
        ],
        'to' => [[
            'email' => $correoDestino,
            'name' => $nombre
        ]],
        'subject' => 'Verifica tu cuenta en Distribuidora Lorenzo',
        'htmlContent' => "
            <html>
                <body>
                    <p>Hola <strong>$nombre</strong>,</p>
                    <p>Gracias por registrarte en <strong>Distribuidora Lorenzo</strong>.</p>
                    <p>Para activar tu cuenta, por favor haz clic en el siguiente enlace:</p>
                    <p><a href='$enlace' style='color: #4CAF50;'>Verificar mi cuenta</a></p>
                    <p>Si no te registraste, puedes ignorar este mensaje.</p>
                </body>
            </html>
        "
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'accept: application/json',
        'api-key: ' . $apiKey,
        'content-type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // ⚠️ GUARDAR LOG DE RESPUESTA
    file_put_contents("log_correo.txt", "HTTP: $httpCode\nRespuesta: $response\nError: $error");

    return $httpCode === 201;
}

?>
