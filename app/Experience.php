<?php

namespace App;

class Experience extends EnhancedModel

{
   
   
    
    public function getDutiesAttribute($d){
        return self::text2web($d);
    }


}
