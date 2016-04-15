<?php
// Classe représentant un joueur
    class Joueur {
        // Identifiant technique
        var $id;
        // Nom du joueur
        var $nom;
		// Droit du joueur
		var $droit
		
		//Constructeur
		function Joueur($idIn, $nomIn, $droitIn) {
			$this->id = $idIn;
			$this->droit = $droitIn;
			$this->nom = $nomIn;
		}
		
		// Renvoi l'événement au format JSON
        function getUserJSON() {
            return json_encode($this);
        }
    }
?>