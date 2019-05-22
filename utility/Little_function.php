<?php
class Little_function {

    function name_exist($array, $search) {
        foreach($array as $key => $value) {
            if ($key === $search) {
                return true;
            }
        }
    }

    function increment_args($argv, $begin) {
        $replace = $argv[$begin];
                for ($i = $begin+1; $i < count($argv); $i++) {
                    $replace .= " ".$argv[$i];
                }
        return $replace;
    }

    function list_key($array) {
        foreach($array as $key => $value) {
            echo " - $key \n";
        }
        echo "\n";
    }

    function list_value($array) {
        foreach($array as $key => $value) {
            echo " - $value \n";
        }
        echo "\n";
    }

    function error($argv, $message = '') {
        $command = null;
        for($i = 1; $i < count($argv); $i++) {
            $command .= $argv[$i]." ";
        }
        echo "\n";
        echo $this->show_in_color("Commande inexistante : ". $command, "bleu");
        echo "\n\n";
        if ($message !== '') {
            echo $message;
            echo "\n";
        }
    }

    function show_commandes() {
        echo "\n";
        echo "LES COMMANDES\n";
        echo "\n";
        echo "COMMANDES                     ==> Montre cette liste\n\n";
        echo "TEMPS +race                   ==> Affiche le temps pour l'accouchement\n";
        echo "                                  Ex: TEMPS pourpre\n\n";
        echo "PARCHEMIN +race               ==> Le parchemin obtenu pour la dragodindes\n";
        echo "                                  Ex: PARCHEMIN pourpreivoire\n\n";
        echo "LIST +whatToList              ==> Affiche list complete de l'element choisi\n";
        echo "                                  Ex: LIST temps\n\n";
        echo "CRAFT +category +item         ==> Liste les ressources necessaire pour le craft\n";
        echo "                                  Ex: CRAFT clef|caresseur craqueleur legendaire\n\n";
        echo "ADD +choice +what +value      ==> Rajoute un element\n";
        echo "                                  Ex: Add accouchement pourpre ivoire dd/mm 12:00\n";
        echo "                                  Ex: Add infos bonta txt txt\n\n";
        echo "TOCOME                        ==> Affiche les accouchements a venir\n\n";
        echo "INFOS                         ==> Affiche les informations\n\n";
        echo "SEARCH parchemin +what +what  ==> Recherche les parchemins correspondant\n";
        echo "                                  Ex: SEARCH parchemin petit force";
        echo "\n";
    }

    function show_command($argv) {
        for ($i = 1; $i < count($argv); $i++) {
            $command .= $argv[$i]." ";
        }
        echo "\n".$this->show_in_color("Commande : ".$command, "bleu")."\n\n";
    }

    function align_text($array, $argv = []) {
        if (count($argv) > 0) {
            foreach($array as $key => $value) {
                $len = strlen($array[$key]['name']) > $len ? strlen($array[$key]['name']) : $len;
            }
            foreach($array as $key => $value) {
                $complete = '';
                $arg = strtolower($argv[2]) == "temps" ? 'accouchement' : strtolower($argv[2]);
                $what = $array[$key][$arg];
                $name = $array[$key]['name'];
                echo "\n";
                if (strlen($key < $len)) {
                    for ($i = strlen($array[$key]['name']); $i < $len; $i++) {
                        $complete .= " ";
                    }
                }
                echo $name . $complete . " ==> " . $what;
                echo $list === false ? "\n" : null;
            }
            echo "\n";
        }
        else {
            foreach($array as $key => $value) {
                $len = strlen($key) > $len ? strlen($key) : $len;
            }
            foreach($array as $key => $value) {
                $complete = '';
                if (strlen($key < $len)) {
                    for ($i = strlen($key); $i < $len; $i++) {
                        $complete .= " ";
                    }
                }
                echo $key.$complete." ==> ".$value."\n";
            }
        }
    }

    function diff_date($date, $date_base) {
        if ($date->format('d') != $date_base->format('d')) {
            $h = (24 - $date_base->format('H')) + $date->format('H');
        }
        else {
            $h = $date->format('H') - $date_base->format('H');
        }
        
        $d = $date->format('d') - $date_base->format('d');
        $h = substr($h, 0, 1) == "-" ? substr($h, 1, strlen($h)) : $h;
        return [$d, $h];
    }

    function align_text_multiply($array, $multiplicator = 1) {
        foreach($array as $key => $value) {
            $len = strlen($key) > $len ? strlen($key) : $len;
        }
        foreach($array as $key => $value) {
            $complete = '';
            if (strlen($key < $len)) {
                for ($i = strlen($key); $i < $len; $i++) {
                    $complete .= " ";
                }
            }
            if ($key == 'Efficacite' || $key == 'Utilisations') {
                echo $key.$complete." ==> ".$value."\n";
            }
            else {
                echo $key.$complete." ==> ".$value * $multiplicator."\n";
            }
        }
    }

    function take_choices($array) {
        $choice = "";
        foreach ($array as $key => $value) {
            $choice .= " ".$key." |";
        }
        $choice = substr($choice, 0, strlen($choice)-1);
        return $choice;
    }

    function show_sort_dd($contentsDecoded) {
        foreach($contentsDecoded['accouchement'] as $key => $value) {
            $len = strlen($key) > $len ? strlen($key) : $len;
            $day =  substr($value, strpos($value, "/")-2, 2);
            $month = substr($value, strpos($value, "/")+1, 2);
            $hour = substr($value, strpos($value, "/")+4, 2);
            $date = new DateTime();
            $date->setDate(2018, $month, $day);
            $date->setTime($hour, 00);
            $tab[$key] = strtotime("2018/".$month."/".$day." ".$hour.":00");
        }
        asort($tab);
        foreach($tab as $key => $value) {
            $complete = '';
            if (strlen($key < $len)) {
                for ($i = strlen($key); $i < $len; $i++) {
                    $complete .= " ";
                }
            }
            echo $key.$complete." ==> ".$contentsDecoded['accouchement'][$key]."\n";
        }
    }

    function show_in_color($value, $color) {
        $txt = "\e";
        if ($color == "rouge") {
            $txt .= "[1;31m".$value."\e[1;37m";
        }
        elseif ($color == "vert") {
            $txt .= "[1;32m" .$value."\e[1;37m";
        }
        elseif ($color == "bleu clair") {
            $txt .= "[1;36m" .$value."\e[1;37m";
        }
        elseif ($color == "bleu") {
            $txt .= "[1;34m" .$value."\e[1;37m";
        }
        elseif ($color == "jaune") {
            $txt .= "[1;33m" .$value."\e[1;37m";
        }
        return $txt;
    }

}