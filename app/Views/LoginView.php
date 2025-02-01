<!-- app/Views/LoginView.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (!empty($error)): ?>
      <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="/login" method="post">
        <label>Username: <input type="text" name="username" /></label><br>
        <label>Password: <input type="password" name="password" /></label><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
