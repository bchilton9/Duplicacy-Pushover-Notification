<?php
// === CONFIG ===
$userKey = 'YOUR_PUSHOVER_USER_KEY';       // Replace with your key
$appToken = 'YOUR_PUSHOVER_APP_TOKEN';     // Replace with your token

// === ERROR REPORTING ===
ini_set('display_errors', 1);
error_reporting(E_ALL);

// === FILE LOGGING ===
$logFile = __DIR__ . '/duplicacy_last.json';
$debugFile = __DIR__ . '/dup-debug.txt';

// === READ JSON ===
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// === LOG TO FILE ===
file_put_contents($logFile, $input);
file_put_contents($debugFile, "RAW INPUT:\n$input\n\nPARSED:\n" . print_r($data, true));

// === EXTRACT INFO ===
$jobName   = $data['directory'] ?? 'Unknown Directory';
$hostname  = $data['computer'] ?? 'Unknown Host';
$result    = $data['result'] ?? 'Unknown Result';

$startTime = isset($data['start_time']) ? (int)$data['start_time'] : 0;
$endTime   = isset($data['end_time']) ? (int)$data['end_time'] : 0;
$duration  = ($startTime && $endTime) ? gmdate("H:i:s", $endTime - $startTime) : 'Unknown';

$uploadedBytes = ($data['uploaded_chunk_size'] ?? 0) + ($data['uploaded_file_chunk_size'] ?? 0) + ($data['uploaded_metadata_chunk_size'] ?? 0);
$sizeMB = number_format($uploadedBytes / 1024 / 1024, 2);

// === BUILD MESSAGE ===
$message = "🛡️ *Duplicacy Backup Report*\n"
         . "📂 Folder: $jobName\n"
         . "🖥️ Host: $hostname\n"
         . "📅 Result: $result\n"
         . "⏱ Duration: $duration\n"
         . "💾 Uploaded: {$sizeMB} MB\n\n";

// === SEND TO PUSHOVER ===
curl_setopt_array($ch = curl_init(), [
    CURLOPT_URL => 'https://api.pushover.net/1/messages.json',
    CURLOPT_POSTFIELDS => [
        'token' => $appToken,
        'user' => $userKey,
        'title' => 'Duplicacy Backup Report',
        'message' => $message,
    ],
    CURLOPT_RETURNTRANSFER => true
]);
curl_exec($ch);
curl_close($ch);

// === RESPONSE TO DUPLICACY ===
http_response_code(200);
echo "Report sent to Pushover";
