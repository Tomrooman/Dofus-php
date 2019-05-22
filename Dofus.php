<?php
include('Dofus_infos.php');
include('utility/Add.php');
include('utility/Tellme.php');
include('utility/Little_function.php');
class Dofus extends Dofus_infos {

/*
substr(PHP_OS, 0, 3) == "WIN";
*/

    public $helper;
    public $Add;
    public $Tellme;

    function menu($argv) {
        $this->helper = new Little_function;
        $this->Add = new Dofus_add($this->helper);
        $this->Tellme = new Tellme($this->helper);
        if (strtolower($argv[1]) == "temps") {
            $this->temps($argv);
        }
        else if (strtolower($argv[1]) == "commandes" || strtolower($argv[1]) == "help") {
            $this->helper->show_commandes();
        }
        else if (strtolower($argv[1]) == "list") {
            $this->list($argv);
        }
        else if (strtolower($argv[1]) == "parchemin") {
            $this->parchemin($argv);
        }
        else if (strtolower($argv[1]) == "search") {
            $this->search($argv);
        }
        else if (strtolower($argv[1]) == "infos") {
            $this->infos($argv);
        }
        else if (strtolower($argv[1]) == "add") {
            $this->Add->add($argv, $this->dragodindes);
        }
        else if (strtolower($argv[1]) == "tocome") {
            $this->tocome($argv);
        }
        else if (strtolower($argv[1]) == "remove") {
            $this->remove($argv);
        }
        else if (strtolower($argv[1]) == "craft") {
            $this->craft($argv);
        }
        else if (strtolower($argv[1]) == "tellme") {
            $this->Tellme->tellme($argv, $this->dragodindes);
        }
        else if (strtolower($argv[1]) == "last") {
            $this->last($argv);
        }
        else if (strtolower($argv[1]) == "dragodinde") {
            $this->my_dragodindes($argv);
        }
        else {
            $this->helper->error($argv);
        }
    }

    function last($argv) {
        if (isset($argv[2]) && !empty($argv[2])) {
            $contents = file_get_contents("./My_informations.json");
            $contentsDecoded = json_decode($contents, true);
            $dd = isset($argv[3]) ? $argv[2].$argv[3] : $argv[2];
            $dd_show = isset($argv[3]) ? $argv[2]." et ".$argv[3] : $argv[2];

            if ($this->helper->name_exist($this->dragodindes, $dd)) {
                $date = new DateTime();
                $date->setDate(Date('Y'), Date('m'), Date('d'));
                $date->setTime(Date('H') + 2, Date('i'));
                $contentsDecoded['last'][$dd_show] = $date;
                file_put_contents("./My_informations.json", json_encode($contentsDecoded));
                $this->show_last($argv);
                echo $this->helper->show_in_color('La dragodinde '.strtoupper($dd_show)." a ete rajoutee avec succes\n", 'vert');
            }
            else {
                $this->helper->error($argv, $this->helper->show_in_color("La dragodinde ".strtoupper($dd_show)." n'existe pas !", 'rouge'));
            }
        }
        else {
            $this->helper->error($argv, $this->helper->show_in_color('Precisez quelle est la derniere dragodinde fecondee !', 'rouge'));
        }
    }

    function my_dragodindes($argv) {
        $contents = file_get_contents("./My_informations.json");
        $contentsDecoded = json_decode($contents, true);
        $this->helper->show_command($argv);
        if ($contentsDecoded['Mes dragodindes']) {
            echo "\nMES DRAGODINDES \n\n";
            $this->helper->list_key($contentsDecoded['Mes dragodindes']);
        }
        else {
            echo $this->helper->show_in_color("\nAucune dragodindes !\n\n", 'rouge');
        }
    }

