<?php
// Classe représentant un evenement
    class Evenement {
        // Identifiant technique
        var $id;
        // Nom de l'événement
        var $nom;
        // Description de l'événement
        var $desc;
        // Date de début l'événement
        var $dateDebut;
        // Heure de début de l'événement
        var $heureDebut;
		// Date de fin l'événement
        var $dateFin;
        // Heure de fin de l'événement
        var $heureFin;
        // Jeu associé
        var $jeu;
		// Type de l'évent
		var $type;
        // Liste des participants
        var $listParticipants;
        
        function Evenement ($nomIn, $dateIn) {
            $this->nom = $nomIn;
            $this->dateDebut = $dateIn;
        }
        
		// Renvoi l'événement au format JSON
        function getEventJSON() {
            return json_encode($this);
        }
		
		// Fonction qui permet de retourne la date et l'heure de début de l'événement au format iso
		function getDateDebutIso() {
			$tmp = explode("/", $this->dateDebut);
			$date_iso = $tmp[2]."-".$tmp[1]."-".$tmp[0];
			if(isset($this->heureDebut)) {
				$date_iso = $date_iso.' '.$this->heureDebut;
			} else {
				$date_iso = $date_iso.' 00:00:00';
			}
			
			return $date_iso;
		}
		
		// Fonction qui permet de retourne la date et l'heure de fin de l'événement au format iso
		function getDateFinIso() {
			$tmp = explode("/", $this->dateFin);
			$date_iso = $tmp[2]."-".$tmp[1]."-".$tmp[0];
			if(isset($this->heureFin)) {
				$date_iso = $date_iso.' '.$this->heureFin;
			} else {
				$date_iso = $date_iso.' 00:00:00';
			}
			
			return $date_iso;
		}
		
    }
?>