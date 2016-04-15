<?php
// Classe représentant un jeu
    class Jeu {
        //Identifiant technique
        var $id;
        // Nom du jeu
        var $nom;
        
        // Constructeur
        function Jeu ($nomIn) {
            $this->nom = $nomIn;
        }
        
		// Renvoi le jeu au format JSON
        function getJeuJSON() {
            return json_encode($this);
        }
    }
?>