<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Lang;

class Project extends EnhancedModel
{
    protected $fillable = [
        'name_en',
        'name_ru',
        'description_en',
        'description_ru',
        'start',
        'end',
        'url',
        'logo',
        'links',
        'skill',
    ];
    
    protected $casts = [
        'links' => 'array',
    ];
    
    public function getSkillAttribute($value)
    {                
        if (empty($value)) {
            return [];
        }
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
        
        if (empty($raw_links)){
            return [];
        }
        
        $arr = json_decode($raw_links);
        if (!is_array($arr)) {
            return [];
        }
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
