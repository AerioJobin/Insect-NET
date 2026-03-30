<?php
include('config.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if (isset($users[$user]) && $users[$user]['password'] === $pass) {
        $_SESSION['username'] = $user;
        $_SESSION['role'] = $users[$user]['role'];
        header("Location: index.php");
    } else {
        $error = "Invalid credentials!";
    }
}
?>
<form method="POST" style="text-align:center; margin-top:100px;">
    <h2>Insect-NET Login</h2>
    <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
</form>

