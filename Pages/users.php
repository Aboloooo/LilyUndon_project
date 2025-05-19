<?php
include_once("../Library/MyLibrary.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['users'] ?></title>
    <link rel="stylesheet" href="../style.css? <?= time(); ?>">
    <script src="../script.js"></script>
</head>

<body>
    <?= NavBar('users') ?>
    <?php
    if (isset($_POST['deleteUser'])) {
        $userIDToDelete = $_POST['deleteUserID'];

        $deleteUserStatment = $connection->prepare("DELETE FROM users WHERE UserID = ?");
        $deleteUserStatment->bind_param('i', $userIDToDelete);
        $deleteUserStatment->execute();
    }
    ?>

    <div class="user-table-container">
        <h2><?= $t['registered_users'] ?></h2>
        <table class="user-table">
            <thead>
                <tr>
                    <th><?= $t['user_id'] ?></th>
                    <th><?= $t['first_name'] ?></th>
                    <th><?= $t['last_name'] ?></th>
                    <th><?= $t['CNS_number'] ?></th>
                    <th><?= $t['username'] ?></th>
                    <th><?= $t['password'] ?></th>
                    <th><?= $t['email'] ?></th>
                    <th><?= $t['role'] ?></th>
                    <th><?= $t['changed_pass'] ?></th>
                    <th><?= $t['action'] ?></th>
                </tr>
            </thead>
            <?php
            $displayUsers = $connection->prepare('select * from users');
            $displayUsers->execute();
            $result = $displayUsers->get_result();
            while ($row = $result->fetch_assoc()) {
                $UserID = $row['UserID'];
                $Fname = $row['First_name'];
                $Lname = $row['Last_name'];
                $CNS = $row['social_security_number'];
                $UserN = $row['Username'];
                $Password = $row['Password'];
                $Email = $row['Email'];
                $Level = $row['Level'];
                $mustChangePass = $row['user_must_change_password'];

            ?>
                <tbody>
                    <tr>
                        <td><?= $UserID ?></td>
                        <td><?= $Fname ?></td>
                        <td><?= $Lname ?></td>
                        <td><?= $CNS ?></td>
                        <td><?= $UserN ?></td>
                        <td><?= $Password ?></td>
                        <td><?= $Email ?></td>
                        <td><?= $Level ?></td>
                        <td><?php if ($mustChangePass == 1) {
                                echo $t['false'];
                            } else {
                                echo $t['true'];
                            } ?></td>
                        <td>
                            <!-- Example action -->
                            <form method="POST" onsubmit="return confirm('<?= $t['confirm_delete_user'] ?>');" style="display:inline;">
                                <input type="hidden" name="deleteUserID" value="<?= $UserID ?>">
                                <button type="submit" name="deleteUser" class="action-btn"><?= $t['delete'] ?></button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            <?php
            }
            ?>
        </table>
    </div>

</body>

</html>