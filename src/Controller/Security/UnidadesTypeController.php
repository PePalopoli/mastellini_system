<?php

/**
 *  (c) Rogério Adriano da Silva <rogerioadris.silva@gmail.com>.
 */
namespace Audi\AudiSystem\Controller\Security;

use Audi\AudiSystem\Controller\ContainerAware;
use Audi\AudiSystem\Form\UnidadesTypeForm;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class UnidadesTypeController extends ContainerAware
{
    /**
     * Lista.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $unidades_type = $this->db()->fetchAll('SELECT * FROM `unidades_type`');

        

        return $this->render('list.twig', array(
            'data' => $unidades_type,
        ));
    }

    /**
     * Adicionar.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $unidades_type = array();
        $unidades_type['atendimento']="<p>Seg - Sex: 7h00 - 17h00 <br> Sáb: 7h00 - 11h00</p>";

        $form = $this->createForm(new UnidadesTypeForm(), $unidades_type, array(
            'action' => $this->get('url_generator')->generate('s_unidades_type_create'),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                if ($data['image'] instanceof UploadedFile) {
                    $image = $data['image'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/unidades');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);

                    $data['image'] = $image_name;
                }

                try {
                    $insert_query = 'INSERT INTO `unidades_type` (`title`, `endereco`, `atendimento`, `enabled`,`telefone`,`whatsapp`,`image`, `created_at`, `updated_at`) VALUES (?,?,?,?, ?, ?, ?, NOW(), NOW())';
                    $this->db()->executeUpdate($insert_query, array($data['title'], $data['endereco'], $data['atendimento'], $data['enabled'], $data['telefone'], $data['whatsapp'],$data['image']));

                    $this->flashMessage()->add('success', array('message' => 'Adicionado com sucesso.'));

                    return $this->redirect('s_unidades_type');
                } catch (UniqueConstraintViolationException $e) {
                    $this->flashMessage()->add('danger', array('message' => 'Url já está em uso.'));
                }
            }
        }

        return $this->render('create.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Editar.
     *
     * @param Request $request
     * @param mixed   $id
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request, $id)
    {
        $unidades_type = $this->get('db')->fetchAssoc('SELECT * FROM `unidades_type` WHERE `id` = ? LIMIT 1;', array($id));

        if ($unidades_type === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mais a pagina não foi encontrada.'));

            return $this->redirect('s_unidades_type');
        }

        $image_name = $unidades_type['image'];
        unset($unidades_type['image']);

        $form = $this->createForm(new UnidadesTypeForm(), $unidades_type, array(
            'action' => $this->get('url_generator')->generate('s_unidades_type_edit', array('id' => $id)),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                if ($data['image'] instanceof UploadedFile) {
                    $image = $data['image'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/unidades');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);

                }
                $data['image'] = $image_name;

                try {
                    $update_query = 'UPDATE `unidades_type` SET `title` = ?, `endereco` = ?, `atendimento` = ?, `enabled` = ?,`telefone` = ?,`whatsapp` = ?,`image` = ?, `updated_at` = NOW() WHERE `id` = ? LIMIT 1';
                    $this->get('db')->executeUpdate($update_query, array($data['title'], $data['endereco'], $data['atendimento'], $data['enabled'],$data['telefone'],$data['whatsapp'],$data['image'], $data['id']));

                    $this->flashMessage()->add('success', array('message' => 'Editado com sucesso.'));

                    return $this->redirect('s_unidades_type');
                } catch (UniqueConstraintViolationException $e) {
                    $this->flashMessage()->add('danger', array('message' => 'Url já está em uso.'));
                }
            }
        }

        return $this->render('edit.twig', array(
            'form' => $form->createView(),
            'id' => $id,
        ));
    }

    /**
     * Deletar.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $id = $request->request->get('id');
        $row_sql = $this->get('db')->fetchAssoc('SELECT * FROM `unidades_type` WHERE `id` = ? LIMIT 1;', array($id));

        if ($row_sql === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mais não foi encontrado.'));
        } else {
            $this->get('db')->executeUpdate('DELETE FROM `unidades_type` WHERE `id` = ?', array($id));

            $this->flashMessage()->add('success', array('message' => 'Deletado com sucesso.'));
        }

        return $this->redirect('s_unidades_type');
    }
}
