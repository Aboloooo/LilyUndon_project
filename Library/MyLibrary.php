<?php
session_start();

/* connection to database */
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'wesers2';
$connection = mysqli_connect($host, $username, $password, $database);



if (!isset($_SESSION["loggedInUser"])) {
    $_SESSION["loggedInUser"];
}
if (!isset($_SESSION["username"])) {
    $_SESSION["username"];
}
if (!isset($_SESSION["Admin"])) {
    $_SESSION["Admin"];
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
            <li><a href="logout_in.php" <?php
                                        if ($currentPageLoc == "logout_in") {
                                            print("class='active'");
                                        } ?>>Login/out</a></li>
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