<?php

/**
 *  (c) Rogério Adriano da Silva <rogerioadris.silva@gmail.com>.
 */
namespace Audi\AudiSystem\Controller\Front;

use Audi\AudiSystem\Controller\ContainerAware;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\User\User;

class HomeController extends ContainerAware
{


    public function IndexAction ()
    {

        // $blog = $this->get('db')->fetchAll("SELECT * FROM noticias_blog where enabled = 1 order by id desc");
        // $unidades = $this->get('db')->fetchAssoc("SELECT * FROM institutional_type where enabled = 1 and id = 2");
        //$this->get('db')->close();
        //dd("testes");

        return $this->render('/front/index.twig', array(
            // 'blog' => $blog,
            // 'unidades' =>$unidades,


        ));
    }

    public function TodosConveniosAction ()
    {        

        return $this->render('/front/todos_convenios.twig', array(
            // 'blog' => $blog,
            // 'unidades' =>$unidades,
        ));
    }

    public function TodasColetasAction ()
    {        

        return $this->render('/front/todas_coletas.twig', array(
            // 'blog' => $blog,
            // 'unidades' =>$unidades,
        ));
    }

    public function QuemSomosAction ()
    {        

        return $this->render('/front/quem_somos.twig', array(
            // 'blog' => $blog,
            // 'unidades' =>$unidades,
        ));
    }

    public function ParceiroAction ()
    {        

        return $this->render('/front/parceiro.twig', array(
            // 'blog' => $blog,
            // 'unidades' =>$unidades,
        ));
    }
    

    





}
