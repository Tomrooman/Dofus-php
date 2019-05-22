<?php
class Dofus_add {

    public $helper;
    public $what;
    public $replace;

    function __construct($object) {
        $this->helper = $object;
    }

    function add($argv, $dragodindes) {
        if (isset($argv[2]) && !empty($argv[2])) {
            $contents = file_get_contents("./My_informations.json");
            $contentsDecoded = json_decode($contents, true);
            if ($this->helper->name_exist($contentsDecoded, $argv[2]) || $argv[2] == 'dragodinde') {
                if (isset($argv[3]) && !empty($argv[3])) {
                    $check = false;
                    if ($argv[2] === 'accouchement') {
                        $check = $this->add_accouchement($argv, $dragodindes);
                        $category = $argv[2];
                    } 
                    elseif ($argv[2] == "dragodinde") {
                        $dd = isset($argv[4]) ? $argv[3]." et ".$argv[4] : $argv[3];
                        $this->helper->show_command($argv);
                        if ($this->helper->name_exist($dragodindes, isset($argv[4]) ? $argv[3].$argv[4] : $argv[3])) {
                            $contentsDecoded["Mes dragodindes"][$dd] = true;
                            file_put_contents(substr($argv[0], 0, strlen($argv[0])-9).'./My_informations.json', json_encode($contentsDecoded));
                            echo "\nMES DRAGODINDES\n\n";
                            $this->helper->list_key($contentsDecoded["Mes dragodindes"]);
                            echo $this->helper->show_in_color("\n\nDragodinde ".strtoupper($dd).' rajoutee avec succes !'."\n", "vert");
                        }
                        else {
                            echo $this->helper->show_in_color("\nLa dragodinde ".strtoupper($dd)." n'existe pas !\n\n", 'rouge');
                        }
                    }
                    else {
                        if (isset($argv[4]) && !empty($argv[4])) {
                            $this->replace = $this->helper->increment_args($argv, 4);
                            $this->what = $argv[3];
                            $category = $argv[2];
                            $check = true;
                        }
                        else {
                            $this->helper->error($argv, $this->helper->show_in_color('Precisez la valeur a rajouter', "rouge"));
                        }
                    }
                    if ($check) {
                        $contentsDecoded[$category][ucfirst($this->what)] = ucfirst($this->replace);
                        $this->helper->show_command($argv);
                        echo "\n".strtoupper($argv[2])."\n\n";
                        $argv[2] == "infos" ? $this->helper->align_text($contentsDecoded[$argv[2]]) : $this->helper->show_sort_dd($contentsDecoded);;
                        file_put_contents(substr($argv[0], 0, strlen($argv[0])-9).'./My_informations.json', json_encode($contentsDecoded));
                        echo $this->helper->show_in_color("\n\n".strtoupper($this->what).' rajouter avec succes !'."\n", "vert");
                    }
                }
                else {
                    $this->helper->error($argv, $this->helper->show_in_color('Precisez la categorie a rajouter', 'rouge'));
                }
            }
            else {
                $this->helper->error($argv, $this->helper->show_in_color('Choix possible : accouchement | infos | dragodinde', 'rouge'));
            }
            
        }
        else {
            $this->helper->error($argv, $this->helper->show_in_color("Precisez le type de la chose a ajouter : accouchement | infos", 'rouge'));
        }
        
    }

    function add_accouchement($argv, $dragodindes) {
        if (count($argv) >= 5) {            
            $if_exist = $this->helper->name_exist($dragodindes, $argv[3].$argv[4]) || $this->helper->name_exist($dragodindes, $argv[3]);
            if ($if_exist) { 
                if ($this->helper->name_exist($dragodindes, $argv[3].$argv[4])) {
                    $this->what = "Dragodinde ".$argv[3]." et ".$argv[4];
                    $this->replace = $this->helper->increment_args($argv, 5);
                }
                else {
                    $this->what = "Dragodinde ".$argv[3];
                    $this->replace = $this->helper->increment_args($argv, 4);
                }
                return true;
            }
            else {

                $this->helper->error($argv, $this->helper->show_in_color('"Dragodinde '.$argv[3].'" OU "Dragodinde '.$argv[3].' et '.$argv[4].'" n\'existe pas', 'rouge'));
            }
        }
        else {
            $if_exist = $this->helper->name_exist($dragodindes, $argv[3]);
            if ($if_exist) {
                $this->what = "Dragodinde ".$argv[3];
                if (isset($argv[4]) && !empty($argv[4])) {
                    $this->replace = $this->helper->increment_args($argv, 4);
                    return true;
                }
                else {
                    $this->helper->error($argv, $this->helper->show_in_color("Precisez la valeur a rajouter", "rouge"));
                }
            }
            else {
                $this->helper->error($argv, $this->helper->show_in_color("Dragodinde ".$argv[3]." n'existe pas", 'rouge'));
            }
        }
    }
}