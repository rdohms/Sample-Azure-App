<?php

namespace App\Action;

class Index extends Base
{
    public function run($args = array())
    {
        $template = $this->getApp()->getTwig()->loadTemplate('index.html.twig');

        return $template->render(array());
    }

}