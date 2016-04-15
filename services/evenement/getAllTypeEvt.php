<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/persistance/eventDao.php');

	echo json_encode(getAllTypesEvt());
?>