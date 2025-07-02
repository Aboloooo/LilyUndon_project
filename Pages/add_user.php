<?php
include_once("../Library/MyLibrary.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['register'] ?></title>
    <link rel="stylesheet" href="../style.css?<?php echo filemtime('../style.css'); ?>">    
    <script src="../script.js"></script>
</head>

<body>

    <?php
    if (isset($_POST['submit'])) {
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
            $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
            $passConfir = $_POST['password_confirmation'];
            $email = $_POST['email'];
            $defaultLevel_ID = 3;
            $defaultStatus = "pending";
            $user_must_change_pass = 1;

            if ($pass == $passConfir) {
                // check if inputs would be doublicated in database. CNS and Username must be unique
                $sqlCheckingDouclicedInput = $connection->prepare('select count(*) as count from users where social_security_number = ?  or Username =? ');
                $sqlCheckingDouclicedInput->bind_param('is', $CNS, $userN);
                $sqlCheckingDouclicedInput->execute();
                $result = $sqlCheckingDouclicedInput->get_result();
                $row = $result->fetch_assoc();
                $inputExist = ($row['count'] > 0);

                if ($inputExist) {
                    echo "<script>alert('" . addslashes($t['cns_username_error']) . "');</script>";
                } else {
                    $sqlInsertValues = $connection->prepare('INSERT INTO users (First_name, Last_name, social_security_number, Username, Password, Email, AccessLevelID,status, user_must_change_password) VALUES (?,?,?,?,?,?,?,?,?)');
                    $sqlInsertValues->bind_param('ssisssisi', $firstN, $lastN, $CNS, $userN, $hashedPass, $email, $defaultLevel_ID, $defaultStatus, $user_must_change_pass);
                    $sqlInsertValues->execute();
                    if($_SESSION["Admin"] = true){
                        echo "<script>alert('" . $t['user_created_successfully_admin'] . "')</script>";
                    }else{
                        echo "<script>alert('" . $t['user_created_successfully'] . "')</script>";
                    }
                }
            } else {
                echo "<script>alert('" . $t['passwords_not_match'] . "')</script>";
                exit();
            }
        }
    }


    ?>


    <?= NavBar('add_user') ?>
    <div class="login-container">
        <h2><?= $t['welcome_message'] ?> 👋</h2>
        <p><?= $t['form_instruction'] ?></p>
        <form action="" method="POST">
            <label for="First_name"><?= $t['first_name'] ?></label>
            <input type="text" id="username" name="first_name" placeholder="<?= $t['first_name'] ?>" required />

            <label for="Last_name"><?= $t['last_name'] ?></label>
            <input type="text" id="username" name="last_name" placeholder="<?= $t['last_name'] ?>" required />

            <label for="CNS"><?= $t['social_security_number'] ?></label>
            <input type="text" id="username" name="CNS_number" placeholder="<?= $t['social_security_number'] ?>" pattern="\d{13}" maxlength="13" minlength="13" required />

            <label for="username"><?= $t['username'] ?></label>
            <input type="text" id="username" name="username" placeholder="<?= $t['username'] ?>" required />

            <label for="password"><?= $t['password'] ?></label>
            <input type="password" id="password" name="password" placeholder="••••••••" pattern="(?=.*\d).{7,}" title="at least 7 characters long,one number and one special character " required />


            <label for="password_confirmation"><?= $t['password_confirmation'] ?></label>
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required />

            <!-- Level selection bar(visible only to admin) -->
             <?php
            if($_SESSION['Admin']){
                ?>
                <div class='levelSelectionInputsContainer'>
                    <input type="radio" name='size' id='Residence' checked='checked'>
                    <label for="Residence" >Residence</label><br>
                    <input type="radio" name='size' id='SecurityGuard' >
                    <label for="SecurityGuard">SecurityGuard</label><br>
                    <input type="radio" name='size' id='Admin' >
                    <label for="Admin">Admin</label>
                </div>
                <?php 
            }
             ?>

            <label for="email"><?= $t['email'] ?></label>
            <input type="email" id="username" name="email" placeholder="<?= $t['email'] ?>" required />


            <button type="submit" name="submit"><?= $t['sign_in'] ?></button>

        </form>
    </div>
</body>

</html>