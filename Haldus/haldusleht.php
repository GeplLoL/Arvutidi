<?php
require_once('tantsus.php');
session_start();
if (isset($_REQUEST["heatants"])){
    global $yhendus;
    if (!isAdmin()) {
        $kask = $yhendus->prepare("UPDATE tantsud SET punktid=punktid+1 where id=?");
        $kask->bind_param("i", $_REQUEST["heatants"]);
        $kask->execute();
    }
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
    exit();
}
if (isset($_REQUEST["heatantsDel"])){
    global $yhendus;
    if (!isAdmin()) {
        $kask = $yhendus->prepare("UPDATE tantsud SET punktid=punktid-1 where id=?");
        $kask->bind_param("i", $_REQUEST["heatantsDel"]);
        $kask->execute();
    }
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
    exit();
}
if (isset($_REQUEST["paarinimi"]) && !empty($_REQUEST["paarinimi"])){
    global $yhendus;
    if (!isAdmin()) {
        $kask = $yhendus->prepare("INSERT INTO tantsud (tantsupaar, ava_paev) VALUES (?, NOW())");
        $kask->bind_param("s", $_REQUEST["paarinimi"]);
        $kask->execute();
    }
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
    exit();
}
if (isset($_REQUEST["paarinimiDel"]) && !empty($_REQUEST["paarinimiDel"])){
    global $yhendus;
    if (isAdmin()) {
    $kask=$yhendus->prepare("DELETE FROM tantsud WHERE id=?");
    $kask->bind_param("s", $_REQUEST["paarinimiDel"]);
    $kask->execute();
    }
}
if(isset($_REQUEST["komment"])){
    if (isset($_REQUEST["uuskomment"]) && !empty($_REQUEST["uuskomment"])){
    global $yhendus;
    if (!isAdmin()) {
        $kask = $yhendus->prepare("UPDATE tantsud SET kommentaarid=CONCAT(kommentaarid, ?) WHERE  id=?");
        $kommentplus = $_REQUEST["uuskomment"] . "\n";
        $kask->bind_param("si", $kommentplus, $_REQUEST["komment"]);
        $kask->execute();
    }
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
    //exit();
    }
}
if(isset($_REQUEST["kommentDel"])){
        global $yhendus;
        if (isAdmin()) {
            $kask = $yhendus->prepare("UPDATE tantsud SET kommentaarid=' ' WHERE  id=?");
            $kask->bind_param("s", $_REQUEST["kommentDel"]);
            $kask->execute();
        }
        header("Location: $_SERVER[PHP_SELF]");
        $yhendus->close();
        exit();
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
<h1>Tantsud tähtedega</h1>
<h2>KasutajaLeht</h2>
<nav>

    <?php
    if (isset($_SESSION['kasutaja'])) {
        echo "<a href='haldusleht.php'>Kasutaja</a>";
    }
    if (isAdmin()){
        ?>
        <a href="adminleht.php">Admin</a>
    <?php }   ?>
</nav>
<table>
    <tr>
        <th>Tantsupaari nimi</th>
        <th>Punktid</th>
        <th>Kuupaev</th>
        <th>Kommentaarid</th>
    </tr>
<?php
global $yhendus;
        $kask = $yhendus->prepare("SELECT id, tantsupaar, punktid, ava_paev, kommentaarid FROM tantsud where avalik=1");
        $kask->bind_result($id, $tantsupaar, $punktid, $paev, $komment);
        $kask->execute();
        while ($kask->fetch()) {
            echo "<tr>";
            $tantsupaar = htmlspecialchars($tantsupaar);
            echo "<td>" . $tantsupaar . "</td>";
            echo "<td>" . $punktid . "</td>";
            echo "<td>" . $paev . "</td>";
            echo "<td>" . nl2br(htmlspecialchars($komment)) . "</td>";
            if(isset($_SESSION['kasutaja'])) {
            echo "<td>
    <form action='?'>
        <input type='hidden' value='$id' name='komment'>
        <input type='text' name='uuskomment' id='uuskomment'>
        <input type='submit' value='OK'>
    </form>
        ";
            echo "<td><a href='?heatants=$id'>Lisa +1punkt</a></td>";
            echo "<td><a href='?heatantsDel=$id'>Delete -1punkt</a></td>";
            echo "<td><a href='?paarinimiDel=$id'>Delete</a></td>";
            echo "<td><a href='?kommentDel=$id'>Komment Delete</a></td>";
            echo "<tr>";
        }
    }
?>
</table>
<?php
    if(isset($_SESSION['kasutaja'])) {
        echo '<form action="" method="POST">';
        echo '    <label for="paarinimi">Lisa uus paar</label>';
        echo '    <input type="text" name="paarinimi" id="paarinimi">';
        echo '    <input type="submit" value="Lisa paar">';
        echo '</form>';
    }
?>

</body>
</html>
