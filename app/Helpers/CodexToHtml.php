<?php

namespace App\Helpers;

class CodexToHtml {
    
    private $templates = null;
    private $beautify = false;

    function __construct($templates, $beautify) {
        $this->templates = $templates;
        $this->beautify = $beautify;
    }

    public function render($blocks) {
        $result = [];

        foreach ($blocks as $block) {
            if (array_key_exists($block['type'], $this->templates)) {
                $template = $this->templates[$block['type']];
                $data = $block['data'];
                $result[] = call_user_func_array($template, $data);
                # tunes implementation if needed
                // $data = array_values($block['data']);
                // $tunes = array_values($block['tunes'] ?? []);
                // $result[] = call_user_func_array($template, [...$data, ...$tunes]);
            }
        }

        $html = implode($result);
        
        return $html;
    }
    
}