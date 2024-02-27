# Töö tehti PHP praktika eesmärgil
### PHP kood, mis sisaldab veebisaidi peamist loogikat

Md-laiendiga fail on Markdowni dokument. 
Markdown on kerge märgistuskeel, mis on loodud lihtsate märkide ja süntaksi abil teksti hõlpsaks vormindamiseks. 
See loodi eesmärgiga olla loetav ja hõlpsasti kirjutatav, kuid samas konverteeritav keerukamatesse vormingutesse, nagu HTML.

## Tantsupaaride punktide haldamine:
Kui taotluses on soojusainete parameeter olemas, suurendatakse vastava tantsupaari punktide arvu 1 võrra.
Kui päringus on parameeter heatantsDel olemas, vähendatakse vastava tantsupaari punktide arvu 1 võrra.

*
### Uue tantsupaari lisamine:
Kui päringus on parameeter paarinimi ja kasutaja ei ole administraator, lisatakse uus tantsupaar määratud nime ja praeguse kuupäevaga.

*
### Tantsupaari eemaldamine:
Kui päringus on parameeter paarinimiDel ja kui kasutaja on administraator, siis määratud identifikaatorit kasutav tantsupaar kustutatakse.

*
### Kommentaari lisamine tantsupaarile:
Kui päringus on kommentaar ja uuskomment parameetrid olemas ja kui kasutaja ei ole administraator, lisatakse tantsupaarile kommentaar.

*
### Kõigi <sub>tantsupaari kommentaaride kustutamine:</sub>
Kui päringus on parameeter commentDel ja kui kasutaja on administraator, kustutatakse kõik määratud tantsupaari kommentaarid.

*
### Juurdepääsuõiguste kontrollimine:
Funktsiooni isAdmin() <sup> kasutatakse kontrollimaks, kas praegune</sup>  kasutaja on administraator.

*
### Seansi juhtimine:
Seansi alustamiseks <sup> kasutage funktsiooni </sup> session_start().
Kood eeldab, et seansis <sub> salvestatakse teave administraatori </sub> oleku kohta.


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
![image](https://github.com/GeplLoL/Tantsud/assets/85700200/69b6eb5f-d792-4089-8160-14e25d6fa275)


![image](https://github.com/GeplLoL/Tantsud/assets/85700200/60bcde1d-356f-44cb-a34a-2ec1171b2ca0)
