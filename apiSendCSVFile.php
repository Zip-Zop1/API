<?php
$body = "Ei Zip-Zop, se liga só nesse arquivo enviado pelo PHP!";
$number = '557183196364';
$fileName = __DIR__ .  "/files/kapakapa.csv"; // caminho  absoluto do arquivo

$url = 'URL_API'; //Url da API
$token = "TOKEN_API"; // api token

$fileSize = filesize($fileName);

if (!file_exists($fileName)) {
    $out['status'] = 'error';
    $out['message'] = 'File not found: ' . $fileName;
    exit(json_encode($out));
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$finfo = finfo_file($finfo, $fileName);

$cFile = new CURLFile($fileName, $finfo, basename($fileName));
$data = [
    'number' => $number,
    'body' => $body,
    "medias" => $cFile,
    "filename" => $cFile->postname
];

$cURL = curl_init($url);

curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
curl_setopt(
    $cURL,
    CURLOPT_HTTPHEADER,
    [
        "Authorization: Bearer " . $token,
        "Content-Type: multipart/form-data",
    ]
);
curl_setopt($cURL, CURLOPT_POST, true);
curl_setopt($cURL, CURLOPT_POSTFIELDS, $data);
curl_setopt($cURL, CURLOPT_INFILESIZE, $fileSize);
curl_setopt($cURL, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);

// Adicionando opções de debug
curl_setopt($cURL, CURLOPT_VERBOSE, true); // Ativar verbosidade
$verboseLog = fopen('php://temp', 'w+');
curl_setopt($cURL, CURLOPT_STDERR, $verboseLog);

$response = curl_exec($cURL);

if (curl_errno($cURL)) {
    $error = curl_error($cURL);
    $out['status'] = 'error';
    $out['message'] = 'cURL Error: ' . $error;
    curl_close($cURL);
    exit(json_encode($out));
}

// Capturando detalhes da verbosidade
rewind($verboseLog);
$verboseOutput = stream_get_contents($verboseLog);
fclose($verboseLog);

// Debug e resposta
curl_close($cURL);

echo "Verbose log:\n";
echo $verboseOutput;

echo "\n\nResponse:\n";
die($response);
