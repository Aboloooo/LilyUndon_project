<?php
session_start();

/* connection to database */
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'Don_Bosco';
$connection = mysqli_connect($host, $username, $password, $database);

if (!isset($_SESSION["userLogin"])) {
    $_SESSION["userLogin"] = false;
}
if (!isset($_SESSION["username"])) {
    $_SESSION["username"] = "Unknown";
}
if (!isset($_SESSION["level"])) {
    $_SESSION["level"] = "customer";
}
if (!isset($_SESSION["Admin"])) {
    $_SESSION["Admin"] = false;
}
if (!isset($_SESSION["userMustChangeThePass"])) {
    $_SESSION["userMustChangeThePass"] = false;
}
if (!isset($_SESSION["language"])) {
    $_SESSION["language"] = "en";
}
if (!isset($_SESSION["activatedUser"])) {
    $_SESSION["activatedUser"] = false;
}


if (isset($_POST["selectedLang"])) {
    $lang = $_POST['selectedLang'];
    if (in_array($lang, ['en', 'fr', 'de'])) {
        $_SESSION['language'] = $lang;
    }
}
$t = [];
$langColumn = '';
if ($_SESSION['language'] == 'en') {
    $langColumn = 'en';
} else if ($_SESSION['language'] == 'fr') {
    $langColumn = 'fr';
} else if ($_SESSION['language'] == 'de') {
    $langColumn = 'de';
} else {
    $langColumn = '';
}
$sqlTranslation = $connection->prepare("select translationID, $langColumn as translation from translation");
$sqlTranslation->execute();
$result = $sqlTranslation->get_result();
while ($row = $result->fetch_assoc()) {
    $t[$row['translationID']] = $row['translation'];
}


//if session created and user hasnt change his password lock the page
$arrFileName = explode("/", $_SERVER['PHP_SELF']);
$fileToCheck = trim($arrFileName[count($arrFileName) - 1]);

if ($fileToCheck != "logout_in.php") {
    if (isset($_SESSION["userMustChangeThePass"]) && $_SESSION["userMustChangeThePass"] == true) {
        header("Location: logout_in.php");
        exit();
    }
}

function NavBar($currentPageLoc)
{
    global $t;
?>
    <nav class="navbar">
        <div class="logo">
            <img src="../img/Logo.png" alt="Croix-Rouge" class="logo-icon" />
        </div>
        <ul class="nav-links">
            <li><a href="index.php" <?php
                                    if ($currentPageLoc == "index") {
                                        print("class='active'");
                                    } ?>><?= $t['home'] ?></a></li>
            <li><a href="reserve.php" <?php
                                        if ($currentPageLoc == "reserve") {
                                            print("class='active'");
                                        } ?>><?= $t['Reserve'] ?></a></li>
            <li><a href="my_reservations.php" <?php
                                                if ($currentPageLoc == "my_reservations") {
                                                    print("class='active'");
                                                } ?>><?= $t['my_reservations'] ?></a></li>
                                                <li><a href="add_user.php" <?php
                                            if ($currentPageLoc == "add_user") {
                                                print("class='active'");
                                            } ?>><?= $t['register'] ?></a></li>
            <?php
            /* if user is admin the following link must be display in navigation bar */
            if (isset($_SESSION['Admin']) && $_SESSION["Admin"]) {
            ?>
                
                <li><a href="users.php" <?php
                                        if ($currentPageLoc == "users") {
                                            print("class='active'");
                                        } ?>><?= $t['users'] ?></a></li>
            <?php
            }
            ?>

            <li><a href="logout_in.php" <?php
                                        if ($currentPageLoc == "logout_in") {
                                            print("class='active'");
                                        }
                                        $usernameORlink = 'Login';
                                        if ($_SESSION['userLogin']) {
                                            $usernameORlink = $_SESSION['username'];
                                        }
                                        ?>><img src="../img/user.png" width="25px" height="25px"><?= $usernameORlink ?></a></li>
            <li>
                <form method="POST">
                    <select name="selectedLang" onchange="this.form.submit()">
                        <option value="en" <?php if ($_SESSION['language'] == "en") echo 'selected' ?>>EN</option>
                        <option value="fr" <?php if ($_SESSION['language'] == "fr") echo 'selected' ?>>FR</option>
                        <option value="de" <?php if ($_SESSION['language'] == "de") echo 'selected' ?>>DE</option>
                    </select>
                </form>
            </li>
        </ul>
        <div class="burger">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
    </nav>
<?php
}


?>