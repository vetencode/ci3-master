<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Template
{
    protected $ci;
    protected $layout = 'templates/master';
    protected $sections = [];
    protected $startedSection;
    protected $data = [];

    public function __construct()
    {
        $this->ci = &get_instance();
    }

    public function use($template = 'layouts/master')
    {
        $this->layout = $template;
    }

    public function render($viewName, $data = [])
    {
        $this->data = array_merge($this->data, $data);
        // Processing called view
        $this->ci->load->view($viewName, $this->data);

        // rendering master template
        $this->ci->load->view($this->layout, $this->data);
    }

    public function show($section)
    {
        if (isset($this->sections[$section])) {
            echo $this->sections[$section];
        }
    }

    public function section($name)
    {
        $this->sections[$name] = '';
        $this->startedSection = $name;
        ob_start();
    }

    public function endSection()
    {
        if (isset($this->sections[$this->startedSection])) {
            $this->sections[$this->startedSection] .= ob_get_clean();
        }
    }

    public function include($viewName, $data = [])
    {
        $this->data = array_merge($this->data, $data);
        $this->ci->load->view($viewName, $this->data);
    }
}
