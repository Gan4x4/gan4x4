<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Lang;

class Project extends EnhancedModel
{
    
    protected $casts = [
        'links' => 'array',
    ];
    
    public function getSkillAttribute($value)
    {                
        $words = [];    
        $delim = "\n\t,;";
        $tok =  strtok($value,$delim);      
        while ($tok !== false) {
            $words[] = $tok;
            $tok =  strtok($delim);      
        }
        
        return $words;
    }
    
    
    public function getDescriptionAttribute($d){
        return self::text2web($d);
    }
    
    
    public function getLinksAttribute($raw_links){
        
        if ($raw_links === Null){
            return [];
        }
        
        $arr = json_decode($raw_links);
        $out = [];
        foreach ( $arr as $link){
            $obj = (object)["url"=>Null,"description"=>Null];
            $obj->url = $link[0];
            
            
            if (Lang::locale() == 'ru' && isset($link[1]) ){
                $obj->description = $link[1];
            }
            
            if (Lang::locale() == 'en' && isset($link[2])){
                $obj->description = $link[2];
            }
            
            if (empty($obj->description)){
                $obj->description = $link[0]; #default description
            }
            $out[] = $obj;
        }
        #dd($out);
        return $out;
        #dd($l);
        #return self::text2web($d);
    }
    
}
