<?php

/**
 *  (c) Rogério Adriano da Silva <rogerioadris.silva@gmail.com>.
 */
namespace Audi\AudiSystem\Controller\Front;

use Audi\AudiSystem\Controller\ContainerAware;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\User\User;

class UnidadesController extends ContainerAware
{


    

    public function TodasUnidadesAction ()
    {
        
        $unidades = $this->get('db')->fetchAll("SELECT * FROM unidades_type where enabled = 1");
        $this->get('db')->close();
        //dd("testes");

        return $this->render('/front/todas_unidades.twig', array(
            'unidades' => $unidades,
        ));
    }

    

    





}
