<?php
require_once './classes/autoload.php';
if(!session_id())
    session_start();
    if((!isset($_SESSION['id']) && !isset($_SESSION['ids'])))
        die('x');
    $id        = isset($_SESSION['id']) ? $_SESSION['id'] : NULL;
    $ids    = isset($_SESSION['ids']) ? $_SESSION['ids'] : NULL;
    
    unset($_SESSION['id']);
    unset($_SESSION['id']);
    header('Content-Encoding: UTF-8');
    header('Content-type: text/csv; charset=UTF-8');

    header("Content-type: text/csv");
    $file_name = $id ? $id : 'all';
    header("Content-Disposition: attachment; filename=$file_name.csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    $query_where = "code = '".dblayer::clean($id)."'"; 
    if($id === NULL)
        $query_where = "code IN (".implode(", ", array_map(function($id){return "'".dblayer::clean($id)."'";}, $ids)).") ORDER BY code"; 
    $header = '#, Subject id, Type, Problem, Stimuli onset, Time to enter first digit (either one of one or one of two digits), Time to complete response (i.e. time to click enter), Participant response, Correct response';
    $query = "SELECT 
                    code,
                    stimulate_text,
                    stimulate_time,
                    (response_time/1000 - stimulate_time/1000) AS response_dis_sec,
                    (sent_time/1000 - stimulate_time/1000) AS sent_dis_sec,
                    answer,
                    is_correct
                FROM 
                    answers 
                WHERE 
                    $query_where";
    $participants = dblayer::get_row_query($query);
    $body = array();
    foreach($participants as $ida => $participant)
    {
        $nanoseconds_time = substr($participant['stimulate_time'],10);
        $stimulate_time = date('Y/m/d H:i:s', $participant['stimulate_time']/1000).":$nanoseconds_time";

        $body[] = "$ida, $participant[code], ".exps::get_type($participant['stimulate_text']).", $participant[stimulate_text], $stimulate_time, $participant[response_dis_sec], $participant[sent_dis_sec], $participant[answer], $participant[is_correct]";
    }
    $bom  = "\xEF\xBB\xBF"; // UTF-8 BOM

    $data = $bom.$header."\n\r".  implode("\n\r", $body);
    die($data);