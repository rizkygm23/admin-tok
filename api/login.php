<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['id_user'];
    $password = $_POST['password'];

    // Supabase URL dan Anon Key
    $supabaseUrl = 'https://avtaghpbnasdxmjnahxc.supabase.co';
    $supabaseAnonKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImF2dGFnaHBibmFzZHhtam5haHhjIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzA5NzU2ODcsImV4cCI6MjA0NjU1MTY4N30.msS8vrExcOUqW70DDMQ0KumXWMuBRpy7jlaU4wIEuLg';

    // API Supabase untuk mengambil data user
    $userUrl = $supabaseUrl . '/rest/v1/user?id_user=eq.' . $id_user;

    $response = file_get_contents($userUrl, false, stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => "apikey: $supabaseAnonKey\r\n",
        ],
    ]));

    $user = json_decode($response, true);

    if ($user && password_verify($password, $user[0]['password'])) {
        $_SESSION['isLoggedIn'] = true;
        echo json_encode(["message" => "Login sukses"]);
    } else {
        http_response_code(401);
        echo json_encode(["message" => "ID User atau Password salah"]);
    }
}
?>
