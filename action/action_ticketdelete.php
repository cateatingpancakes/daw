<?php
    require_once(__DIR__ . "/../require/connect.php");
    require_once(__DIR__ . "/../require/query.php");
    require_once(__DIR__ . "/../require/auth.php");

    Auth::gateBy(Auth::PERM_USERS);

    CSRF::check();

    $userData = Auth::getData();
    $userId = $userData["id"];

    if(isset($_POST["SALE_ID"]))
    {
        $saleId = $_POST["SALE_ID"];
        $record = Query::getUnique("sale", "SALE_ID", $saleId);
    
        if($record && $record["USER_ID"] == $userId)
        {
            Query::deleteUnique("sale", "SALE_ID", $saleId);
        }
    }
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.parent.postMessage("refresh");
    });
</script>