<?php

namespace MSS;

abstract class Controller
{
    public $pathAssets = '';
        
    protected function render(array $data = [], string $view = '', bool $includeTemplate = true)
    {
        extract($data);
      
        if (!$view) {
            $view =  $this->controllerName() . '/' . debug_backtrace()[1]['function'];
        }

        if ($includeTemplate) {
            require __DIR__. '/../templates/base.tpl.php';
        } else {
            require __DIR__. '/../templates/'.$view.'.php';
        }
    }

    private function controllerName()
    {
        $class = get_class($this);
        $class = explode('\\', $class);
        $class = array_pop($class);
        $class = preg_replace('/Controller$/', '', $class);

        return strtolower($class);
    }

    protected function redirect($toUrl)
    {
        header("Location: ".$toUrl);
    }
}
