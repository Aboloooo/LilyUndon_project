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

    <?php



    if (isset($_POST['submit'])) {
        //        echo "<script>alert('Error')</script>";

        $requiredFields = ["first_name", "last_name", "CNS_number", "username", "password", "password_confirmation", "email"];
        $errors = [];

        foreach ($requiredFields as $requiredField) {
            if (empty($_POST[$requiredField])) {
                $errors[] = ucfirst(str_replace("_", " ", $requiredField)) . " is required!";
            }
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<script>alert('$error')</script>";
            }
        } else {
            $firstN = $_POST['first_name'];
            $lastN = $_POST['last_name'];
            $CNS = $_POST['CNS_number'];
            $userN = $_POST['username'];
            $pass = $_POST['password'];
            $passConfir = $_POST['password_confirmation'];
            $email = $_POST['email'];
            $defaultLevel = "customer";
            $user_must_change_pass = 1;

            if ($pass == $passConfir) {
                $sqlInsertValues = $connection->prepare('INSERT INTO users (First_name, Last_name, social_security_number, Username, Password, Email, Level, user_must_change_password) VALUES (?,?,?,?,?,?,?,?)');
                $sqlInsertValues->bind_param('ssissssi', $firstN, $lastN, $CNS, $userN, $pass, $email, $defaultLevel, $user_must_change_pass);
                $sqlInsertValues->execute();
                echo "<script>alert('User created successfully!')</script>";
            } else {
                echo "<script>alert('Passwords are not match!')</script>";
                exit();
            }
        }
    }


    ?>


    <?= NavBar('add_user') ?>
    <div class="login-container">
        <h2>Welcome to Croix-Rouge 👋</h2>
        <p>Fill in the form below to register a new user in the system. Make sure all required fields are completed accurately.</p>
        <form action="" method="POST">
            <label for="First_name">First Name</label>
            <input type="text" id="username" name="first_name" placeholder="First name" required />

            <label for="Last_name">Last Name</label>
            <input type="text" id="username" name="last_name" placeholder="Last name" required />

            <label for="CNS">Social security number</label>
            <input type="text" id="username" name="CNS_number" placeholder="CNS number" pattern="\d{13}" maxlength="13" minlength="13" required />

            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="username" required />

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="••••••••" required />

            <label for="password">Password confirmation</label>
            <input type="password" id="password" name="password_confirmation" placeholder="••••••••" required />

            <label for="email">Email</label>
            <input type="email" id="username" name="email" placeholder="Email" required />


            <button type="submit" name="submit">Sign in</button>

        </form>
    </div>
</body>

</html>