<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link rel="stylesheet" href="css.css">
</head>
<body>

    <div id="login-container">
        <h2>Log In</h2>
        <form id="login-form" action="submit_loginAdmin.php" method="POST">
            <div>
                <label for="login-email">Email:</label>
                <input type="email" id="login-email" name="email" required><br>
            </div>
            <div>
                <label for="login-password">Password:</label>
                <input type="password" id="login-password" name="password" required><br>
            </div>
            <button type="submit">Log In</button>
        </form>
        <p>
            Don't have an account? 
            <a href="#" onclick="showSignupForm()">Sign Up</a>
        </p>
    </div>

<div id="signup-container" style="display:none;">
    <h2>Sign Up</h2>
    <form id="signup-form" action="submit_signupAdmin.php" method="POST" onsubmit="return validateSignup()">
        <div>
            <label for="signup-username">Username:</label>
            <input type="text" id="signup-username" name="username" required><br>
        </div>
        <div>
            <label for="signup-email">Email:</label>
            <input type="email" id="signup-email" name="email" required><br>
        </div>
        <div>
            <label for="signup-password">Password:</label>
            <input type="password" id="signup-password" name="password" required><br>
        </div>
        <div>
            <label for="signup-confirm-password">Confirm Password:</label>
            <input type="password" id="signup-confirm-password" name="confirmPassword" required><br>
        </div>
        <button type="submit">Create Account</button>
    </form>
    <p>
        Already have an account? 
        <a href="#" onclick="showLoginForm()">Log In</a>
    </p>
</div>

    <script>
        const loginContainer = document.getElementById('login-container');
        const signupContainer = document.getElementById('signup-container');

        function validateSignup() {
            const password = document.getElementById('signup-password').value;
            const confirmPassword = document.getElementById('signup-confirm-password').value;

            if (password !== confirmPassword) {
                alert("Password confirmation does not match the password!");
                return false;
            }

            if (password.length < 8) {
                alert("The password must be at least 8 characters long");
                return false;
            }
    
            return true;
        } 
        function showLoginForm() {
            loginContainer.style.display = 'block';
            signupContainer.style.display = 'none';
        }

        function showSignupForm() {
            loginContainer.style.display = 'none';
            signupContainer.style.display = 'block';
        }
    </script>
    
</body>

<footer>
    <p>&copy; 2025 AUNS</p>
</footer>

</html>