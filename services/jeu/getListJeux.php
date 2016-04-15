<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/persistance/jeuDao.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/class/RoleUser.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/class/User.php');

session_start();
	
if(isset($_SESSION['userConnect'])) {
	$user = $_SESSION['userConnect'];

	if($user->role->code == ROLE_EDITOR && isset($user->id) ) {
		echo json_encode(getListJeuxByIdUser($user->id));
	} else {
		echo json_encode(getListJeux());
	}
} else {
	echo "Perte de session. Veuillez vous reconnecter.";
}
?>