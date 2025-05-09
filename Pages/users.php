<?php
include_once("../Library/MyLibrary.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="../style.css? <?= time(); ?>">
    <script src="../script.js"></script>

    <style>
        .user-table-container {
            max-width: 1300px;
            margin: 40px auto;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .user-table-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table.user-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.user-table th,
        table.user-table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table.user-table th {
            background-color: #f5f5f5;
        }

        table.user-table tr:hover {
            background-color: #f0f8ff;
        }

        .action-btn {
            background-color: #ff4d4f;
            border: none;
            padding: 5px 10px;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        .action-btn:hover {
            background-color: #d9363e;
        }
    </style>
</head>

<body>
    <?= NavBar('users') ?>

    <div class="user-table-container">
        <h2>Registered Users</h2>
        <table class="user-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>CNS Number</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Changed Pass</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>user_ID</td>
                    <td>a</td>
                    <td>a</td>
                    <td>1234567891234</td>
                    <td>a</td>
                    <td>a</td>
                    <td>a@gmail.com</td>
                    <td>customer</td>
                    <td>Yes</td>
                    <td>
                        <!-- Example action -->
                        <form method="POST" onsubmit="return confirm('Delete this user?');" style="display:inline;">
                            <input type="hidden" name="deleteUserID" value="userID">
                            <button type="submit" name="deleteUser" class="action-btn">Delete</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</body>

</html>