    function craft($argv) {
        if (isset($argv[2]) && !empty($argv[2])) {
            if ($this->helper->name_exist($this->craft, $argv[2])) {
                if (isset($argv[3]) && !empty($argv[3])) {
                    if ($this->craft[$argv[2]][$argv[3]] || $this->craft[$argv[2]][$argv[3]." ".$argv[4]]) {
                        $item = $this->craft[$argv[2]][$argv[3]." ".$argv[4]] ? $argv[3]." ".$argv[4] : $argv[3];
                        $multiplicator = $this->craft[$argv[2]][$argv[3]." ".$argv[4]] && count($argv) == 6 ? $argv[5] : 1;
                        if ($multiplicator == 1) {
                            $multiplicator = $this->craft[$argv[2]][$argv[3]] && count($argv) == 5 ? $argv[4] : 1;
                        }
                        $this->helper->show_command($argv);
                        echo "\n";
                        $string =  $multiplicator > 1 ? $argv[2]." ".$item." X$multiplicator"."\n\n" : $argv[2]." ".$item."\n\n";
                        echo strtoupper($string);
                        $this->helper->align_text_multiply($this->craft[$argv[2]][$item], $multiplicator);
                    }
                    else {
                        if (isset($argv[4]) && !empty($argv[4])) {
                            $this->helper->error($argv, $this->helper->show_in_color(strtoupper($argv[2]).' "'.$argv[3]." ".$argv[4].'" n\'existe pas'."\n", 'rouge'));
                            $this->helper->list_key($this->craft[$argv[2]]);
                        }
                        else {
                            $this->helper->error($argv, $this->helper->show_in_color(strtoupper($argv[2]).' "'.$argv[3].'" n\'existe pas'."\n", 'rouge'));
                            $this->helper->list_key($this->craft[$argv[2]]);
                        }
                    }
                }
                else {
                    $this->helper->error($argv, $this->helper->show_in_color("Precisez l'element dont vous voulez voir le craft\n", 'rouge'));
                    echo strtoupper($argv[2])."\n\n";
                    $this->helper->list_key($this->craft[$argv[2]]);
                }
            }
            else {
                $choice = $this->helper->take_choices($this->craft);
                $this->helper->error($argv, $this->helper->show_in_color("Choix possible :$choice", "rouge"));
            }
        }
        else {
            $choice = $this->helper->take_choices($this->craft);
            $this->helper->error($argv, $this->helper->show_in_color("Precisez la categorie, choix possible : ".$choice, "rouge"));
        }
    }

    function remove($argv) {
        if (isset($argv[2]) && !empty($argv[2])) {
            if ($argv[2] == "infos" || $argv[2] == 'accouchement' || $argv[2] == 'dragodinde'
            || $argv[2] == "last") {
                $contents = file_get_contents("./My_informations.json");
                $contentsDecoded = json_decode($contents, true);
                if (isset($argv[3]) && !empty($argv[3])) {
                    if ($argv[2] == 'infos') {
                        if ($contentsDecoded[$argv[2]][$argv[3]]) {
                            unset($contentsDecoded[$argv[2]][$argv[3]]);
                            file_put_contents("./My_informations.json", json_encode($contentsDecoded));
                            $this->infos($argv);
                            echo $this->helper->show_in_color("\n".'Infos '.strtoupper($argv[3]).' supprimer avec succes !'."\n", "vert");
                        }
                        else {
                            $this->helper->error($argv, $this->helper->show_in_color('Infos "'.$argv[3].'" n\'existe pas', "rouge"));
                        }

                    }
                    elseif ($argv[2] == 'dragodinde') {
                        $dd = isset($argv[4]) ? $argv[3]." et ".$argv[4] : $argv[3];
                        if ($contentsDecoded['Mes dragodindes'][$dd]) {
                            unset($contentsDecoded['Mes dragodindes'][$dd]);
                            file_put_contents("./My_informations.json", json_encode($contentsDecoded));
                            $this->my_dragodindes($argv);
                            echo $this->helper->show_in_color("\n".'Dragodinde '.strtoupper($dd).' supprimee avec succes !'."\n", "vert");
                        }
                        else {
                            echo $this->helper->show_in_color('La dragodinde '.strtoupper($dd).' n\'est pas dans la liste'."\n", 'rouge');
                        }
                        
                    }
                    elseif ($argv[2] == 'last') {
                        $dd = isset($argv[4]) ? $argv[3]." et ".$argv[4] : $argv[3];
                        if ($contentsDecoded['last'][$dd]) {
                            unset($contentsDecoded['last'][$dd]);
                            file_put_contents("./My_informations.json", json_encode($contentsDecoded));
                            $this->show_last($argv);
                            echo $this->helper->show_in_color("\n".'Dragodinde '.strtoupper($dd).' supprimee avec succes !'."\n", "vert");
                        }
                        else {
                            $this->helper->show_command($argv);
                            echo $this->helper->show_in_color('La dragodinde '.strtoupper($dd).' n\'est pas dans la liste'."\n", 'rouge');
                        }
                    }
                    else {
                        if ($this->helper->name_exist($this->dragodindes, $argv[3].$argv[4]) ||  $this->helper->name_exist($this->dragodindes, $argv[3])) {
                            if (isset($argv[4]) && !empty($argv[4])) {
                                $dd = $this->helper->name_exist($this->dragodindes, $argv[3].$argv[4]) ? "Dragodinde ".$argv[3]." et ".$argv[4] : "Dragodinde ".$argv[3];
                            } else {
                                $dd = "Dragodinde ".$argv[3];
                            }
                            if ($contentsDecoded[$argv[2]][$dd]) {
                            unset($contentsDecoded[$argv[2]][$dd]);
                            file_put_contents("./My_informations.json", json_encode($contentsDecoded));
                            $this->tocome($argv);
                            echo $this->helper->show_in_color("\n".'Accouchement '.strtoupper($dd).' supprimer avec succes !'."\n", "vert");
                            }
                            else {
                                $this->helper->error($argv, $this->helper->show_in_color("Accouchement $dd n'existe pas",'rouge'));
                            }
                        }
                        else {
                            $this->helper->error($argv, $this->helper->show_in_color(!empty($argv[4]) ? "Dragodinde ".$argv[3]." et ".$argv[4]." n'existe pas" : "Dragodinde ".$argv[3]." n'existe pas", "rouge"));
                        }
                    }
                }
                else {
                    $this->helper->error($argv, $this->helper->show_in_color('Precisez l\'element a supprimer', "rouge"));
                }
            }
            else {
                $this->helper->error($argv, $this->helper->show_in_color('Choix de categorie possible : infos | accouchement | dragodinde', "rouge"));
            }
        } else {
            $this->helper->error($argv, $this->helper->show_in_color('Precisez la categorie de l\'element a supprimer : infos | accouchement', "rouge"));
        }
    }

