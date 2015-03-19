<?php
function httpReq($url, $postData, $method='POST') {
// Create the context for the request
$context = stream_context_create(array(
    'http' => array(
        // http://www.php.net/manual/en/context.http.php
        'method' => $method,
        'header' => "Content-Type: application/json\r\n",
        'content' => json_encode($postData)
    )
));

// Send the request
$response = file_get_contents($url, FALSE, $context);

// Check for errors
if($response === FALSE){
    die('Error');
}

// Decode the response
return json_decode($response, TRUE);
}
?>