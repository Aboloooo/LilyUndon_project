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
    if (isset($_POST['statusBtnChange'])) {
        $userIDToChangeStatus = $_POST['statusBtnChangeUserID'];

        $fetchStatus = $connection->prepare('select status from users where UserID = ?');
        $fetchStatus->bind_param('i', $userIDToChangeStatus);
        $fetchStatus->execute();
        $resultStatus = $fetchStatus->get_result();
        $userStatusRow = $resultStatus->fetch_assoc();
        $userStatus = $userStatusRow['status'];

        $newStatus = (strtolower($userStatus) == 'pending') ? 'active' : 'pending';


        $statusUserToChangeStatment = $connection->prepare("update users set status =? WHERE UserID = ?");
        $statusUserToChangeStatment->bind_param('si', $newStatus, $userIDToChangeStatus);
        $statusUserToChangeStatment->execute();
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
                    <th><?= $t['status'] ?></th>
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
                $Level = $row['AccessLevelID'];
                $status = $row['status'];
                $mustChangePass = $row['user_must_change_password'];

                $btnStyle = (strtolower($status) == 'pending')
                    ? 'background-color: #f0ad4e; color: #fff; border: none; padding: 6px 12px; border-radius: 4px;'
                    : 'background-color: #28a745; color: #fff; border: none; padding: 6px 12px; border-radius: 4px;';



            ?>
                <tr>
                    <td><?= $UserID ?></td>
                    <td><?= $Fname ?></td>
                    <td><?= $Lname ?></td>
                    <td><?= $CNS ?></td>
                    <td><?= $UserN ?></td>
                    <td><?= $Password ?></td>
                    <td><?= $Email ?></td>
                    <td><?= $Level == 1 ? 'Admin' : ' ' ?>
                        <?= $Level == 2 ? 'SecurityGuard' : ' ' ?>
                        <?= $Level == 3 ? 'Residence' : ' ' ?>
                    </td>
                    <td><?php if ($mustChangePass == 1) {
                            echo $t['false'];
                        } else {
                            echo $t['true'];
                        } ?></td>

                    <td>
                        <?php
                        $disableIfAdmin = ($Level == 1) ? "disabled" : " ";
                        ?>
                        <!-- Either activate or deactivate user -->
                        <form method="POST" onsubmit="return confirm('<?= $t['confirmation_either_to_activate_or_deactivate_user'] ?>');" style="display:inline;">
                            <input type="hidden" name="statusBtnChangeUserID" value="<?= $UserID ?>">
                            <button <?= $disableIfAdmin ?> type="submit" name="statusBtnChange" style="<?= $btnStyle ?>" class="action-btn"><?= $t[strtolower($status)] ?></button>
                        </form>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                        <tbody>
                    </td>
                    <td>
                        <!-- Delete user action -->
                        <?php
                        if ($Level == 3 || $Level == 2) {
                        ?>
                            <form method="POST" onsubmit="return confirm('<?= $t['confirm_delete_user'] ?>');" style="display:inline;">
                                <input type="hidden" name="deleteUserID" value="<?= $UserID ?>">
                                <button type="submit" name="deleteUser" class="action-btn"><?= $t['delete'] ?></button>
                            </form>
                        <?php
                        }
                        ?>
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