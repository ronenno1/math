<?php

require_once './classes/autoload.php';
if(!session_id())
    session_start();
if (preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]))
    die('we do not support smartphones');

$id = isset($_GET['id']) ? $_GET['id'] : 0;    

$id ? run::use_run_code($id) : 0;

if($id)
{
    $add   = config::add;
    $sub   = config::sub;
    $mult  = config::mult;
    $div   = config::div;
    $zeros = config::zeros;
}
if(!$id)
{
    $add            = isset($_GET['add']) ? $_GET['add'] : 0;    
    $sub            = isset($_GET['sub']) ? $_GET['sub'] : 0;    
    $mult           = isset($_GET['mult']) ? $_GET['mult'] : 0;    
    $div           = isset($_GET['div']) ? $_GET['div'] : 0;    
    $zeros          = isset($_GET['zeros']) ? $_GET['zeros'] : 0;    
    if(!$add && !$sub && !$mult && !$div)
        die('Wrong Code');
    run::create_session();
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href = "https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="./js/run.js"></script>
        <script src="./js/jquery.fullscreen.min.js"></script>
    </head>
    <body dir="ltr">
        <div id="inst">
            <div id="inst1" class="inst">
                <p >
                    <h1 align="center">

                        Hello and welcome to the experiment

                    </h1>
                </p>
                <p align="left">
During the current study by researchers Yarden Gliksman and Avishai Henik of Ben Gurion University of the Negev, you will be presented with stimuli on the computer. Your job will be to respond quickly and without errors according to the instructions presented to you at the beginning of the experiment. Your computer will register your answers and response times. The data collected will be confidential and will be visible only to the researchers.

                </p>
                <p align="left">
If the research causes you to suffer or experience emotional distress, you may wish to discontinue the study at any stage. Credit or payment will be given only if you complete the experiment. 

                </p>

                <p align="center">
            To confirm your participation please click
            <button id="start_but" onclick="go2a()" class="btn btn-primary">here</button>
                </p>
            </div>
            <div id="inst2" class="inst">
               
                <p>

The screen below shows arithmetic exercises.

                </p>
                <p>
You need to solve them as quickly and accurately as possible

                </p>
                <p>
Type the answer in the space provided, then click "Enter" to continue

                </p>
                <p align="center">
                    To continue click
                    <button id="start_but" onclick="go2b()" class="btn btn-primary">here</button>
                </p>
            </div>
            <div id="inst3" class="inst">
               
                <p>


		Here is an example, in the screen below you are asked to calculate the value of exercise 1 + 1.

                </p>
                <p>
                The correct answer in this case is 2, this is presented in the answer box
                </p>
		<p>
			In every trial of the experiment, you will get a similar screen; that is, an exersise and below it an answer box where you need to key in your response.
		</p>
                <p align="center">
                    <img class="img" src="example.png">
                    
                </p>
                <p align="center">
                    To start the experiment click
                    <button id="start_but" onclick="start()" class="btn btn-primary">here</button>
                </p>
            </div>
        </div>
        
        <div id="parent">
            <div id="page">
                <div class="box"  dir="ltr">
                    <div id="changeit"></div>
                    <input id="inp">
                    <input id="res" hidden >
                    <script>
                        $(document).ready(function(){
                            actions = [<?php echo $add;?>, <?php echo $sub;?>, <?php echo $mult;?>, <?php echo $div;?>];
                            include_zero = (<?php echo $zeros;?>>0);
                        });
                    </script>
                </div>
            </div>
        </div>
    </body>
</html>
