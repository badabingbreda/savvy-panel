<?php
namespace SavvyPanel;

class Section extends Control {

    private $content;
    private $start;
    private $end;

    public $defaults = [

        'id'        => 'section-id',
        'title'     => 'Section title',
        'content'   => '',
    ];

    public function prepare() {

        $this->content = $this->settings[ 'content' ];
        $this->start = $this->start();
        $this->end = $this->end();

        return $this;
    }

    public function __(  ) {
        $settings = $this->settings;
        return <<<EOL
                {$this->start}
                {$this->content}
                {$this->end}
        EOL;
    }

    public function start( $echo = false ) {
        $return = <<<EOL
            <div id="{$this->settings['id']}">
                <h3>{$this->settings['title']}</h3>
            EOL;
        
        if ( $echo === true ) {
            echo $return;
        } else {
            return $return;
        }
    }

    public function end( $echo = false ) {
        $return = <<<EOL
        </div>
        EOL;

        if ( $echo === true ) {
            echo $return;
        } else {
            return $return;
        }
    }

    public function add_content( $content ) {
        $this->content .= $content;
    }



}