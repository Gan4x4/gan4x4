<?php

namespace App;

class Experience extends EnhancedModel

{
    protected $fillable = [
        'name_en',
        'name_ru',
        'description_en',
        'description_ru',
        'position_en',
        'position_ru',
        'duties_en',
        'duties_ru',
        'start',
        'end',
        'url',
        'logo',
    ];
   
   
    public function getDescriptionAttribute($d){
        return self::text2web($d);
    }
    
   
    public function getDutiesAttribute($d){
        return self::text2web($d);
    }

    public function getLogoUrlAttribute()
    {
        return self::resolveDesignImageUrl('work', $this->logo ?? '');
    }

    public function projects()
    {
        return $this->hasMany(Project::class)
            ->orderByRaw('CASE WHEN "end" IS NULL THEN 0 ELSE 1 END ASC')
            ->orderByRaw('COALESCE("end", "start") DESC')
            ->orderByDesc('start');
    }

    public function videos()
    {
        return $this->hasMany(Video::class)
            ->orderByDesc('created_at');
    }

}
