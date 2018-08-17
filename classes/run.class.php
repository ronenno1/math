<?php
class run
{
    static function create_session()
    {
        $query = "SELECT MAX(code) FROM answers AS id";
        $last_id_arr = dblayer::get_row_query_single($query);
        $_SESSION['code'] = $last_id_arr['MAX(code)']+1;
        return $_SESSION['code'];
    }
    
    static function send_answer($code, $user_id, $stimulate, $answer, $stimulate_time, $response_time, $sent_time, $is_correct)
    {
        $query = "INSERT 
                    INTO answers
                SET 
                    code = '".dblayer::clean($code)."',
                    stimulate_text = '".dblayer::clean($stimulate)."',
                    answer         = '".dblayer::clean($answer)."',
                    is_correct     = '".($is_correct ? 1 : 0)."',
                    stimulate_time = '".dblayer::clean($stimulate_time)."',
                    response_time  = '".dblayer::clean($response_time)."',
                    sent_time      = '".dblayer::clean($sent_time)."'";
        return dblayer::get_row_query_insert($query)>0;
    }
    
    public static function use_run_code($id)
    {
        $_SESSION['code'] = $id;
        return $id;
    }
}