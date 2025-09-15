<?php
session_start();
require_once('../model/userModel.php');

if (!isset($_SESSION['status']) || $_SESSION['status'] !== true) {
    header('location: ../view/login.php?error=badrequest');
    exit;
}
if (strtolower($_SESSION['role']) !== 'user') {
    header('location: ../view/login.php?error=badrequest');
    exit;
}

$id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
if ($id === 0) {
    echo json_encode(['status' => 'error', 'message' => 'User ID not found.']);
    exit;
}

$currentPassword = '';
$user = getUserById($id);
if ($user) {
    $currentPassword = $user['password'] ?? '';
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not found.']);
    exit;
}

$errors = ['old' => '', 'new' => '', 'confirm' => '', 'general' => ''];
$success = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Update Password</title>
    <link rel="stylesheet" type="text/css" href="../asset/auth.css">
    <style>
        .error-msg { color: red; font-weight: 600; }
        .center-success { text-align: center; font-weight: bold; color: green; margin: 8px 0 16px; }
        .disabled-btn { background: #ccc; cursor: not-allowed; }
    </style>
</head>
<body>
    <h1>Update Password</h1>

    <form id="updatePasswordForm">
        <fieldset>
            Old Password:
            <input type="password" id="oldPass" name="old_password">
            <p id="oldError" class="error-msg"></p>

            New Password:
            <input type="password" id="newPass" name="new_password">
            <p id="newError" class="error-msg"></p>

            Retype New Password:
            <input type="password" id="confirmPass" name="confirm_password">
            <p id="confirmError" class="error-msg"></p>

            <input type="submit" id="updateBtn" value="Update Password" disabled class="disabled-btn">
            <p id="updateSuccess"></p>

            <input type="button" value="Back to Dashboard" onclick="window.location.href='user_dashboard.php'">
        </fieldset>
    </form>

    <script>
        const oldPassEl = document.getElementById('oldPass');
        const newPassEl = document.getElementById('newPass');
        const confirmPassEl = document.getElementById('confirmPass');
        const updateBtn = document.getElementById('updateBtn');

        function validateForm() {
            const oldPass = oldPassEl.value.trim();
            const newPass = newPassEl.value.trim();
            const confirmPass = confirmPassEl.value.trim();

            let valid = true;

            if (oldPass === "") {
                document.getElementById('oldError').innerHTML = "Enter old password!";
                valid = false;
            } else {
                document.getElementById('oldError').innerHTML = "";
            }

            if (newPass === "") {
                document.getElementById('newError').innerHTML = "Enter new password!";
                valid = false;
            } else if (newPass.length < 4) {
                document.getElementById('newError').innerHTML = "At least 4 characters!";
                valid = false;
            } else if (oldPass !== "" && oldPass === newPass) {
                document.getElementById('newError').innerHTML = "New password must differ from old password!";
                valid = false;
            } else {
                document.getElementById('newError').innerHTML = "";
            }

            if (confirmPass === "") {
                document.getElementById('confirmError').innerHTML = "Retype new password!";
                valid = false;
            } else if (newPass !== confirmPass) {
                document.getElementById('confirmError').innerHTML = "Passwords do not match!";
                valid = false;
            } else {
                document.getElementById('confirmError').innerHTML = "";
            }
            if (valid) {
                updateBtn.disabled = false;
                updateBtn.classList.remove("disabled-btn");
            } else {
                updateBtn.disabled = true;
                updateBtn.classList.add("disabled-btn");
            }
        }

        oldPassEl.addEventListener('input', validateForm);
        newPassEl.addEventListener('input', validateForm);
        confirmPassEl.addEventListener('input', validateForm);

        document.getElementById('updatePasswordForm').onsubmit = function(e) {
            e.preventDefault();
            if (updateBtn.disabled) return; 

            var oldPass = oldPassEl.value.trim();
            var newPass = newPassEl.value.trim();
            var confirmPass = confirmPassEl.value.trim();

            var formData = new FormData();
            formData.append('old_password', oldPass);
            formData.append('new_password', newPass);
            formData.append('confirm_password', confirmPass);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../controller/update_password_handler.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        document.getElementById('updateSuccess').innerHTML = response.message;
                        document.getElementById('updateSuccess').style.color = 'green';
                    } else {
                        document.getElementById('updateSuccess').innerHTML = response.message;
                        document.getElementById('updateSuccess').style.color = 'red';
                    }
                }
            };
            xhr.send(formData);
        };
    </script>
</body>
</html>
