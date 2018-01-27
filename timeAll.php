<?php
/*
 * Cette fonction, met le temps de tous les pilotes en milliseconde
 */
function timeAll(){

    $db = new PDO('mysql:host=localhost;dbname=base1', 'kimt', '1992');
    $db->exec('SET NAMES UTF8');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $requete1 = $db->query('SELECT *  FROM temps WHERE depart IS NOT NULL AND arrivee IS NOT NULL');

    $timeAll = $requete1->fetchAll();

    foreach ($timeAll as $time) {

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

        /*
         * Met à jour le temps des pilotes en milliseconde
         */
        $requete2 = $db->prepare('UPDATE temps SET temps = :temps WHERE id = :id');

        $requete2->bindParam('temps', $temps);
        $requete2->bindParam('id', $time->id);

        $rallye = $requete2->execute();
    }

    return $rallye;
};

timeAll();