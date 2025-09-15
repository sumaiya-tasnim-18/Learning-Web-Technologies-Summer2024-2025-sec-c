<?php
session_start();

$errorMsg = '';
if (isset($_GET['error'])) {
    $err = $_GET['error'];
    if ($err === 'userid_exists') {
        $errorMsg = 'This User ID is already registered.';
    } elseif ($err === 'regerror') {
        $errorMsg = 'Registration failed. Try again.';
    } elseif ($err === 'badrequest') {
        $errorMsg = 'Please fill the form correctly.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Signup</title>
    <link rel="stylesheet" type="text/css" href="../asset/auth.css">
    <style>
        .error-msg { color: red; font-weight: 600; margin: 4px 0; }
        .ok { color: green; font-weight: 700; margin-top: 10px; text-align: center; }
        .notice { text-align:center; font-weight:700; color:green; margin-bottom:12px; }
    </style>
</head>
<body>
    <h1>Signup Page</h1>

    <?php if (!empty($errorMsg)): ?>
        <p class="notice"><?= htmlspecialchars($errorMsg) ?></p>
    <?php endif; ?>

    <form id="signupForm">
        <fieldset>
            Username:
            <input type="text" id="signupUsername" name="username" onblur="checkSignupUsername()" />
            <p id="signupUError" class="error-msg"></p>

            User ID:
            <input type="text" id="signupUserId" name="userid" onblur="checkSignupUserId()" />
            <p id="signupIdError" class="error-msg"></p>

            Password:
            <input type="password" id="signupPassword" name="password" onblur="checkSignupPassword()" />
            <p id="signupPError" class="error-msg"></p>

            Confirm Password:
            <input type="password" id="signupConfirm" name="confirm" onblur="checkSignupConfirm()" />
            <p id="signupCError" class="error-msg"></p>

            Role: 
            <label><input type="radio" name="role" value="User" checked>User</label>
            <label><input type="radio" name="role" value="Admin">Admin</label>
            <p id="signupRoleError" class="error-msg"></p>

            <input type="button" value="Sign Up" onclick="signupUser()" />
            <p id="signupSuccess" class="ok"></p>
        </fieldset>

        <p style="text-align:center;">
            <input type="button" value="Login" onclick="window.location.href='login.php'">
        </p>
    </form>

    <script>
        function checkSignupUsername() {
            const username = document.getElementById('signupUsername').value.trim();
            let msg = "";
            if (username === "") msg = "Please type username!";
            else if (username.length < 3) msg = "Username must be at least 3 characters!";
            document.getElementById('signupUError').innerHTML = msg;
        }
        
        function checkSignupUserId() {
            const userid = document.getElementById('signupUserId').value.trim();
            let msg = "";
            if (userid === "") msg = "Please enter User ID!";
            else if (userid.length < 3) msg = "User ID must be at least 3 characters!";
            document.getElementById('signupIdError').innerHTML = msg;
        }

        function checkSignupPassword() {
            const password = document.getElementById('signupPassword').value;
            document.getElementById('signupPError').innerHTML =
                password.length < 4 ? "Password must be at least 4 characters!" : "";
        }

        function checkSignupConfirm() {
            const password = document.getElementById('signupPassword').value;
            const confirm = document.getElementById('signupConfirm').value;
            document.getElementById('signupCError').innerHTML =
                (password !== confirm) ? "Passwords do not match!" : "";
        }

        function signupUser() {
            checkSignupUsername();
            checkSignupUserId();
            checkSignupPassword();
            checkSignupConfirm();
            
            const username = document.getElementById('signupUsername').value.trim();
            const userid = document.getElementById('signupUserId').value.trim();
            const password = document.getElementById('signupPassword').value;
            const confirm = document.getElementById('signupConfirm').value;
            const role = document.querySelector('input[name="role"]:checked')?.value;

            const ok = document.getElementById('signupUError').innerHTML === "" &&
                       document.getElementById('signupIdError').innerHTML === "" &&
                       document.getElementById('signupPError').innerHTML === "" &&
                       document.getElementById('signupCError').innerHTML === "";

            document.getElementById('signupSuccess').innerHTML = ok ? "Submitting…" : "";

            if (!ok) return false;

            const user = {
                'username': username,
                'userid': userid,
                'password': password,
                'role': role
            };

            const data = JSON.stringify(user);

            const xhttp = new XMLHttpRequest();
            xhttp.open('POST', '../controller/signupCheck.php', true);
            xhttp.setRequestHeader("Content-type", "application/json");
            xhttp.send(data);

            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    const response = JSON.parse(this.responseText);
                    if (response.status === 'success') {
                        document.getElementById('signupSuccess').innerHTML = "Signup successful!";
                        window.location.href = 'login.php?success=registered';
                    } else {
                        document.getElementById('signupSuccess').innerHTML = response.message;
                    }
                }
            }
        }
    </script>
</body>
</html>
