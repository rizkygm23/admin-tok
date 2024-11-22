<?php
session_start();
if (!isset($_SESSION['isLoggedIn'])) {
    header('Location: /api/login.php');
    exit;
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
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Tambah Produk</h1>
        <form method="POST" enctype="multipart/form-data" action="/api/upload.php" class="space-y-4">
            <input type="text" name="name" placeholder="Nama Produk" required class="w-full p-2 border rounded">
            <textarea name="description" placeholder="Deskripsi" required class="w-full p-2 border rounded"></textarea>
            <select name="category" required class="w-full p-2 border rounded">
                <option value="">Pilih Kategori</option>
                <option value="Tshirt">Tshirt</option>
                <option value="Kemeja">Kemeja</option>
                <option value="Hoodie">Hoodie</option>
                <option value="Pants">Pants</option>
            </select>
            <input type="number" name="price" placeholder="Harga" required class="w-full p-2 border rounded">
            <input type="file" name="image" required class="w-full p-2 border rounded">
            <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Tambah Produk</button>
        </form>
    </div>
</body>
</html>
