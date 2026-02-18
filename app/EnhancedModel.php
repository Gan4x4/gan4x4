<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Lang;

class EnhancedModel extends Model

{
    /**
     * Runtime-only aliases (e.g. name_en -> name) that must never be persisted.
     */
    protected $localizedAliases = [];
    
    
    public static function boot()
    {
        parent::boot();
        
        self::retrieved(function ($model) {
            $model->init();            
        });

        self::saving(function ($model) {
            $model->stripLocalizedAliases();
        });
    }
    
    
    public function init(){
        # Translating of language related fields like description_ru
        $locale = Lang::locale();
        foreach($this->attributes as $p=>$v){
            $postfix = substr($p , -2);
            if ($postfix != $locale) {
                continue;
            }

            $new_name = substr($p, 0, strlen($p) - 3); # trim "_ru" / "_en"
            if (empty($new_name)) {
                continue;
            }

            # Do not override real DB columns if they exist.
            if (array_key_exists($new_name, $this->attributes)) {
                continue;
            }

            # Expose locale-aware virtual field for reads only.
            $this->attributes[$new_name] = $v;
            $this->localizedAliases[$new_name] = true;
        }
    }

    protected function stripLocalizedAliases()
    {
        if (empty($this->localizedAliases)) {
            return;
        }

        foreach (array_keys($this->localizedAliases) as $alias) {
            unset($this->attributes[$alias]);
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
        if ($text === null) {
            return '';
        }

        $markdown = self::htmlToMarkdown((string) $text);
        $markdown = self::linkifyMarkdownUrls($markdown);

        return Str::markdown($markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    public static function htmlToMarkdown(string $text): string
    {
        if ($text === '') {
            return '';
        }

        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $text = preg_replace('~<br\\s*/?>~i', "\n", $text);
        $text = preg_replace('~</p>\\s*<p[^>]*>~i', "\n\n", $text);
        $text = preg_replace('~<p[^>]*>~i', '', $text);
        $text = preg_replace('~</p>~i', "\n\n", $text);

        $text = preg_replace_callback('~<h([1-6])[^>]*>(.*?)</h\\1>~is', function ($m) {
            $level = max(1, min(6, (int)$m[1]));
            $content = trim(strip_tags($m[2]));
            return str_repeat('#', $level) . ' ' . $content . "\n\n";
        }, $text);

        $text = preg_replace('~<(strong|b)[^>]*>(.*?)</\\1>~is', '**$2**', $text);
        $text = preg_replace('~<(em|i)[^>]*>(.*?)</\\1>~is', '*$2*', $text);
        $text = preg_replace_callback('~<code[^>]*>(.*?)</code>~is', function ($m) {
            $content = trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            return '`' . $content . '`';
        }, $text);

        $text = preg_replace_callback('~<a\\s+[^>]*href=[\'"]([^\'"]+)[\'"][^>]*>(.*?)</a>~is', function ($m) {
            $url = trim($m[1]);
            $label = trim(strip_tags($m[2]));
            if ($label === '') {
                $label = $url;
            }
            return '[' . $label . '](' . $url . ')';
        }, $text);

        $text = preg_replace('~<li[^>]*>~i', '- ', $text);
        $text = preg_replace('~</li>~i', "\n", $text);
        $text = preg_replace('~</?(ul|ol)[^>]*>~i', "\n", $text);

        $text = strip_tags($text);
        $text = preg_replace("/\n{3,}/", "\n\n", $text);

        return trim($text);
    }

    public static function linkifyMarkdownUrls(string $text): string
    {
        if ($text === '') {
            return '';
        }

        return preg_replace_callback('~(?<!\\]\\()(?<!\\()(?<![">])(https?://[^\\s<]+)~i', function ($m) {
            $url = rtrim($m[1], '.,;)');
            $tail = substr($m[1], strlen($url));
            return '<' . $url . '>' . $tail;
        }, $text);
    }

    protected static function resolveDesignImageUrl(string $section, ?string $filename): string
    {
        $filename = trim((string) $filename);
        if ($filename === '') {
            return '';
        }

        $section = trim($section, '/');
        $storageRelative = 'design/' . $section . '/' . $filename;
        if (is_file(storage_path('app/public/' . $storageRelative))) {
            return asset('storage/' . $storageRelative);
        }

        return asset('design/' . $section . '/' . $filename);
    }

}
