<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image = $_FILES['image'];

    // Cloudinary Setup
    $cloudinaryUrl = 'https://api.cloudinary.com/v1_1/dtjnzbvlg/image/upload';
    $cloudinaryPreset = 'img_data';

    // Upload Gambar ke Cloudinary
    $imageData = file_get_contents($image['tmp_name']);
    $base64Image = base64_encode($imageData);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $cloudinaryUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'file' => "data:image/jpeg;base64,$base64Image",
        'upload_preset' => $cloudinaryPreset,
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $cloudinaryResponse = json_decode(curl_exec($ch), true);
    curl_close($ch);

    $imageUrl = $cloudinaryResponse['secure_url'];

    // Supabase Setup
    $supabaseUrl = 'https://avtaghpbnasdxmjnahxc.supabase.co';
    $supabaseAnonKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImF2dGFnaHBibmFzZHhtam5haHhjIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzA5NzU2ODcsImV4cCI6MjA0NjU1MTY4N30.msS8vrExcOUqW70DDMQ0KumXWMuBRpy7jlaU4wIEuLg';

    $productUrl = $supabaseUrl . '/rest/v1/products';

    $postData = json_encode([
        'name' => $name,
        'description' => $description,
        'category' => $category,
        'price' => $price,
        'image_url' => $imageUrl,
        'likes' => 0,
    ]);

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\napikey: $supabaseAnonKey\r\n",
            'content' => $postData,
        ],
    ]);

    $response = file_get_contents($productUrl, false, $context);

    if ($response) {
        echo json_encode(["message" => "Produk berhasil ditambahkan"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Gagal menambahkan produk"]);
    }
}
?>
