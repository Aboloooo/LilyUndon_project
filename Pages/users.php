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
    <script src="../script.js" <?= time(); ?>></script>
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

    // Changing user status
    if (isset($_POST['statusSliderChange']) && isset($_POST['statusBtnChangeUserID'])) {
        $newStatus = ($_POST['statusSliderChange'] == '1') ? 'active' : 'pending';
        $userID = $_POST['statusBtnChangeUserID'];

        $updateStatusStmt = $connection->prepare("UPDATE users SET status = ? WHERE UserID = ?");
        $updateStatusStmt->bind_param('si', $newStatus, $userID);
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
                $Password = $row['Password'];
                $Email = $row['Email'];
                $Level = $row['AccessLevelID'];
                $status = strtolower($row['status']);
                $mustChangePass = $row['user_must_change_password'];


                $isActive = $status === 'active';
                $btnStyle = $isActive
                    ? 'background-color: #28a745; color: #fff;'
                    : 'background-color: #f0ad4e; color: #fff;';

                $ActiveOrNot = $isActive ? '0' : '1'; // what to send on toggle
                $checked = $isActive ? 'checked' : '';


                $AutoSubmition = 'onchange="this.form.submit()"';
                $ActiveOrNot = $status == 'active' ? '0' : '1';
                $checked = ($status == 'active') ? 'checked' : '';
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
                        $ActiveIfAdmin = ($Level == 1) ? "1" : " ";
                        ?>
                        <!-- Either activate or deactivate user -->
                        <!-- <form method="POST" onsubmit="return confirm('<?= $t['confirmation_either_to_activate_or_deactivate_user'] ?>');" style="display:inline;">
                            <input type="hidden" name="statusBtnChangeUserID" value="<?= $UserID ?>">
                            <button <?= $disableIfAdmin ?> type="submit" name="statusBtnChange" style="<?= $btnStyle ?>" class="action-btn"><?= $t[$status] ?></button>
                        </form> -->
                        <form method="post">
                            <input type="hidden" name="statusBtnChangeUserID" value="<?= $UserID ?>">
                            <label class="switch">
                                <input type="checkbox"
                                    name="statusSliderChange"
                                    value="<?= $ActiveOrNot ?>"
                                    onchange="this.form.submit()"
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

</body>

</html>