    function tocome($argv) {
        $contents = file_get_contents("./My_informations.json");
        $contentsDecoded = json_decode($contents, true);
        $this->helper->show_command($argv);
        $len = 0;
        $tab = [];
        if ($contentsDecoded['accouchement']) {
            echo "\nACCOUCHEMENT\n\n";
            $this->helper->show_sort_dd($contentsDecoded);
            echo "\n";
        }
        else {
            echo $this->helper->show_in_color("\nAucun accouchement de prevu !\n", 'rouge');
        }
    }

    function infos($argv) {
        $contents = file_get_contents("./My_informations.json");
        $contentsDecoded = json_decode($contents, true);
        $this->helper->show_command($argv);
        echo "INFOS";
        echo "\n\n";
        if ($contentsDecoded['infos']) {
            $this->helper->align_text($contentsDecoded['infos']);
        }
        else {
            echo $this->helper->show_in_color('Infos est vide !', 'rouge');
        }
    }

    function show_last($argv) {
        $contents = file_get_contents("./My_informations.json");
        $contentsDecoded = json_decode($contents, true);
        $this->helper->show_command($argv);
        echo "\n";
        if ($contentsDecoded['last']) {
            echo "LAST\n\n";
            $this->helper->list_key($contentsDecoded['last']);
        }
        else {
            echo $this->helper->show_in_color("Aucune dragodinde dernierement fecondee\n\n", 'rouge');
        }
    }

    function list($argv) {
        if (isset($argv[2]) && !empty($argv[2])) {
            if (strtolower($argv[2]) == "temps" || strtolower($argv[2]) == "parchemin"
            || strtolower($argv[2]) == "craft" || $this->helper->name_exist($this->craft, $argv[2])
            || $argv[2] == "last") {
                if (strtolower($argv[2]) == "temps" || strtolower($argv[2]) == "parchemin") {
                    $this->helper->show_command($argv);
                    $this->helper->align_text($this->dragodindes, $argv);
                }
                elseif ($argv[2] == "last") {
                    $this->show_last($argv);
                }
                else {
                    $this->helper->show_command($argv);
                    echo "\n";
                    if (strtolower($argv[2]) == "craft") {
                        foreach($this->craft as $key => $values) {
                            echo strtoupper($key)."\n\n";
                            foreach($values as $key_name => $value) {
                                echo " - ".$key_name."\n";
                            }
                            echo "\n\n";
                        }
                    }
                    else {
                        echo strtoupper($argv[2])."\n\n";
                        foreach ($this->craft[$argv[2]] as $key => $value) {
                            echo " - ".$key."\n";
                        }
                    }
                }
            }
            else {
                $choice = "";
                foreach ($this->craft as $key => $value) {
                    $choice .= "| ".$key." ";
                }
                $this->helper->error($argv, $this->helper->show_in_color("Choix possible : temps | parchemin | last | craft ".$choice, "rouge"));
            }
        }
        else {
            $choice = "";
                foreach ($this->craft as $key => $value) {
                    $choice .= "| ".$key." ";
                }
            $this->helper->error($argv, $this->helper->show_in_color("Veuillez preciser la chose a lister : temps | parchemin | last | craft ".$choice, "rouge"));
        }
    }

