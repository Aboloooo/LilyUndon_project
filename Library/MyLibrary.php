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
    $_SESSION["level"] = 3;
}
if (!isset($_SESSION["Admin"])) {
    $_SESSION["Admin"] = false;
}
if (!isset($_SESSION["GuardReserveAccess"])) {
    $_SESSION["GuardReserveAccess"] = false;
}
if (!isset($_SESSION["userMustChangeThePass"])) {
    $_SESSION["userMustChangeThePass"] = false;
}
if (!isset($_SESSION["language"])) {
    $_SESSION["language"] = "en";
}

if (!isset($_SESSION["currentSite"])) {
    $_SESSION["currentSite"] = 1;
}

if (isset($_POST["site"])) {
    $_SESSION["currentSite"] = $_POST["site"];
}


$t = [];
$supportedLanguages = ['en', 'fr', 'de', 'ti', 'es', 'ar', 'fa'];

if (isset($_POST["selectedLang"])) {
    $lang = $_POST['selectedLang'];
    if (in_array($lang, $supportedLanguages)) {
        $_SESSION['language'] = $lang;
    }
}

$langColumn = in_array($_SESSION['language'], $supportedLanguages) ? $_SESSION['language'] : 'en';


$sqlTranslation = $connection->prepare("select translationID, $langColumn as translation from translation");
$sqlTranslation->execute();
$result = $sqlTranslation->get_result();
while ($row = $result->fetch_assoc()) {
    $t[$row['translationID']] = $row['translation'];
}

/* in case there is a change in user's status admin will logout the user   */
if ($_SESSION['username'] && $_SESSION['username'] != 'Unknown') {
    $userIDstat = $connection->prepare('select status from users where UserID = (select UserID from users where Username = ?)');
    $userIDstat->bind_param('s', $_SESSION['username']);
    $userIDstat->execute();
    $userIDstatResult = $userIDstat->get_result();
    $userStatus = $userIDstatResult->fetch_assoc();

    if (!$userStatus || strtolower($userStatus['status']) != 'active') {
        session_destroy();
        echo "<script>alert('" . $t['session_terminated_status_changed'] . "')</script>";
        header("Location: index.php");
        exit();
    }
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
    global $supportedLanguages;
    $currentLanguage = $_SESSION['language'] ?? 'en';  // Default to English
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
                                        if ($_SESSION['username'] == 'Unknown' || $_SESSION['Admin']) {
                                            if ($currentPageLoc == "add_user") {
                                                print("class='active'");
                                            } ?>><?= $t['register'] ?></a></li>
        <?php
                                        }
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
                                    $usernameORlink = $t['login'];
                                    if ($_SESSION['userLogin']) {
                                        $usernameORlink = $_SESSION['username'];
                                    }
                                    ?>><img src="../img/user.png" width="25px" height="25px"><?= $usernameORlink ?></a></li>
        <li>
            <form method="POST">
                <select name="selectedLang" onchange="this.form.submit()">

                    <?php
                    foreach ($supportedLanguages as $supportedLanguage) {
                    ?>
                        <option value="<?= $supportedLanguage ?>" <?= $currentLanguage == $supportedLanguage ? 'selected' : '' ?>> <?= strtoupper($supportedLanguage) ?> </option> <?php
                                                                                                                                                                                }
                                                                                                                                                                                    ?>
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