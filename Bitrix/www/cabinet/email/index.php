<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Подтверждение email");
?>


<?php
$emailConfirm = new EmailConfirm();

if (! empty($_GET['id'])) {
    $emailConfirm->add($_GET['id']);
    echo '<h2>Спасибо! Ваш email подтвержден!</h2>';

}





?>






<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
