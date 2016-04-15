<?php
// Classe représentant un type d'un événement
    class TypeEvt {
        // Identifiant technique
        var $id;
		// Code fonctionnel du type
		var $code;
        // Nom du type
        var $nom;
		
		//Constructeur
		function TypeEvt($idIn, $codeIn, $nomIn) {
			$this->id = $idIn;
			$this->code = $codeIn;
			$this->nom = $nomIn;
		}
    }
?>