<!-- <?php
 session_start();
      include_once("../Library/MyLibrary.php");
      ?> -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style.css? <?= time(); ?>">
    <script src="../script.js"></script>
</head>
<body>
<?= NavBar('add_user') ?>
<div class="login-container">
    <h2>Welcome to Croix-Rouge 👋</h2>
<p>Fill in the form below to register a new user in the system. Make sure all required fields are completed accurately.</p>
    <form action="" method="POST">
    <label for="username">First Name</label>
    <input type="text" id="username" name="first_name" placeholder="First name" required />

    <label for="username">Last Name</label>
        <input type="text" id="username" name="last_name" placeholder="Last name" required />

        <label for="username">Social security number</label>
        <input type="text" id="username" name="CNS_number" placeholder="CNS number" required />

        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="username" required />

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="••••••••" required />

        <label for="password">Password confirmation</label>
        <input type="password" id="password" name="password_confirmation" placeholder="••••••••" required />


        <button type="submit">Sign in</button>
        
        </form>
  </div>
</body>
</html>