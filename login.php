<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $id_user = $_POST['id_user'];
    $password = $_POST['password'];

    // Panggil API Supabase untuk mendapatkan data user
    $supabaseUrl = 'https://avtaghpbnasdxmjnahxc.supabase.co';
    $supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImF2dGFnaHBibmFzZHhtam5haHhjIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzA5NzU2ODcsImV4cCI6MjA0NjU1MTY4N30.msS8vrExcOUqW70DDMQ0KumXWMuBRpy7jlaU4wIEuLg';
    $userUrl = $supabaseUrl . '/rest/v1/user?id_user=eq.' . $id_user;

    $response = file_get_contents($userUrl, false, stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => "apikey: $supabaseKey\r\n",
        ],
    ]));

    $user = json_decode($response, true);

    if ($user && password_verify($password, $user[0]['password'])) {
        $_SESSION['isLoggedIn'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = 'ID User atau Password salah.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <form method="POST" class="bg-white p-6 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold text-center mb-4">Login Admin</h2>
        <?php if (isset($error)) echo "<div class='text-red-500 text-center mb-4'>$error</div>"; ?>
        <div class="mb-4">
            <label for="id_user" class="block text-sm font-medium mb-2">ID User</label>
            <input type="text" name="id_user" id="id_user" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium mb-2">Password</label>
            <input type="password" name="password" id="password" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <button type="submit" name="login" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Login</button>
    </form>
</body>
</html>
