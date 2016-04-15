<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/persistance/eventDao.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/class/RoleUser.php');

session_start();

if(isset($_SESSION['userConnect'])) {
	$user = $_SESSION['userConnect'];
	
	if($user->role->code == ROLE_EDITOR && isset($user->id) ) {
		echo json_encode(getAllEventsByIdJeu($user->id, false));
	} else {
		echo json_encode(getAllEvents(false));
	}
} else {
	echo "Perte de session. Veuillez vous reconnecter.";
}
?>