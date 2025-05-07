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
    $_SESSION["username"];
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


//if session created and user hasnt change his password lock the page
$arrFileName = explode("/",$_SERVER['PHP_SELF']);
$fileToCheck = trim($arrFileName[count($arrFileName)-1]);

if ($fileToCheck!= "logout_in.php")
 {
    if (isset($_SESSION["userMustChangeThePass"]) && $_SESSION["userMustChangeThePass"] == true) {
    header("Location: logout_in.php");
    exit();
    }
 }

function NavBar($currentPageLoc)
{
?>
    <nav class="navbar">
        <div class="logo">
            <img src="../img/Logo.png" alt="Croix-Rouge" class="logo-icon" />
        </div>
        <ul class="nav-links">
            <li><a href="index.php" <?php
                                    if ($currentPageLoc == "index") {
                                        print("class='active'");
                                    } ?>>Home</a></li>
            <li><a href="reserve.php" <?php
                                        if ($currentPageLoc == "reserve") {
                                            print("class='active'");
                                        } ?>>Reserve</a></li>
            <li><a href="my_reservations.php" <?php
                                                if ($currentPageLoc == "my_reservations") {
                                                    print("class='active'");
                                                } ?>>My Reservations</a></li>
            <?php
            /* if user is admin the following link must be display in navigation bar */
            if (isset($_SESSION['Admin']) && $_SESSION["Admin"]) {
            ?>
                <li><a href="add_user.php" <?php
                                            if ($currentPageLoc == "add_user") {
                                                print("class='active'");
                                            } ?>>Add User</a></li>
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
                <form action="">
                    <select onchange="changeLanguage(this.value)">
                        <option value="en">EN</option>
                        <option value="fr">FR</option>
                        <option value="de">DE</option>
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