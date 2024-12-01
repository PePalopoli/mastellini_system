<?php

/**
 *  (c) Rogério Adriano da Silva <rogerioadris.silva@gmail.com>.
 */
namespace Audi\AudiSystem\Controller\Security;

use Audi\AudiSystem\Controller\ContainerAware;
use Audi\AudiSystem\Form\VagasTypeForm;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class VagasTypeController extends ContainerAware
{
    /**
     * Lista.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $vagas_type = $this->db()->fetchAll('SELECT * FROM `vagas_type`');
        

        return $this->render('list.twig', array(
            'data' => $vagas_type,
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
        $vagas_type = array(
            'areas_choices' => $this->getChoiceAreas(),
            'unidades_choices' => $this->getChoiceUnidades(),
        );

        $form = $this->createForm(new VagasTypeForm(), $vagas_type, array(
            'action' => $this->get('url_generator')->generate('s_vagas_type_create'),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $data['url'] = $this->get('slugify')->slugify($data['url']);

                try {
                    $insert_query = 'INSERT INTO `vagas_type` (`vaga`, `url`, `body`, `enabled`,`id_area`,`id_unidade`, `created_at`, `updated_at`) VALUES (?,?,?, ?, ?, ?, NOW(), NOW())';
                    $this->db()->executeUpdate($insert_query, array($data['vaga'], $data['url'], $data['body'], $data['enabled'], $data['id_area'], $data['id_unidade']));

                    $this->flashMessage()->add('success', array('message' => 'Adicionado com sucesso.'));

                    return $this->redirect('s_vagas_type');
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
        $vagas_type = $this->get('db')->fetchAssoc('SELECT * FROM `vagas_type` WHERE `id` = ? LIMIT 1;', array($id));

        if ($vagas_type === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mais a pagina não foi encontrada.'));

            return $this->redirect('s_vagas_type');
        }

        $vagas_type['areas_choices'] = $this->getChoiceAreas();
        $vagas_type['unidades_choices'] = $this->getChoiceUnidades();        

        $form = $this->createForm(new VagasTypeForm(), $vagas_type, array(
            'action' => $this->get('url_generator')->generate('s_vagas_type_edit', array('id' => $id)),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $data['url'] = $this->get('slugify')->slugify($data['url']);

                try {
                    $update_query = 'UPDATE `vagas_type` SET `vaga` = ?, `url` = ?, `body` = ?, `enabled` = ?,`id_area` = ?,`id_unidade` = ?, `updated_at` = NOW() WHERE `id` = ? LIMIT 1';
                    $this->get('db')->executeUpdate($update_query, array($data['vaga'], $data['url'], $data['body'], $data['enabled'],$data['id_area'],$data['id_unidade'], $data['id']));

                    $this->flashMessage()->add('success', array('message' => 'Editado com sucesso.'));

                    return $this->redirect('s_vagas_type');
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
        $row_sql = $this->get('db')->fetchAssoc('SELECT * FROM `vagas_type` WHERE `id` = ? LIMIT 1;', array($id));

        if ($row_sql === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mais não foi encontrado.'));
        } else {
            $this->get('db')->executeUpdate('DELETE FROM `vagas_type` WHERE `id` = ?', array($id));

            $this->flashMessage()->add('success', array('message' => 'Deletado com sucesso.'));
        }

        return $this->redirect('s_vagas_type');
    }

     /**
     * @return array
     */
    private function getChoiceAreas()
    {
        $roles = array();

        $roles_fetch = $this->db()->fetchAll('SELECT `id`, `role` FROM `areas_vagas`');

        foreach ($roles_fetch as $role) {
            $roles[$role['id']] = $role['role'];
        }

        return $roles;
    }

    /**
     * @return array
     */
    private function getChoiceUnidades()
    {
        $roles = array();

        $roles_fetch = $this->db()->fetchAll('SELECT `id`, `title` FROM `unidades_type`');

        foreach ($roles_fetch as $role) {
            $roles[$role['id']] = $role['title'];
        }

        return $roles;
    }
}
