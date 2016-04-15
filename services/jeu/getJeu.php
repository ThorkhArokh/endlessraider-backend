<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/class/Jeu.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/persistance/jeuDao.php');

if(isset($_GET['idJeu'])) {	
	$jeu = getJeuById($_GET['idJeu']);
	echo $jeu->getJeuJSON();
} else {
	echo "Aucun jeu trouvé";
}

?>