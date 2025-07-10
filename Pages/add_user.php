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
    <!-- bank of icons -->
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
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
            $Level_ID = $_SESSION['Admin'] ? $_POST['AccessLevel'] : '3';
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
                    $sqlInsertValues->bind_param('ssisssisi', $firstN, $lastN, $CNS, $userN, $hashedPass, $email, $Level_ID, $defaultStatus, $user_must_change_pass);
                    $sqlInsertValues->execute();
                    if ($_SESSION["Admin"] = true) {
                        echo "<script>alert('" . $t['user_created_successfully_admin'] . "')</script>";
                    } else {
                        echo "<script>alert('" . $t['user_created_successfully'] . "')</script>";
                    }
                }
            } else {
                echo "<script>alert('" . $t['passwords_not_match'] . "')</script>";
            }
        }
    }


    ?>


    <?= NavBar('add_user') ?>
    <div class="login-container add_user">
        <h2><?= $t['welcome_message'] ?> 👋</h2>
        <p><?= $t['form_instruction'] ?></p>

        <form method="POST" class="form-container">

            <div class="step">
                <input type="text" id="first_name" name="first_name" placeholder="   <?= $t['first_name'] ?>" required />
                <input type="text" id="last_name" name="last_name" placeholder="   <?= $t['last_name'] ?>" required />
            </div>

            <div class="step">
                <input type="text" id="CNS_number" name="CNS_number" placeholder="   <?= $t['social_security_number'] ?>" pattern="\d{13}" maxlength="13" minlength="13" required />
            </div>

            <div class="step">
                <input type="text" id="username" name="username" placeholder="   <?= $t['username'] ?>" required />
                <input type="password" id="password" name="password" placeholder="   ••••••••" pattern="(?=.*\d).{7,}" title="at least 7 characters long,one number and one special character " required />
            </div>

            <div class="step">
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="   <?= $t['password_confirmation'] ?>" required />
            </div>

            <!-- Level selection bar(visible only to admin) -->
            <?php
            if ($_SESSION['Admin']) {
            ?>
                <div class="step">
                    <div class='levelSelectionInputsContainer'>
                        <input type="radio" name='AccessLevel' id='Residence' value="3" checked='checked'>
                        <label for="Residence"><?= $t['resident'] ?></label><br>
                        <input type="radio" name='AccessLevel' id='SecurityGuard' value="2">
                        <label for="SecurityGuard"><?= $t['security_guard'] ?></label><br>
                    </div>
                </div>
            <?php
            }
            ?>

            <div class="step">
                <input type="email" id="username" name="email" placeholder="   <?= $t['email'] ?>" required />
                <input type="submit" name="submit" id="testBtn" value="<?= $t['sign_in'] ?>">
            </div>
            <div class="navigationBtns-container">
                <button id="previous"><img src="../img/previous.png" width="50px" alt=""></button>
                <button id="next"><img src="../img/next.png" width="50px" alt=""></button>
            </div>

        </form>


    </div>

    </form>
    </div>





    <script src="../script.js"></script>

</body>

</html>