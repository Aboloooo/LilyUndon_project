<?php
include_once("../Library/MyLibrary.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['register'] ?></title>
    <link rel="stylesheet" href="../New_edition.css?<?php echo filemtime('../style.css'); ?>">
    <!-- bank of icons -->
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
    <div class="login-container">
        <h2><?= $t['welcome_message'] ?> 👋</h2>
        <p><?= $t['form_instruction'] ?></p>

        <form action="" method="POST" class="form-container">
            <button id="previous"><img src="../img/previous.png" width="50px" alt=""></button>

            <div class="step">
                <input type="text" id="first_name" name="first_name" placeholder="<?= $t['first_name'] ?>" required />
                <input type="text" id="last_name" name="last_name" placeholder="<?= $t['last_name'] ?>" required />
            </div>

            <div class="step">
                <input type="text" id="CNS_number" name="CNS_number" placeholder="<?= $t['social_security_number'] ?>" pattern="\d{13}" maxlength="13" minlength="13" required />
            </div>

            <div class="step">
                <input type="text" id="username" name="username" placeholder="<?= $t['username'] ?>" required />
                <input type="password" id="password" name="password" placeholder="••••••••" pattern="(?=.*\d).{7,}" title="at least 7 characters long,one number and one special character " required />
            </div>

            <div class="step">
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required />
            </div>

            <!-- Level selection bar(visible only to admin) -->
            <div class="step">
                <div class='levelSelectionInputsContainer'>
                    <input type="radio" name='AccessLevel' id='Residence' value="3" checked='checked'>
                    <label for="Residence">Residence</label><br>
                    <input type="radio" name='AccessLevel' id='SecurityGuard' value="2">
                    <label for="SecurityGuard">Security Guard</label><br>
                </div>
            </div>

            <div class="step">
                <input type="email" id="username" name="email" placeholder="<?= $t['email'] ?>" required />
            </div>

            <button id="next"><img src="../img/next.png" width="50px" alt=""></button>

        </form>

    </div>





    <script src="../New_edition.js"></script>

</body>

</html>