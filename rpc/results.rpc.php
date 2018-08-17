<?php
require_once '../classes/autoload.php';
if(!session_id())
    session_start();

$action = isset($_POST['action']) ? $_POST['action'] : NULL;
$id  = isset($_POST['id']) ? $_POST['id'] : NULL;
$ids = isset($_POST['ids']) ? $_POST['ids'] : NULL;
switch ($action)
{
    case 'excel_output':
        $_SESSION['id'] = $id;
        die('downloadOutput.php');
        break;
    case 'excel_output_group':
        $_SESSION['ids'] = $ids;

        die('downloadOutput.php');
        break;
    case 'show_participant':
        die(exps::show_participant_results($id));
        break;
    case 'delete_participant':
        die(exps::delete_participant_results($id));
        break;
}
