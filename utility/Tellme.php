<?php
class Tellme {

    public $helper;

    function __construct($object) {
        $this->helper = $object;
    }

    function tellme($argv, $dragodindes) {
        $contents = file_get_contents("./My_informations.json");
        $contentsDecoded = json_decode($contents, true);
        $time;
        $this->helper->show_command($argv);
        $last;
        $bigger;
        $check = false;
        if ($contentsDecoded['last']) {
            foreach ($contentsDecoded["last"] as $key => $value) {
                echo $this->helper->show_in_color('Derniere dragodinde fecondee : '.strtoupper($key), "vert")."\n\n";
                if (count(explode(" ", $key)) >= 3) {
                    $last = explode(" ", $key)[0].explode(" ", $key)[2];
                }
                else {
                    $last = $key;
                }
                $bigger = substr($dragodindes[$last]['accouchement'], 0, strlen($dragodindes[$last]['accouchement']) -1);
            }
            $date_base = new DateTime($contentsDecoded['last'][$key]['date']);
            $check = true;
        }
        else {
            $date_base = new DateTime();
            $date_base->setDate(Date('Y'), Date('m'), Date('d'));
            $date_base->setTime(Date('H') + 1, Date('i'));
        }
        
        if (count($contentsDecoded['Mes dragodindes']) === 0 && $contentsDecoded['last']) {
            $accouch = new DateTime();
            $temps_dd = substr($dragodindes[$last]['accouchement'], 0, strlen($dragodindes[$last]['accouchement']) - 1);
            $accouch->setDate($date_base->format('Y'), $date_base->format('m'), $date_base->format('d'));
            $accouch->setTime($date_base->format('H') + $temps_dd, $date_base->format('i'));
            echo $this->helper->show_in_color("Accouchement prevu : ".$accouch->format('d-m H:i:s'), 'bleu clair')."\n";
        }
        if ($contentsDecoded['Mes dragodindes']) {
            foreach($contentsDecoded['Mes dragodindes'] as $key => $value) {
                $name = $key;
                if (preg_match("/\bet\b/i", $key) == 1) {
                    $name = explode(' ', $key)[0].explode(' ', $key)[2];
                }
                $time[$dragodindes[$name]['name']] = substr($dragodindes[$name]['accouchement'], 0, strlen($dragodindes[$name]['accouchement']) -1);
            }
            arsort($time);
            $increment = 0;
            $first;
            foreach ($time as $key => $value) {
                if ($increment == 0) {
                    $first = $time[$key];
                }
                $increment++;
            }
            $increment = 0;
            $accouch = new DateTime();
            foreach ($time as $key => $value) {
                $date = new DateTime();
                if ($check === false) {
                    if ($increment == 0) {
                        $accouch->setTime($date->format('H') + 1 + $time[$key], $date->format('i'));
                        $time[$key] = "NOW | ".$date_base->format('d-m H:i:s');
                    }
                    else {
                        $diff = $first - intval($time[$key]);
                        $date_base->setTime($date_base->format('H') + $diff, $date_base->format('i'));
                        if ($diff === 0) {
                            $time[$key] = "NOW | ".$date_base->format('d-m H:i:s');
                        }
                        else {
                            $time[$key] = "Dans $diff H | ".$date_base->format('d-m H:i:s');
                        }
                    }
                    $increment++;
                }
                else {
                    $date->setTime($date->format('H') + 2, $date->format('i'));
                    $data = $this->helper->diff_date($date, $date_base);
                    $diff_day = $data[0];
                    $diff_h = $data[1];
                    $temps_base = substr($dragodindes[$last]['accouchement'], 0, strlen($dragodindes[$last]['accouchement']) - 1);
                    $diff_dd = $bigger - $time[$key];
                    if ($increment == 0) {
                        if ($diff_h > $diff_dd) {
                            $accouch->setTime($accouch->format('H') + 2 + $time[$key], $accouch->format('i'));
                            $time[$key] = "NOW | ".$date->format('d-m H:i:s');
                            $after = true;
                        }
                        else {
                            if ($diff_h === 0 ) {
                                if ($diff_day === 0) {
                                    $accouch->setTime($accouch->format('H') + 2 + ($diff_dd-$diff_h) + $time[$key], $date_base->format('i'));
                                    $date->setTime(Date('H') + 2 + ($diff_dd-$diff_h), $date_base->format('i'));
                                }
                                else {
                                    $accouch->setTime($accouch->format('H') + 2 + $first, $accouch->format('i'));
                                    $date->setTime(Date('H') + 2, $date_base->format('i'));
                                }                                
                            }
                            else {
                            if ($diff_dd - $diff_h > 0) {
                                $accouch->setTime($date_base->format('H') + $bigger, $date_base->format('i'));  
                                $date->setTime(Date('H') + 2 + ($diff_dd - $diff_h), $date_base->format('i'));
                                $time[$key] = "Dans ".($diff_dd-$diff_h)." H | ".$date->format('d-m H:i:s');
                            }
                            else {
                                $date = new DateTime();
                                $accouch->setTime(Date('H') + 2, $date->format('i'));
                                $date->setTime($date->format('H') + 2, $date->format('i'));
                                $time[$key] = "NOW | ".$date->format('d-m H:i:s');
                            }
                            $after = false;
                            $last_diff = ($diff_dd-$diff_h);
                        }
                        }
                    }
                    else {
                        if ($after) {
                            $diff = $first - $time[$key];
                            $date->setTime(Date('H') + 2 + $diff, $date->format('i'));
                            if ($diff === 0) {
                                $time[$key] = "NOW | ".$date->format('d-m H:i:s');
                            }
                            else {
                                $time[$key] = "Dans ".$diff." H | ".$date->format('d-m H:i:s');
                            }
                        }
                        else {
                            $diff = $first - $time[$key];
                            $date->setTime(Date('H') + 2 + $diff + $last_diff, $date_base->format('i'));
                            
                            $time[$key] = "Dans ".($diff + $last_diff)." H | ".$date->format('d-m H:i:s');
                        } 
                    }
                    $increment++;
                }
            }
            echo $this->helper->show_in_color("Accouchement prevu : ".$accouch->format('d-m H:i:s'), 'bleu clair')."\n";
            echo "\n";
            $this->helper->align_text($time);
        }
        else {
            echo $this->helper->show_in_color("\nAucune dragodindes !\n\n", 'rouge');
        }
    }
}