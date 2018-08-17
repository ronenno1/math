<?php
class exps 
{


    private static function get_all_participants()
    {
        $query = "SELECT 
                     min(stimulate_time) AS stimulate_time,
                     code
                   FROM answers 
                     GROUP BY code";
        
        return dblayer::get_row_query($query);
    }
    
    public static function view_result_table()
    {
        $participants = exps::get_all_participants();
        if(count($participants) == 0)
                return 'NO RESULTS...';
        ob_start();
        ?>
        <div class="inner_table">
            <table id="exp_results">
                <tr>
                  <th>    
                    <input id="select_all" type="checkbox">
                    <a href="javascript:excel_output_group();"><img src="excel.png"></a>
                  </th>
                  <th>participant id</th>
                  <th>start date</th>
                  <th>show</th>
                  <th>delete</th>
                </tr>
                <?php    
                foreach ($participants as $participant)
                {
                    $nanoseconds_time = substr($participant['stimulate_time'],10);
                    $time = date('Y/m/d H:i:s', $participant['stimulate_time']/1000).":$nanoseconds_time";
                ?>
                    <tr>
                            <td><input class="check" type="checkbox"></td>
                            <td><?php echo $participant['code'];?></td>
                            <td><?php echo $time;?></td>
                            <td><button class="btn btn-primary btn_show_participant"><span class="glyphicon glyphicon-eye-open"></span>Show</button></td>
                            <td><button class="btn btn-danger btn_delete_participant"><span class="glyphicon glyphicon-trash"></span>Delete</button></td>
                    </tr>
                <?php
                }
                    ?>
            </table>
            <div><a href="logout.php"><button class="btn btn-default btn_back">Logout</button></a></div>
        </div>
        <div class="inner_table" id="output"></div>
        <?php return ob_get_clean();
    }



    static function view_delete_alert()
    {
        ob_start();
        ?>
          <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                        Delete exp
                </div>
                <h3>are you sure?</h3>
                <div class="modal-footer">
                    <button type="button" id="btn_do_delete" class="btn btn-danger">Delete</button>
                    <button type="button" id="btn_close_modal" class="btn btn-default">Cancel</button>
                </div>
            </div>
          </div>
        <?php return array('html' => ob_get_clean());
    }


    static function delete_participant_results($code)
    {
        
        $query = "DELETE FROM 
                    answers
                  WHERE
                    code = '".dblayer::clean($code)."'";
          echo $query;
        if(!dblayer::get_row_query_update($query))
            return FALSE;
        return json_encode(array('html'=>(dblayer::get_row_query_update($query))));

    }
    static function show_participant_results($id)
    {
        $query = "SELECT 
                    answers.code,
                    stimulate_text,
                    stimulate_time,
                    (response_time/1000 - stimulate_time/1000) AS response_dis_sec,
                    (sent_time/1000 - stimulate_time/1000) AS sent_dis_sec,
                    answer,
                    is_correct
                FROM 
                    answers 
                WHERE 
                     code = '".dblayer::clean($id)."' 
                ORDER BY stimulate_time DESC";
        $lines = dblayer::get_row_query($query);
        ob_start();
        ?>
        <h3>participant <?php echo $id;?></h3>
        <div id="output_frame">
            <table>
                <tr>
                    <th class="r_stimulate_time">Stimuli onset</th>
                    <th class="r_response_dis_sec">Time to enter first digit (either one of one or one of two digits)	</th>
                    <th class="r_sent_dis_sec">Time to complete response (i.e., time to click enter)</th>
                    <th class="r_type">Type</th>
                    <th class="r_stimulate_text">Problem</th>
                    <th class="r_text">Participant response</th>
                    <th class="r_morfix_text">Correct response</th>
                </tr>
            </table>
                
            <div style="overflow: auto; max-height: 550px;">
                <table>
                <?php
                foreach($lines as $line)
                {
                    $nanoseconds_time = substr($line['stimulate_time'],10);
                    $stimulate_time = date('Y/m/d H:i:s', $line['stimulate_time']/1000).":$nanoseconds_time";
                    ?>
                    <tr>
                            <td class="r_stimulate_time"><?php echo $stimulate_time;?></td>
                            <td class="r_response_dis_sec"><?php echo $line['response_dis_sec'];?></td>
                            <td class="r_sent_dis_sec"><?php echo $line['sent_dis_sec'];?></td>
                            <td class="r_type"><?php echo exps::get_type($line['stimulate_text']);?></td>
                            <td class="r_stimulate_text"><?php echo $line['stimulate_text'];?></td>
                            <td class="r_text"><?php echo $line['answer'];?></td>
                            <td class="r_morfix_text"><?php echo $line['is_correct'];?></td>
                    </tr>
                    <?php
                }
                ?>
                </table>
            </div>            
        </div>            
        <a href="javascript:excel_output('<?php echo $id;?>');"><img src="excel.png"></a>
        <?php
        return ob_get_clean();
    }
    
    public static function get_type($string)
    {
        if(strpos($string, 'X') !== false)
            return 'X';
        if(strpos($string, '÷') !== false)
            return '÷';
        if(strpos($string, '+') !== false)
            return '+';
        if(strpos($string, '-') !== false)
            return '-';
    }
}