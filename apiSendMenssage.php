<?php

$url = 'URL_API'; //Url da API
$token = "TOKEN_API"; // api token

// Defina o número de telefone e a mensagem
$data = [
    'number' => '557183196364', // Número de telefone no formato internacional
    'body' => 'Ei Zip-Zop, esta é uma mensagem enviada apenas como texto pelo PHP!'
];


// Inicializar cURL
$ch = curl_init($url);

// Configurar os headers e a requisição
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Adicionar verbosidade para debug
curl_setopt($ch, CURLOPT_VERBOSE, true); // Ativar verbosidade
$verboseLog = fopen('php://temp', 'w+'); // Criar log temporário
curl_setopt($ch, CURLOPT_STDERR, $verboseLog);

// Executar a requisição
$response = curl_exec($ch);

// Verificar se ocorreu algum erro
if (curl_errno($ch)) {
    echo 'Erro: ' . curl_error($ch);
} else {
    // Mostrar a resposta da API
    echo "Resposta da API:\n" . $response . "\n";
}

// Capturar e exibir o log de debug
rewind($verboseLog);
$verboseOutput = stream_get_contents($verboseLog);
fclose($verboseLog);

echo "\nLog de Debug:\n" . $verboseOutput;

// Fechar a conexão cURL
curl_close($ch);

