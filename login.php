<?php
require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/Account.php");
$account = new Account($con);

if(isset($_POST["submitButton"])) {
    $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
    $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);

    // التحقق من أن الإدخالات تحتوي فقط على الأحرف الإنجليزية
    if(FormSanitizer::containsNonEnglishCharacters($username) || FormSanitizer::containsNonEnglishCharacters($password)) {
        $account->addError(Constants::$nonEnglishCharacters);
    }

    // تحقق من وجود SQL Injection
    if(FormSanitizer::containsSqlInjection(array($username, $password))) {
        $account->addError(Constants::$sqlInjectionAttempt);
    }
    
    $success = $account->login($username, $password);
    $account->setOnline($username); 
    if($success) {
        $_SESSION["userLoggedIn"] = $username;
        if($account->isAdmin($username)){
            header("Location: admin.php");
            exit(); 
        }
        header("Location: index.php");
        exit(); // Always exit after redirection
    }
}

function getInputValue($name) {
    if(isset($_POST[$name])) {
        echo $_POST[$name];
    }
}  
?>
<!DOCTYPE html>
<html>
<head>
    <title>Braudeflix</title>
    <link rel="stylesheet" type="text/css" href="assets/style/style.css" />
</head>
<body>
    
    <div class="signInContainer">

        <div class="column">

            <div class="header">
                <img src="assets/images/logo.png" title="Logo" alt="Site logo" />
                <h3>Sign In</h3>
                <span>to continue to Braudeflix</span>
            </div>

            <form method="POST">
                <?php echo $account->getError(Constants::$loginFailed); ?>
                <input type="text" name="username" placeholder="Username" value="<?php getInputValue("username"); ?>" required>

                <input type="password" name="password" placeholder="Password" required>

                <input type="submit" name="submitButton" value="SUBMIT">

            </form>

            <a href="register.php" class="signInMessage">Need an account? Sign up here!</a>
            <br>
            <a href="reset/checkEmail.php" class="signInMessage">Forgot Password!!</a>
        </div>

    </div>

</body>
</html>
