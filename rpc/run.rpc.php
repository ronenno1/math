<?php
require_once '../classes/autoload.php';
if(!session_id())
    session_start();

$action          = isset($_POST['action']) ? $_POST['action'] : NULL;
$code            = isset($_SESSION['code']) ? $_SESSION['code'] : NULL;    
$user_id         = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;    

$stimulate       = isset($_POST['stimulate']) ? $_POST['stimulate'] : NULL;
$answer          = isset($_POST['answer']) ? $_POST['answer'] : NULL;
$is_correct      = isset($_POST['is_correct']) ? $_POST['is_correct'] : NULL;
$stimulate_time  = isset($_POST['stt']) ? $_POST['stt'] : NULL;
$response_time   = isset($_POST['ret']) ? $_POST['ret'] : NULL;
$sent_time       = isset($_POST['set']) ? $_POST['set'] : NULL;

switch ($action)
{
    case 'send_answer': 

            die(json_encode(run::send_answer($code, $user_id, $stimulate, $answer, $stimulate_time, $response_time, $sent_time, $is_correct)));
            break;
    case 'send_feedback': 
            die(json_encode(array('html' => run::send_feedback($code, $q1, $q2, $q3, $q4, $q5, $q6, $q7))));
            break;
    case 'get_end':
            die(json_encode(array('html' => run::get_end($code))));
            break;
}
