<?php

namespace App\Action;

class SaveEmail extends Base
{
    public function run($args = array())
    {
        var_dump($_POST);
        
        $repo = $this->getApp()->getDoctrineEntityManager()->getRepository('App\Entity\User');
        $user = $repo->findByTwitterHandle($_POST['twitter_handle']);
        $user->setEmail($_POST['email']);
        
        $this->getApp()->getDoctrineEntityManager()->persist($user);
        $this->getApp()->getDoctrineEntityManager()->flush();
        
        $template = $this->getApp()->getTwig()->loadTemplate('save-email.html.twig');
        return $template->render(array(
            'user' => $user,
        ));
    }

}