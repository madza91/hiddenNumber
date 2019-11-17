<?php

/**
 * Creates a template/view object
 */
class Template
{
    /** @var string */
    protected $template;

    /** @var string */
    protected $template_dir;

    /** @var string */
    protected $base_dir;

    /** @var bool|TRUE */
    protected $header;

    /** @var bool|TRUE */
    protected $menu;

    /** @var string */
    protected $title;

    /** @var bool|TRUE */
    protected $footer;

    /**
     * Variables passed in
     * @var array
     */
    protected $vars = [];

    /**
     * Template constructor.
     *
     * @param string    $template
     * @param bool|TRUE $header
     * @param bool|TRUE $menu
     * @param bool|TRUE $footer
     */
    public function __construct(string $template, bool $header = true, bool $menu = true, bool $footer = true)
    {
        // Check for extension
        if(substr($template, -4) !== '.php') {
            $template .= '.php';
        }

        $this->template     = BASE_DIR . 'templates/' . SITE_TEMPLATE . '/' . $template;
        $this->template_dir = BASE_DIR . 'templates/' . SITE_TEMPLATE . '/';
        $this->base_dir     = BASE_DIR;
        $this->header       = $header;
        $this->menu         = $menu;
        $this->footer       = $footer;
        $this->title        = 'Warning: Title is not set!';
    }

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->vars[$key];
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->vars[$key] = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        extract($this->vars);
        @chdir(dirname($this->template));
        ob_start();

        global $lang;
        global $urls;
        global $menu;

        // If TRUE, include header
        if ($this->header) {
            include($this->template_dir . 'includes/header.php');
        }

        // If TRUE, include menu
        if ($this->menu) {
            include($this->template_dir . 'includes/menu.php');
        }

        // Include view source if exists, otherwise 404.php
        if (file_exists($this->template)) {
            include($this->template);
        } else {
            // View doesn't exists
            $tmpPageName = basename($this->template);
            $template = new Template('404.php', false, false, false);
            $template->title = 'Error 404 - View not found';

            $template->message = "View page doesn't exists.<br> View you requested is <b title='{$this->template}'>{$tmpPageName}</b>";
            echo $template;
        }

        // If TRUE, include footer
        if ($this->footer) {
            include($this->template_dir . 'includes/footer.php');
        }

        return ob_get_clean();
    }
}
