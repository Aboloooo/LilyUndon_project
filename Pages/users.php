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
    <!-- bank of icons -->
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
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

    //Update user role
    if (isset($_POST['Role'])) {
        $newRole = $_POST['Role'];
        $UserID = $_POST['ID'];

        $updateRoleStat = $connection->prepare('UPDATE users set AccessLevelID = ? where UserID = ? ');
        $updateRoleStat->bind_param('si', $newRole, $UserID);
        $updateRoleStat->execute();
    }

    // Changing user status
    if (isset($_POST['statusBtnChangeUserID'])) {
        $userID = $_POST['statusBtnChangeUserID'];

        $currentStatusOfUser  = $connection->prepare('select status from users where UserID = ?');
        $currentStatusOfUser->bind_param('i', $userID);
        $currentStatusOfUser->execute();
        $resultOfStatus = $currentStatusOfUser->get_result();
        $currentStatus = $resultOfStatus->fetch_assoc();
        // echo $currentStatus['status'];

        $newStatus = ($currentStatus['status'] == 0) ? 1 : 0;

        $updateStatusStmt = $connection->prepare("UPDATE users SET status = ? WHERE UserID = ?");
        $updateStatusStmt->bind_param('ii', $newStatus, $userID);
        $updateStatusStmt->execute();
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
                    /*  $Password = strlen($row['Password']) */;
                $Password = '●●●●●●●●●●';
                $Email = $row['Email'];
                $Level = $row['AccessLevelID'];
                $UserIsAdmin = ($Level == 1) ? true : false;
                /* $status = strtolower($row['status']); */
                $status = ($row['status'] == 0) ? "Pending" : "Active";
                $mustChangePass = $row['user_must_change_password'];

                $isActive = $status === 'Active';

                $btnStyle = $isActive
                    ? 'background-color: #28a745; color: #fff;'
                    : 'background-color: #f0ad4e; color: #fff;';

                $ActiveOrNot = $isActive ? '1' : '0'; // what to send on toggle 

                $checked = $isActive ? 'checked' : '';

                $AutoSubmition = 'onchange="this.form.submit()"';
            ?>
                <tr>
                    <td><?= $UserID ?></td>
                    <td><?= $Fname ?></td>
                    <td><?= $Lname ?></td>
                    <td><?= $CNS ?></td>
                    <td><?= $UserN ?></td>
                    <td><?= $Password ?></td>
                    <td><?= $Email ?></td>
                    <?php
                    if ($UserIsAdmin) {
                    ?>
                        <td>Admin</td>
                    <?php
                    } else {
                    ?>
                        <td>
                            <form method="Post" onsubmit="return confirm('Are you sure you want to make change?');">
                                <input type="hidden" name="ID" value="<?= $UserID ?>">
                                <select name="Role" <?= $AutoSubmition ?> class="level">
                                    <option value="2" <?= ($Level == 2) ? 'selected' : '' ?>>SecurityGuard</option>
                                    <option value="3" <?= ($Level == 3) ? 'selected' : '' ?>>Residence</option>
                                </select>

                            </form>
                        </td>
                    <?php
                    }
                    ?>
                    <!-- <td><?= $Level == 1 ? 'Admin' : ' ' ?>
                        <?= $Level == 2 ? 'SecurityGuard' : ' ' ?>
                        <?= $Level == 3 ? 'Residence' : ' ' ?>
                    </td> -->

                    <td><?php if ($mustChangePass == 1) {
                            echo $t['false'];
                        } else {
                            echo $t['true'];
                        } ?></td>

                    <td>
                        <?php
                        $disableIfAdmin = ($Level == 1) ? "disabled" : " ";
                        $ActiveIfAdmin = ($Level == 1) ? "1" : " ";
                        ?>

                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="statusBtnChangeUserID" value="<?= $UserID ?>">
                            <label class="switch">
                                <input type="checkbox"
                                    name="statusSliderChange"
                                    value="<?= $ActiveOrNot ?>"
                                    onchange="if(confirm('<?= $t['confirmation_either_to_activate_or_deactivate_user'] ?>')) this.form.submit(); else this.checked = !this.checked;"
                                    <?= $checked ?>
                                    <?= $Level == 1 ? 'disabled' : '' ?>>
                                <span class="slider round" style="<?= $btnStyle ?>"></span>
                            </label>
                        </form>


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





    <script src="../script.js"></script>
</body>

</html>