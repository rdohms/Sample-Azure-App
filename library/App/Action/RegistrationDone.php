<?php

namespace App\Action;

class RegistrationDone extends Base
{
    public function run($args = array())
    {
        
        $template = $this->getApp()->getTwig()->loadTemplate('registration-done.html.twig');
        return $template->render(array(
            'user' => $user,
            'locationGraphUrl' => $this->convertUrl($locationGraphUrl),
            'wordGraphUrl' => $this->convertUrl($wordGraphUrl)
        ));
    }

}