<?php
require_once("tantsus.php");
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']))
    header("Location: ./index.php");
if (isset($_POST['registerBtn'])){
    $username = $_POST['username'];
    $passwd = $_POST['passwd'];
    $passwd_again = $_POST['passwd_again'];
    global $yhendus;
    $kask= $yhendus->prepare("SELECT * FROM kasutaja WHERE kasutaja=?");
    $kask->bind_param("s",$username);
    $kask->execute();
    if (!$kask->fetch()){
        $id = '';
        $cool="superpaev";
        $krypt=crypt($passwd, $cool);
        $passwd_hashed = $krypt;
        $date_created = time();
        $last_login = 0;
        $status = 1;
        if ($username != "" && $passwd != "" && $passwd_again != ""){
            if ($passwd === $passwd_again){
                if ( strlen($passwd) >= 5 && strpbrk($passwd, "!#$.,:;()")){
                    mysqli_query($yhendus, "INSERT INTO kasutaja (kasutaja, parool) VALUES ('$username', '$passwd_hashed')");
                    $query = mysqli_query($yhendus, "SELECT * FROM kasutaja WHERE kasutaja='{$username}'");
                    if (mysqli_num_rows($query) == 1){
                        $success = true;
                    }
                }
                else
                    $error_msg = 'Your password is not strong enough. Please use another.';
            }
            else
                $error_msg = 'Your passwords did not match.';
        }
        else
            $error_msg = 'Please fill out all required fields.';
    }
    else
        $error_msg = 'The username <i>'.$username.'</i> is already taken. Please use another.';
}
else
    $error_msg = 'An error occurred and your account was not created.';
?>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<form action="./register.php" class="form" method="POST">
    <div>
        <?php
        if (isset($success) && $success) {
            echo '<p style="color: green;">Yay!! Your account has been created. <a href="./login.php">Click here</a> to login!<p>';
        }
        ?>
    </div>
</form>
<form action="./register.php" class="form" method="POST">
    <h1>Registreeri uus kasutaja</h1>
    <div class="">
        <?php
        if (isset($success) && $success){
            echo '<p color="green">Yay!! Your account has been created. <a href="./login.php">Click here</a> to login!<p>';
        }
        else if (isset($error_msg))
            echo '<p color="red">'.$error_msg.'</p>';
        ?>
    </div>
    <div class="">
        <input type="text" name="username" value="" placeholder="enter a username" autocomplete="off" required />
    </div>
    <div class="">
        <input type="password" name="passwd" value="" placeholder="enter a password" autocomplete="off" required />
    </div>
    <div class="">
        <p>password must be at least 5 characters and<br /> have a special character, e.g. !#$.,:;()</font></p>
    </div>
    <div class="">
        <input type="password" name="passwd_again" value="" placeholder="confirm your password" autocomplete="off" required />
    </div>
    <div class="">
        <input class="" type="submit" name="registerBtn" value="create account" />
    </div>
    <p class="center"><br />
        Already have an account? <a href="login.php">Login here</a>
    </p>
</form>
