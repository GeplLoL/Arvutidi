# Töö tehti PHP praktika eesmärgil
## PHP kood, mis sisaldab veebisaidi peamist loogikat
```
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
```