    function search($argv) {
        if (isset($argv[2]) && !empty($argv[2])) {
            if ($argv[2] == "parchemin") {
                if (isset($argv[3]) && !empty($argv[3])) {
                    $infos = [];
                    if (isset($argv[4]) && !empty($argv[4])) {
                        foreach ($this->dragodindes as $key => $value) {
                            if (preg_match("/\b".$argv[3]."\b/i", $value['parchemin']) && preg_match("/\b".$argv[4]."\b/i", $value['parchemin'])) {
                                $infos[$value["name"]] = $value['parchemin'];
                            }
                        }
                    }
                    else {
                        foreach ($this->dragodindes as $key => $value) {
                            if (preg_match("/\b".$argv[3]."\b/i", $value['parchemin'])) {
                                $infos[$value["name"]] = $value['parchemin'];
                            }
                        }
                    }
                    if (count($infos)) {
                        $this->helper->show_command($argv);
                        $this->helper->align_text($infos);
                    }
                    else {
                        $this->helper->error($argv, $this->helper->show_in_color('Verifier la sorte de parchemin recherche', "rouge"));
                    }
                }
                else {
                    $this->helper->error($argv, $this->helper->show_in_color("Merci de preciser le parchemin a rechercher", "rouge"));
                }
            }
            else {
                $this->helper->error($argv, $this->helper->show_in_color("Recherche autorisee : parchemin", "rouge"));
            }
        }
        else {
            $this->helper->error($argv, $this->helper->show_in_color("Veuillez preciser la chose à rechercher", "rouge"));
        }
    }

    function parchemin($argv) {
        if (isset($argv[2]) && !empty($argv[2])) {
            if ($this->helper->name_exist($this->dragodindes, $argv[2]) || $this->helper->name_exist($this->dragodindes, $argv[2].$argv[3])) {
                $what = $this->helper->name_exist($this->dragodindes, $argv[2].$argv[3]) ? $argv[2].$argv[3] : $argv[2];
                $parcho = $this->dragodindes[$what]['parchemin'];
                $name = $this->dragodindes[$what]['name'];
                $this->helper->show_command($argv);
                echo $name . " ==> " . $parcho;
                echo $list === false ? "\n" : null;
                echo "\n";
            }
            else {
                $this->helper->error($argv, $this->helper->show_in_color('La dragodinde n\'existe pas', "rouge"));
            }
        }
        else {
            $this->helper->error($argv, $this->helper->show_in_color("Veuillez preciser une race de dragodinde", "rouge"));
        }
    }

    function temps($argv) {
        if (isset($argv[2]) && !empty($argv[2])) {
            $check = false;
                if (count($argv) >= 4) {
                    $time = $this->dragodindes[$argv[2].$argv[3]]['accouchement'];
                    $check = $this->helper->name_exist($this->dragodindes, $argv[2].$argv[3]);
                    $name = $this->dragodindes[$argv[2]]['name'];
                    $dd_show = $argv[2]." ".$argv[3];
                    $dd_selected = $argv[2].$argv[3];
                }
                if (!$check) {
                    $check = $this->helper->name_exist($this->dragodindes, $argv[2]);
                    $dd_show = $argv[2];
                    $dd_selected = $argv[2];
                }
                if ($check) {
                    $time = $this->dragodindes[$dd_selected]['accouchement'];
                    $name = $this->dragodindes[$dd_selected]['name'];
                    $this->helper->show_command($argv);
                    echo $name . " ==> " . $time;
                    echo "\n";
                }
                else {
                    $this->helper->error($argv, $this->helper->show_in_color(!empty($argv[3]) ? "La dragodinde ".$argv[2]." ou ".$argv[2]." ".$argv[3]." n'existe pas" : "La dragodinde ".$argv[2]." n'existe pas", 'rouge'));
                }
        }
        else {
            $this->helper->error($argv, $this->helper->show_in_color("Veuillez precier une race de dragodinde", "rouge"));
        }
    }
}
$Dofus = new Dofus;
$Dofus->Menu($argv);