<?php

/**
 *  (c) Rogério Adriano da Silva <rogerioadris.silva@gmail.com>.
 */
namespace Audi\AudiSystem\Controller\Front;

use Audi\AudiSystem\Controller\ContainerAware;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\User\User;

class ExamesController extends ContainerAware
{


    public function GetExameAction ($url_exame)
    {
        
        $exame = $this->get('db')->fetchAssoc("SELECT * FROM exames_type where enabled = 1 and url = ?",array($url_exame));
        $this->get('db')->close();
        //dd("testes");

        return $this->render('/front/interna_exames.twig', array(
            'exame' => $exame,
        ));
    }

    public function TodosExamesAction ()
    {
        
        //$exame = $this->get('db')->fetchAssoc("SELECT * FROM exames_type where enabled = 1 and url = ?",array($url_exame));
        $this->get('db')->close();
        //dd("testes");

        return $this->render('/front/todos_exames.twig', array(
          //  'exame' => $exame,
        ));
    }

    

    





}
