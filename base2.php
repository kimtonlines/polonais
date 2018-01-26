<?php

/*
 * Cette fonction, met le temps d'un pilote en milliseconde dans une nouvelle base de données
 */

/**
 * @param $piloteId
 * @param $specialeId
 * @return bool
 */
function base2($piloteId, $specialeId){

    /*
     * Connexion à la base de données base1
     */
    $db1 = new PDO('mysql:host=localhost;dbname=base1', 'kimt', '1992');
    $db1->exec('SET NAMES UTF8');
    $db1->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $db1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /*
     * Selection le temps en fonction des parametres
     */
    $requete1 = $db1->prepare('SELECT *  FROM temps WHERE id_pilote = :id_pilote AND id_speciale = :id_speciale AND depart IS NOT NULL AND arrivee IS NOT NULL');

    $requete1->bindParam('id_pilote', $piloteId, PDO::PARAM_INT);
    $requete1->bindParam('id_speciale', $specialeId, PDO::PARAM_INT);

    $requete1->execute();
    $time = $requete1->fetch();

    /*
     * Remplace les caractères allant de 0 jsuqu'a 11 et les met au format date
     */
    $td = date('H:i:s', strtotime(( substr_replace( $time->depart, '', 0, 11))));
    $ta = date('H:i:s', strtotime(( substr_replace( $time->arrivee, '', 0, 11))));

    /*
     * Met en milliseconde
     */
    $ams = $time->ams / 1000;

    /*
     * Met au format datetime le temps de départ et le temps d'arrivée
     */
    $ttd = new DateTime($td);
    $tta = new DateTime($ta);

    /*
     * Fait la différence entre le temps de départ et le temps d'arrivée
     */
    $diff = $ttd->diff($tta);

    /*
     *  Met en milliseconde les minutes et les secondes de la différence
     */
    $i = $diff->i * 60000;
    $s = $diff->s * 1000;

    /*
     * Additionne en milliseconde les minutes, les secondes et les millisecondes
     */
    $temps =$i + $s + $ams;

    if ($temps) {

        $tempsA = $ta.':'.$ams;
        /*
         * Connexion à la base de données base2 si la variable $temps existe
         */
        $db2 = new PDO('mysql:host=localhost;dbname=base2', 'kimt', '1992');
        $db2->exec('SET NAMES UTF8');
        $db2->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $db2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /*
         * Insertion des données dans la base de données base2
         */
        $requete2 = $db2->prepare('INSERT INTO temps(id_pilote, id_speciale, temps, depart, arrivee) VALUES(:id_pilote, :id_speciale, :temps, :depart, :arrivee)');

        $requete2->bindParam('id_pilote', $piloteId, PDO::PARAM_INT);
        $requete2->bindParam('id_speciale', $specialeId, PDO::PARAM_INT);
        $requete2->bindParam('temps', $temps);
        $requete2->bindParam('depart', $td);
        $requete2->bindParam('arrivee', $tempsA);

        $rallye = $requete2->execute();
    }

    return $rallye;
};

base2(11, 1);