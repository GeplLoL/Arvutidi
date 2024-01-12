<?php
require_once("tantsus.php");
global $yhendus;
session_start();
if (!empty($_POST['login']) && !empty($_POST['pass'])) {
    $login = htmlspecialchars(trim($_POST['login']));
    $pass = htmlspecialchars(trim($_POST['pass']));
    $cool="superpaev";
    $krypt = crypt($pass, $cool);
    $kask=$yhendus-> prepare("SELECT kasutaja, onAdmin FROM kasutaja WHERE kasutaja=? AND parool=?");
    $kask->bind_param("ss", $login, $krypt);
    $kask->bind_result($kasutaja, $onAdmin);
    $kask->execute();
    if ($kask->fetch()) {
        $_SESSION['tuvastamine'] = 'misiganes';
        $_SESSION['kasutaja'] = $login;
        $_SESSION['onAdmin'] = $onAdmin;
        if ($onAdmin==1) {
            header("location: adminleht.php");
        }
        else {
            header("location: haldusleht.php");
            $yhendus->close();
            exit();
        }
    } else {
        echo "kasutaja $login või parool $krypt on vale";
        $yhendus->close();
    }
}
?>
<h1>Login</h1>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<form action="" method="post">
    Login: <input type="text" name="login"><br>
    Password: <input type="password" name="pass"><br>
    <input type="submit" value="Logi sisse">
</form>