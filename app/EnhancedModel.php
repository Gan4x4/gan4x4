<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Lang;

class EnhancedModel extends Model

{
    
    
    public static function boot()
    {
        parent::boot();
        
        self::retrieved(function ($model) {
            $model->init();            
        });
    }
    
    
    public function init(){
        # Translating of laguage related fields like description_ru
        foreach($this->attributes as $p=>$v){
            $postfix = substr($p , -2);
            if ( $postfix == Lang::locale() ){  # ru or en
                $new_name = substr($p,0,strlen($p)-3);
                $this->{$new_name} = $v;
            }    
        }
    }
    
    public static function makeClickableLinks($s) {
        // https://stackoverflow.com/questions/1960461/convert-plain-text-urls-into-html-hyperlinks-in-php
        //return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" >$1</a>', $s);
        return preg_replace('~(^|\s)((?:https?://|ftps?://|www\.)[^\s"\']+)(\s|$)~', '<a href="$2">$1$2$3</a>', $s);
    }
    
    public static function toYear($val)
    {
        if ($val){
            return substr($val, 0, 4); #year
        }
        return $val;        
    }
    
    public function getStartAttribute($value)
    {                
        return self::toYear($value);
    }
    
    
    public function getEndAttribute($value)
    {        
        return self::toYear($value);
    }
    
    public static function text2web($text){
       $href = self::makeClickableLinks($text);
       return nl2br($href);
    }

}
