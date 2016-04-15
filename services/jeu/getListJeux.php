<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/persistance/jeuDao.php');

echo json_encode(getListJeux());
?>