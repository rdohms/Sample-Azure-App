<?php

namespace App\Action;

class RegistrationDone extends Base
{
    public function run($args = array())
    {
        
        $repo = $this->getApp()->getDoctrineEntityManager()->getRepository('App\Entity\User');
        $user = $repo->findByTwitterHandle($args['handle']);
        
        $template = $this->getApp()->getTwig()->loadTemplate('registration-done.html.twig');
        return $template->render(array(
            'user' => $user,
        ));
    }

}