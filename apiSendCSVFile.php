<?php
$body = "Ei Zip-Zop, se liga só nesse arquivo enviado pelo PHP!";
$number = '557183196364';
$fileName = __DIR__ . "/files/kapakapa.csv"; 

$url = '';
$token = '';

if (!file_exists($fileName)) {
    exit(json_encode(["status" => "error", "message" => "Arquivo não encontrado: " . realpath($fileName)]));
}

$fileSize = filesize($fileName);
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $fileName);
finfo_close($finfo);

$cFile = new CURLFile($fileName, $mimeType, basename($fileName));

$data = [
    "media" => $cFile,
    "body" => $body,
    "number" => $number,
    "externalKey" => "",
    "fileName" => basename($fileName)
];

$cURL = curl_init($url);
curl_setopt_array($cURL, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["Authorization: Bearer " . $token],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_INFILESIZE => $fileSize,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_VERBOSE => true
]);

$verboseLog = fopen('php://temp', 'w+');
curl_setopt($cURL, CURLOPT_STDERR, $verboseLog);

$response = curl_exec($cURL);
$httpCode = curl_getinfo($cURL, CURLINFO_HTTP_CODE);

if (curl_errno($cURL)) {
    $error = curl_error($cURL);
    curl_close($cURL);
    exit(json_encode(["status" => "error", "message" => "cURL Error: " . $error]));
}

rewind($verboseLog);
$verboseOutput = stream_get_contents($verboseLog);
fclose($verboseLog);

curl_close($cURL);

echo "HTTP Code: $httpCode\nVerbose log:\n$verboseOutput\n\nResponse:\n$response";

