<?php
class log
{
    private static $first_time = TRUE;
    private static $hostname = NULL;
    
        public static function str_to_line($str)
    {
        $str = preg_replace('/\s\s+/', ' ', $str);
        $str = preg_replace('/([\r\n])+/', ' ', $str);
        return $str;
    }
    
    
    
    private static function log_generic($file, $function, $level, $code, $data, $logfile_name = 'applog', $display = FALSE, $trace = FALSE)
    {
        if(empty($logfile_name))
            $logfile_name = 'applog';
        $log_path =  config::log_path;
        $logfile = $log_path.$logfile_name.'_'.date('Y-m-d_H').'.txt';
        if(!is_string($data))
            $data = @json_encode($data);
        else
            $data = self::str_to_line($data);
                
        $file = basename($file);
        
        $trace_str = '';
        if($trace)
            $trace_str = @json_encode(debug_backtrace());
        if(!self::$hostname)
            self::$hostname = @gethostname();
        $str = date("Y-m-d H:i:s").'|'.self::$hostname."|$code|$file|$function|[$level]|$data|$trace_str";
        file_put_contents($logfile, "$str\r\n", FILE_APPEND);
        
        if(self::$first_time)
        {
            if(!@chmod($logfile, 0777))
                error_log("Can't chmod log file!:$logfile");
            self::$first_time = FALSE;
        }
        if($display)
        {
            echo "<pre>";
                print_r($str);
            echo "</pre>";
        }
        return $str;
    }
    
    public static function error($file, $function, $code, $text, $logfile = 'applog', $display = FALSE, $trace = FALSE)
    {
        return self::log_generic($file, $function, 'ERROR', $code, $text, $logfile, $display, $trace);
    }


    public static function info($file, $function, $code, $text, $logfile = 'applog', $display = FALSE)
    {
        return self::log_generic($file, $function, 'INFO', $code, $text, $logfile, $display);
    }
}

