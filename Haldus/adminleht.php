<?php
require_once('tantsus.php');
session_start();
if (isset($_REQUEST["punktid0"])) {
    global $yhendus;
    if (isAdmin()) {
        $kask = $yhendus->prepare("UPDATE tantsud SET punktid=0 where id=?");
        $kask->bind_param("i", $_REQUEST["punktid0"]);
        $kask->execute();
    }
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
    exit();
}

if (isset($_REQUEST["peitmine"])) {
    global $yhendus;
    if (isAdmin()) {
        $kask = $yhendus->prepare("UPDATE tantsud SET avalik=0 where id=?");
        $kask->bind_param("i", $_REQUEST["peitmine"]);
        $kask->execute();
    }
}

if (isset($_REQUEST["naitmine"])) {
    global $yhendus;
    if (isAdmin()) {
        $kask = $yhendus->prepare("UPDATE tantsud SET avalik=1 where id=?");
        $kask->bind_param("i", $_REQUEST["naitmine"]);
        $kask->execute();
    }
}
function isAdmin(){
    return isset($_SESSION['onAdmin']) &&$_SESSION['onAdmin'];
}
?>
<!doctype html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Tantsud tähtedega</title>
    <script src="code.js"></script>


</head>
<body>
<header>
    <?php
    if (isset($_SESSION['kasutaja'])) {
        ?>
        <h1>Tere, <?= $_SESSION['kasutaja'] ?></h1>
        <a href="logout.php">Logi välja</a>
        <?php
    } else {
        ?>
        <a href="#" onclick="openModal()">Logi sisse</a>
        <a href="register.php">Register</a>
        <?php
    }
    ?>
</header>

<!-- Modal for Login -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h1>Login</h1>
        <form id="loginForm" action="login.php" method="post">
            <label for="login">Login:</label>
            <input type="text" name="login" required>
            <br>
            <label for="pass">Password:</label>
            <input type="password" name="pass" required>
            <br>
            <input type="submit" value="Logi sisse">
        </form>
    </div>
</div>

<nav>
    <a href="haldusleht.php">Kasutaja</a>
    <a href="adminleht.php">Admin</a>
</nav>
<h1>Tantsud tähtedega</h1>
<h2>AdministreerimistLeht</h2>
<table>
    <tr>
        <th>Tantsupaari nimi</th>
        <th>Punktid</th>
        <th>Kuupaev</th>
        <th>Kommentaarid</th>
    </tr>
<?php
global $yhendus;
    $kask=$yhendus->prepare("SELECT id, tantsupaar, punktid, ava_paev, kommentaarid, avalik FROM tantsud");
    $kask->bind_result($id, $tantsupaar, $punktid, $paev, $komment, $avalik);
    $kask->execute();
        while ($kask->fetch()) {
            $tekst = "Näita";
            $seisund = "naitmine";
            $tekst2 = "Kasutaja ei näe";
            if ($avalik == 1) {
                $tekst = "Peida";
                $seisund = "peitmine";
                $tekst2 = "Kasutaja näeb";
            }
                echo "<tr>";
                $tantsupaar = htmlspecialchars($tantsupaar);
                echo "<td>" . $tantsupaar . "</td>";
                echo "<td>" . $punktid . "</td>";
                echo "<td>" . $paev . "</td>";
                echo "<td>" . $komment . "</td>";
                echo "<td>" . $avalik . "/" . $tekst2 . "</td>";
                    echo "<td><a href='?punktid0=$id'>Punktid Nulliks!</a></td>";
                    echo "<td><a href='?$seisund=$id'>$tekst</a></td>";
                echo "<tr>";
    }
?>
</table>
</body>
</html>
