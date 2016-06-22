<?php 
namespace common\components;
 
 
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class EphocTime extends Component 
{
    public function time_elapsed_A($current,$create)
    {
        $cur_month = date("F");
        $cur_year = date("Y");
        $cur_day = date("d");
        $cre_month = date("F", $create);
        $cre_year = date("Y", $create);
        $cre_day = date("d", $create);
        $secs = $current - $create;
        $bit = array(
                        'y' => $secs / 31556926 % 12,
                        'w' => $secs / 604800 % 52,
                        'd' => $secs / 86400 % 7,
                        ' hrs' => $secs / 3600 % 24,
                        ' mins' => $secs / 60 % 60
                      //  ' secs' => $secs % 60
                    );
        //echo '<pre>';print_r($bit);
        if($bit['y'] >= 1)
        {
            return date("d F Y", $create). ' at ' .date("h:i a", $create);
        }
        else if($bit['w'] > 0)
        {
            return date("d F", $create). ' at ' .date("h:i a", $create);
        }
        else if($bit['d'] > 1 && $bit['d'] < 7)
        {
            return date("d F", $create). ' at ' .date("h:i a", $create);
        }
        else if(($bit['d'] == 0) && ($cur_day != $cre_day))
        {
            return 'Yesterday at ' .date("h:i a", $create);
        }
        else if($bit['d'] == 1 && $bit[' hrs'] <= 0 )
        {
            return 'Yesterday at ' .date("h:i a", $create);
        }
        else if($bit['d'] == 1 && $bit[' hrs'] > 0 )
        {
            return date("d F", $create). ' at ' .date("h:i a", $create);
        }
        /*else if(($bit[' mins'] > 5) && ($cur_day == $cre_day))
        {
            return date("h:i a", $create);
        }
        else if($bit[' mins'] <= 5 && $bit[' hrs'] >= 1)
        {
            return date("h:i a", $create);
        }
        else if($bit[' mins'] <= 5 && $bit[' hrs'] < 1)
        {
            return "Just now";
        }*/
        else
        {
          //$ret[] = $v . $k;
            foreach($bit as $k => $v) 
            {
                if($v > 0) 
                {
                  $ret[] = $v . $k;
                }
            }
        }
        if(empty($ret))
        {
            return "Just now";
        }
        else
        {
            return join(' ', $ret);
        }
    }
    
    public function time_pwd_changed($current,$create)
    {
        $cur_month = date("F");
        $cur_year = date("Y");
        $cur_day = date("d");
        $cre_month = date("F", $create);
        $cre_year = date("Y", $create);
        $cre_day = date("d", $create);
        $secs = $current - $create;
        $bit = array(
                        ' year' => $secs / 31556926 % 12,
                        ' week' => $secs / 604800 % 52,
                        ' day' => $secs / 86400 % 7,
                        ' hr' => $secs / 3600 % 24,
                        ' min' => $secs / 60 % 60
                      //  ' secs' => $secs % 60
                    );
        //echo '<pre>';print_r($bit);
        foreach($bit as $k => $v) 
        {
            if($v > 0) 
            {
              $ret[] = $v . $k;
            }
        }
        if(empty($ret))
        {
            return "just now";
        }
        else
        {
            return join(' ', $ret).' ago';
        }
    }
}