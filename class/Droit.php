<?php
// Classe représentant un droit pour un joueur
    class Droit {
        // Identifiant technique
        var $id;
        // Nom du droit
        var $nom;
		
		//Constructeur
		function Droit($idIn, $nomIn) {
			$this->id = $idIn;
			$this->nom = $nomIn;
		}
    }
?>