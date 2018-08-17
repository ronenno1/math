<?php
require 'log.class.php';

class dblayer
{

    private static $con = FALSE; // hold the connecton instance

    private static function disconnect()
    {
        mysqli_close(self::$con);
        self::$con = FALSE;
        return;
    }

    private static function connect()
    {
        if (self::$con)
            return self::$con;
        
        self::$con = new mysqli(config::sql_server, config::sql_user, config::sql_pass);
        if (!self::$con)
            die(self::die_report_error());
        self::$con->select_db(config::sql_db) or die(self::die_report_error());
        return self::$con;
    }

    private static function die_report_error($query)
    {
        $code = 20140926.1224;
        $str = "E_MYSQLI_ERROR, query: [$query], error:" . mysqli_error(self::$con) . ", errno:" . mysqli_errno(self::$con);
        $err = log::error(__FILE__, __FUNCTION__, $code, $str, NULL, FALSE, TRUE);
        die();
    }

    static function get_row_query($query)
    {
        self::connect();
        log::info(__FILE__, __FUNCTION__, 20140926.1321, $query, 'querylog');
        $resultGroup = self::$con->query($query);
        if (!$resultGroup)
            die(self::die_report_error($query));
        $res = array();
        while ($resultGroup && $row = mysqli_fetch_assoc($resultGroup))
            $res[] = $row;
        self::disconnect();
        return $res;
    }

    static function get_row_query_single($query)
    {
        $res = self::get_row_query($query);
        return $res ? $res[0] : $res;
    }

    static function get_row_query_update($query)
    {
        log::info(__FILE__, __FUNCTION__, 20140926.1322, $query, 'querylog');

        self::connect();
        $res = self::$con->query($query);
        if (!$res)
            die(self::die_report_error($query));
        return TRUE;
    }

    static function get_row_query_insert($query)
    {
        log::info(__FILE__, __FUNCTION__, 20140926.1632, $query, 'querylog');

        self::connect();
        $res = self::$con->query($query);
        if (!$res)
            die(self::die_report_error($query));
        $mysqli_insert_id = self::$con->insert_id;
        self::disconnect();
        return $mysqli_insert_id;
    }

    public static function clean($text)
    {
        if ($text === null)
            return null;
        return trim(strip_tags(str_replace("'", "\'", str_replace('"', '\"', $text))));
    }

    static function get_connected_user()
    {
        if (!isset($_COOKIE['login']))
            header("Location: http://psc.bgu.ac.il/mexp");
        $userId = $_COOKIE['login'];
        $query = "
					SELECT 
						* 
					FROM 
						users 
					WHERE 
						id='$userId'";
        $result = dblayer::get_row_query_single($query);
        return $result;
    }

    static function draw_menu($name, $questName = NULL, $dir = NULL)
    {
        ob_get_contents();
        ?>

        <table  class ="borderTable" width =100%>
            <tr >
                <td width =200px class ="borderTable">
                    <div class="btn-group">
                        <a  class="btn btn-primary" ><i class="icon-user icon-white"></i> <?php echo $name; ?></a>
                        <a  class="btn btn-primary  dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                        <ul class="dropdown-menu ">
                            <li><a href="../profile.php"><i class="icon-pencil"></i> Edit</a></li>
                            <li class="divider"></li>
                            <li><a href="../logout.php"><i class="icon-off"></i> Logout</a></li>

                        </ul>
                    </div>
                </td>
                <td class ="borderTable">
                    <a href="../home.php"><?php echo $name; ?></a>&#187;<a href="../editQuest">Edit questionnaires
                        <?php
                        if ($questName)
                            echo "</a>&#187<a href=\"javascript:void(0)\" >$questName</a>";
                        ?>
                </td >
            </tr>
        </table>	
        <table width =100% class ="borderTable" >	
            <tr>	
                <td valign = top width =200px class ="borderTable">
                    <div class ="link">
                        <img  src = "../lib/logo.jpg" >
                    </div>
                    <div class ="link"> 
                        <a href="../editQuest"> Edit questionnaires</a>
                    </div>
                    <div class ="link">
                        <a href="../editSubExp"> Edit sub - experiments </a>
                    </div>
                    <div class ="link">
                        <a href="../editExp"> Edit experiments </a>
                    </div>

                </td>
                <td  valign = top class ="borderTable">
            <center>
                <?php if ($questName)
                {
                    ?>							
                    <h1>Edit questionnaire - <?php echo "$questName"; ?></h1>
                    <div id ="feedback" class="alert alert-error" style="display: none"></div>
                    <div class="btn-toolbar" >
                        <div class="btn-group">
                            <a  <?php if ($dir == "ltr") echo ' disabled="disabled" '; ?>
                                id ="dir-ltr" class="btn"  onClick = "changeDir('ltr')">
                                <i class="icon-align-left"></i>
                            </a>
                            <a <?php if ($dir == "rtl") echo ' disabled="disabled" '; ?>
                                id ="dir-rtl" class="btn"  onClick = "changeDir('rtl')">
                                <i class="icon-align-right"></i>
                            </a>			
                        </div>
                    </div>
            <?php } ?>
            </center>
            <?php
            ob_get_clean();
        }

    }
    
