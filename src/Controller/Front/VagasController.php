<?php

/**
 *  (c) Rogério Adriano da Silva <rogerioadris.silva@gmail.com>.
 */
namespace Audi\AudiSystem\Controller\Front;

use Audi\AudiSystem\Controller\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\SwiftMailler;


use Symfony\Component\Security\Core\User\User;

class VagasController extends ContainerAware
{


    public function NossasVagasAction ()
    {
        
        $unidades = $this->get('db')->fetchAll("SELECT * FROM unidades_type where enabled = 1 ");
        $areas = $this->get('db')->fetchAll("SELECT * FROM areas_vagas  ");
        $vagas = $this->get('db')->fetchAll("SELECT v.*,av.role as area, u.title as unidade FROM vagas_type v 
                                                        inner join areas_vagas av on av.id = v.id_area
                                                        inner join unidades_type u on u.id = v.id_unidade");        
        $this->get('db')->close();
        //dd("testes");

        return $this->render('/front/nossas_vagas.twig', array(
            'unidades' => $unidades,
            'areas' => $areas,
            'vagas' => $vagas,
        ));
    }

    public function NossasVagasSearchAction (Request $request)
    {
        $data = $request->request->all();        
        
        $busca = $data["iBusca"];
        $area = $data["iArea"];
        $cidade = $data["iCidade"];
        
        $sql_busca = "SELECT v.*,av.role as area, u.title as unidade FROM vagas_type v 
        inner join areas_vagas av on av.id = v.id_area
        inner join unidades_type u on u.id = v.id_unidade
        where (v.vaga like '%".$busca."%' or v.body like '%".$busca."%')";

        if($area !=0)
        $sql_busca .=" and av.id = ".$area;

        if($cidade !=0)
        $sql_busca .=" and u.id = ".$cidade;

        //dd($sql_busca);

        $vagas = $this->get('db')->fetchAll($sql_busca);        
        $unidades = $this->get('db')->fetchAll("SELECT * FROM unidades_type where enabled = 1 ");
        $areas = $this->get('db')->fetchAll("SELECT * FROM areas_vagas  ");
        $this->get('db')->close();
        

        return $this->render('/front/nossas_vagas.twig', array(
            'unidades' => $unidades,
            'areas' => $areas,
            'vagas' => $vagas,
        ));
    }


    public function InternaVagaAction ($url_vaga)
    {
        
        $unidades = $this->get('db')->fetchAll("SELECT * FROM unidades_type where enabled = 1 ");
        $areas = $this->get('db')->fetchAll("SELECT * FROM areas_vagas  ");
        $vagas = $this->get('db')->fetchAssoc("SELECT v.*,av.role as area, u.title as unidade FROM vagas_type v 
                                                        inner join areas_vagas av on av.id = v.id_area
                                                        inner join unidades_type u on u.id = v.id_unidade
                                                        where v.url=?",array($url_vaga));        
        $this->get('db')->close();
        //dd("testes");

        return $this->render('/front/nossas_vagas_interna.twig', array(
            'unidades' => $unidades,
            'areas' => $areas,
            'vagas' => $vagas,
        ));
    }

    public function FaleConoscoAction ()
    {
        
        // $unidades = $this->get('db')->fetchAll("SELECT * FROM unidades_type where enabled = 1 ");
        // $areas = $this->get('db')->fetchAll("SELECT * FROM areas_vagas  ");
        // $vagas = $this->get('db')->fetchAssoc("SELECT v.*,av.role as area, u.title as unidade FROM vagas_type v 
        //                                                 inner join areas_vagas av on av.id = v.id_area
        //                                                 inner join unidades_type u on u.id = v.id_unidade
        //                                                 where v.url=?",array($url_vaga));        
        $this->get('db')->close();
        //dd("testes");

        return $this->render('/front/fale_conosco.twig', array(
            // 'unidades' => $unidades,
            // 'areas' => $areas,
            // 'vagas' => $vagas,
        ));
    }

    

    // public function FaleConoscoSendAction (Request $request)
    // {
    //     $data = $request->request->all();        
    //     dd($data);
    //     $busca = $data["iBusca"];
    //     $area = $data["iArea"];
    //     $cidade = $data["iCidade"];
    // }

    public function FaleConoscoSendAction(Request $request){
        if("POST" == $request->getMethod()){
  
          $data = $request->request->all();
          unset($data['send']);
  
          //validar envio do form
          if(!in_array('', $data) && count($data) > 0){
            $message = \Swift_Message::newInstance();
            $message->setSubject('Contato via site');
            $message->setFrom(array($this->get('swiftmailer.options')['from'] => 'Contato Mastelini'));
            $message->setTo(array("pedro.palopoli@hotmail.com"));            
            //$message->setReplyTo(array($data['email'] => $data['nome']));
            $message->setBody($this->render('/emails/fale_conosco.twig', array(
              'data' => $data,
            )), 'text/html');
  
            if (!$this->get('mailer')->send($message)) {
              //return $this->json(['message' => 'Erro ao enviar mensagem, tente novamente!', 'type' => 'error', 'title' => 'Erro'], 400);
              $this->flashMessage()->add('danger', array('message' => 'Erro ao enviar mensagem, tente novamente!'));
            } else {
              //return $this->json(['message' => 'Mensagem enviada com sucesso.', 'type' => 'success', 'title' => 'Enviado'], 201);
              $this->flashMessage()->add('success', array('message' => 'Email enviado com sucesso.'));
            }
          } else {
            //return $this->json(['message' => 'Por favor, preencha todos os campos!', 'type' => 'warning', 'title' => 'Atenção'], 406);
            $this->flashMessage()->add('danger', array('message' => 'Por favor, preencha todos os campos!'));
          }
        }
        //return $this->json([]);
        return $this->render('/front/fale_conosco.twig', array(
            // 'unidades' => $unidades,
            // 'areas' => $areas,
            // 'vagas' => $vagas,
        ));
      }


      public function NossasVagasTodasSendAction(Request $request){
        if("POST" == $request->getMethod()){
  
          $data = $request->request->all();
          unset($data['send']);
  
          //validar envio do form
          if(!in_array('', $data) && count($data) > 0){
            $message = \Swift_Message::newInstance();
            $message->setSubject('Contato via site');
            $message->setFrom(array($this->get('swiftmailer.options')['from'] => 'Contato Mastelini'));
            $message->setTo(array("pedro.palopoli@hotmail.com"));            
            //$message->setReplyTo(array($data['email'] => $data['nome']));

            //dd($_FILES);
             $message->attach(
                 \Swift_Attachment::fromPath($_FILES['iFile']['tmp_name'])->setFilename($_FILES['iFile']['name'])
                 );
            $message->setBody($this->render('/emails/nossas_vagas_todas.twig', array(
              'data' => $data,
            )), 'text/html');
  
            if (!$this->get('mailer')->send($message)) {
              //return $this->json(['message' => 'Erro ao enviar mensagem, tente novamente!', 'type' => 'error', 'title' => 'Erro'], 400);
              $this->flashMessage()->add('danger', array('message' => 'Erro ao enviar mensagem, tente novamente!'));
            } else {
              //return $this->json(['message' => 'Mensagem enviada com sucesso.', 'type' => 'success', 'title' => 'Enviado'], 201);
              $this->flashMessage()->add('success', array('message' => 'Email enviado com sucesso.'));
            }
          } else {
            //return $this->json(['message' => 'Por favor, preencha todos os campos!', 'type' => 'warning', 'title' => 'Atenção'], 406);
            $this->flashMessage()->add('danger', array('message' => 'Por favor, preencha todos os campos!'));
          }
        }
        //return $this->json([]);
        $unidades = $this->get('db')->fetchAll("SELECT * FROM unidades_type where enabled = 1 ");
        $areas = $this->get('db')->fetchAll("SELECT * FROM areas_vagas  ");
        $vagas = $this->get('db')->fetchAll("SELECT v.*,av.role as area, u.title as unidade FROM vagas_type v 
                                                        inner join areas_vagas av on av.id = v.id_area
                                                        inner join unidades_type u on u.id = v.id_unidade");        
        $this->get('db')->close();
        //dd("testes");

        return $this->render('/front/nossas_vagas.twig', array(
            'unidades' => $unidades,
            'areas' => $areas,
            'vagas' => $vagas,
        ));
      }

      public function NossasVagasUmaSendAction(Request $request){
        if("POST" == $request->getMethod()){
  
          $data = $request->request->all();
          unset($data['send']);
            
          //validar envio do form
          $url_vaga = $data["iURL"];
          $vagas = $this->get('db')->fetchAssoc("SELECT v.*,av.role as area, u.title as unidade FROM vagas_type v 
                                                        inner join areas_vagas av on av.id = v.id_area
                                                        inner join unidades_type u on u.id = v.id_unidade
                                                        where v.url=?",array($url_vaga));
          if(!in_array('', $data) && count($data) > 0){            
            $message = \Swift_Message::newInstance();
            $message->setSubject('Contato via site');
            $message->setFrom(array($this->get('swiftmailer.options')['from'] => 'Contato Mastelini'));
            $message->setTo(array("pedro.palopoli@hotmail.com"));           
            $data["vagas"] = $vagas;
            //$message->setReplyTo(array($data['email'] => $data['nome']));

            //dd($data);
             $message->attach(
                 \Swift_Attachment::fromPath($_FILES['iFile']['tmp_name'])->setFilename($_FILES['iFile']['name'])
                 );
            $message->setBody($this->render('/emails/nossas_vagas_uma.twig', array(
              'data' => $data,
            )), 'text/html');
  
            if (!$this->get('mailer')->send($message)) {
              //return $this->json(['message' => 'Erro ao enviar mensagem, tente novamente!', 'type' => 'error', 'title' => 'Erro'], 400);
              $this->flashMessage()->add('danger', array('message' => 'Erro ao enviar mensagem, tente novamente!'));
            } else {
              //return $this->json(['message' => 'Mensagem enviada com sucesso.', 'type' => 'success', 'title' => 'Enviado'], 201);
              $this->flashMessage()->add('success', array('message' => 'Email enviado com sucesso.'));
            }
          } else {
            //return $this->json(['message' => 'Por favor, preencha todos os campos!', 'type' => 'warning', 'title' => 'Atenção'], 406);
            $this->flashMessage()->add('danger', array('message' => 'Por favor, preencha todos os campos!'));
          }
        }
        //return $this->json([]);
        $unidades = $this->get('db')->fetchAll("SELECT * FROM unidades_type where enabled = 1 ");
        $areas = $this->get('db')->fetchAll("SELECT * FROM areas_vagas  ");
                
        $this->get('db')->close();
        //dd("testes");

        return $this->render('/front/nossas_vagas_interna.twig', array(
            'unidades' => $unidades,
            'areas' => $areas,
            'vagas' => $vagas,
        ));
      }

    

    





}
