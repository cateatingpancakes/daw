<?php
    require_once(__DIR__ . "/../require/connect.php");
    require_once(__DIR__ . "/../require/query.php");
    require_once(__DIR__ . "/../require/auth.php");
    require_once(__DIR__ . "/../mail/class.phpmailer.php");
    require_once(__DIR__ . "/../mail/config.php");
    require_once(__DIR__ . "/../view/utility.php");
    require_once(__DIR__ . "/../require/stat.php");

    Stat::log($_SERVER["SCRIPT_NAME"]);

    $userData = Auth::getData();

    if($userData != null)
    {
        CSRF::check();

        $record = Query::getUnique("user", "USERNAME", $userData["username"]);
        $confCode = $record["CONF_CODE"];

        $whoami = mailcfg\mailcfg::MAIL_WHOAMI;
        $mailHead = "Confirmare cont pentru proiect DAW";
        $mailText = "<p>Pentru a vă valida contul nou-creat, folosiți link-ul $whoami/register_valid.php?CONF_CODE=$confCode</p>";

        $nospoof = Query::getUnique("user", "USER_ID", $userData["id"])["NOSPOOF"];

        if($nospoof != null)
        {
            $mailText .= "<p>Cuvântul tău secret pentru verificarea autenticității acestui mesaj este: <strong>$nospoof</strong></p>";
        }
        else
        {
            $mailText .= "<p>Nu ai un cuvânt secret setat. Intră în contul tău și setează-ți un cuvânt prin care să poți recunoaște că un mail de la noi este autentic.</p>";
        }

        $status = mail2($userData["email"], $mailHead, $mailText, $userData["realname"]);
        // var_dump($status);
    }

?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.parent.postMessage("completed");
    });
</script>