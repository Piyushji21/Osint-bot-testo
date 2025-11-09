<?php
// Telegram Bot for Railway
define('BOT_TOKEN', '7951999209:AAHYUAl9Y41RNyvKz1sFKHcSie0IpU4UqRU');
define('WEBSITE_URL', 'https://neggaverval.42web.io');

$input = file_get_contents("php://input");
$update = json_decode($input, true);

if(isset($update['message'])) {
    $chat_id = $update['message']['chat']['id'];
    $text = $update['message']['text'];
    
    if($text == '/start') {
        $response = "🤖 *Welcome to Info Finder Bot* \n\n";
        $response .= "Available Services:\n";
        $response .= "• `/mobile 9999999999` - Mobile info\n";
        $response .= "• `/vehicle MH01AB1234` - Vehicle info\n"; 
        $response .= "• `/aadhar 123456789012` - Aadhar info\n\n";
        $response .= "🔒 *Powered by Piyush XD*";
    }
    elseif(strpos($text, '/mobile') === 0) {
        $number = trim(str_replace('/mobile', '', $text));
        if(preg_match('/^\d{10}$/', $number)) {
            // Call your mobile API
            $api_data = json_decode(file_get_contents(WEBSITE_URL."/api.php?number=".$number), true);
            if(isset($api_data['error'])) {
                $response = "❌ " . $api_data['error'];
            } else {
                $response = "📱 *Mobile Info*\n\n";
                $response .= "👤 Name: " . ($api_data['name'] ?? 'N/A') . "\n";
                $response .= "📞 Mobile: " . ($api_data['mobile'] ?? 'N/A') . "\n";
                $response .= "🌐 Circle: " . ($api_data['circle'] ?? 'N/A') . "\n";
            }
        } else {
            $response = "❌ Invalid mobile number format";
        }
    }
    elseif(strpos($text, '/vehicle') === 0) {
        $response = "🚗 Vehicle service - Coming soon!";
    }
    elseif(strpos($text, '/aadhar') === 0) {
        $response = "🆔 Aadhar service - Coming soon!";
    }
    else {
        $response = "❓ Use /start to see commands";
    }
    
    // Send response
    $url = "https://api.telegram.org/bot".BOT_TOKEN."/sendMessage";
    $data = [
        'chat_id' => $chat_id,
        'text' => $response,
        'parse_mode' => 'Markdown'
    ];
    
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => json_encode($data)
        ]
    ];
    
    $context = stream_context_create($options);
    file_get_contents($url, false, $context);
}

echo "OK";
?>
