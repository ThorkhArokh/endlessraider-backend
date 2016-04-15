<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/persistance/joueurDao.php');

	echo json_encode(getAllRoles());

?>