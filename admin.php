<?php
session_start();
if (!isset($_SESSION['isLoggedIn'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image = $_FILES['image'];

    // Upload ke Cloudinary
    $cloudinaryUrl = 'https://api.cloudinary.com/v1_1/dtjnzbvlg/image/upload';
    $cloudinaryPreset = 'img_data';

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

    // Simpan data ke Supabase
    $supabaseUrl = 'https://avtaghpbnasdxmjnahxc.supabase.co';
    $supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImF2dGFnaHBibmFzZHhtam5haHhjIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzA5NzU2ODcsImV4cCI6MjA0NjU1MTY4N30.msS8vrExcOUqW70DDMQ0KumXWMuBRpy7jlaU4wIEuLg';
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
            'header' => "Content-Type: application/json\r\napikey: $supabaseKey\r\n",
            'content' => $postData,
        ],
    ]);

    $response = file_get_contents($productUrl, false, $context);

    if ($response) {
        $success = 'Produk berhasil ditambahkan.';
    } else {
        $error = 'Gagal menambahkan produk.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tambah Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Tambah Produk</h1>
        <?php if (isset($success)) echo "<div class='text-green-500 mb-4'>$success</div>"; ?>
        <?php if (isset($error)) echo "<div class='text-red-500 mb-4'>$error</div>"; ?>
        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium mb-2">Nama Produk</label>
                <input type="text" name="name" id="name" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="description" class="block text-sm font-medium mb-2">Deskripsi</label>
                <textarea name="description" id="description" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
            </div>
            <div>
                <label for="category" class="block text-sm font-medium mb-2">Kategori</label>
                <select name="category" id="category" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Pilih Kategori</option>
                    <option value="Tshirt">Tshirt</option>
                    <option value="Kemeja">Kemeja</option>
                    <option value="Hoodie">Hoodie</option>
                    <option value="Pants">Pants</option>
                </select>
            </div>
            <div>
                <label for="price" class="block text-sm font-medium mb-2">Harga</label>
                <input type="number" name="price" id="price" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="image" class="block text-sm font-medium mb-2">Gambar</label>
                <input type="file" name="image" id="image" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <button type="submit" name="add_product" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Tambah Produk</button>
        </form>
    </div>
</body>
</html>
