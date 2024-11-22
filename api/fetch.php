<?php
// Supabase URL dan Anon Key
$supabaseUrl = 'https://avtaghpbnasdxmjnahxc.supabase.co';
$supabaseAnonKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImF2dGFnaHBibmFzZHhtam5haHhjIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzA5NzU2ODcsImV4cCI6MjA0NjU1MTY4N30.msS8vrExcOUqW70DDMQ0KumXWMuBRpy7jlaU4wIEuLg';

// Endpoint Supabase untuk mengambil data produk
$productUrl = $supabaseUrl . '/rest/v1/products';

$response = file_get_contents($productUrl, false, stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "apikey: $supabaseAnonKey\r\n",
    ],
]));

// Decode response menjadi array
$products = json_decode($response, true);

if ($products) {
    // Kirim data sebagai JSON
    header('Content-Type: application/json');
    echo json_encode($products);
} else {
    // Jika gagal mengambil data
    http_response_code(500);
    echo json_encode(["message" => "Gagal mengambil data produk"]);
}
?>
