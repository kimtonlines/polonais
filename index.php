<?php

require 'vendor/autoload.php';

use Slim\App;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;

$app = new App();

$containerdb1 = $app->getContainer();
$containerdb1['db1'] = function () {

    $db1 = new PDO('mysql:host=localhost;dbname=base1', 'kimt', '1992');
    $db1->exec('SET NAMES UTF8');
    $db1->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $db1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $db1;
};

$containerdb2 = $app->getContainer();
$containerdb2['db2'] = function () {

    $db2 = new PDO('mysql:host=localhost;dbname=base2', 'kimt', '1992');
    $db2->exec('SET NAMES UTF8');
    $db2->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $db2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $db2;
};

/*
 * Met toutes les temps en milliseconde
 */
$app->get('/rallye/pilotes', function () {

    $requete = $this->db1->query('SELECT *  FROM temps WHERE depart IS NOT NULL AND arrivee IS NOT NULL');

    $timeAll = $requete->fetchAll();

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
        $requete2 = $this->db1->prepare('UPDATE temps SET temps = :temps WHERE id = :id');

        $requete2->bindParam('temps', $temps);
        $requete2->bindParam('id', $time->id);

        $requete2->execute();
    }
});

/*
 * Met le temps du pilote dans une autre base de données en fonction de son id et de l'id de la spéciale
 */
$app->get('/rallye/pilotes/{piloteId}/{specialeId}', function (Request $req, Response $res, $args){

    $piloteId = $args['piloteId'];
    $specialeId = $args['specialeId'];

    if ($specialeId > 2 || $specialeId < 1){
        return false;
    }
        /*
         * Selection le temps en fonction des parametres
         */
        $requete1 = $this->db1->prepare('SELECT *  FROM temps WHERE id_pilote = :id_pilote AND id_speciale = :id_speciale AND depart IS NOT NULL AND arrivee IS NOT NULL');

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
            $date = substr_replace( $time->depart, '', 10, 9);
            $a = substr_replace($date, '', 4, 6);
            $m = substr_replace($date, '', 0, 5);
            $j = substr_replace($m, '', 0, 3);
            $m = substr_replace($m, '', 2, 3);
            $dates = $j.'/'.$m.'/'.$a;

            $requete = $this->db2->prepare('SELECT *  FROM temps WHERE id_pilote = :id_pilote AND id_speciale = :id_speciale AND dates = :dates');

            $requete->bindParam('id_pilote', $piloteId, PDO::PARAM_INT);
            $requete->bindParam('id_speciale', $specialeId, PDO::PARAM_INT);
            $requete->bindParam('dates', $dates);

            $requete->execute();
            $temsPilote = $requete->fetch();

           if ($temsPilote) {
               return false;
           } else {
               /*
           * Insertion des données dans la base de données base2
           */
               $requete2 = $this->db2->prepare('INSERT INTO temps(id_pilote, id_speciale, dates, temps, depart, arrivee) VALUES(:id_pilote, :id_speciale, :dates, :temps, :depart, :arrivee)');

               $requete2->bindParam('id_pilote', $piloteId, PDO::PARAM_INT);
               $requete2->bindParam('id_speciale', $specialeId, PDO::PARAM_INT);
               $requete2->bindParam('dates', $dates);
               $requete2->bindParam('temps', $temps);
               $requete2->bindParam('depart', $td);
               $requete2->bindParam('arrivee', $tempsA);

               $requete2->execute();
           };
        }

});

$app->run();