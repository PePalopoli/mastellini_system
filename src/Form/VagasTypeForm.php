<?php

/**
 *  (c) Rogério Adriano da Silva <rogerioadris.silva@gmail.com>.
 */
namespace Audi\AudiSystem\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Audi\AudiSystem\Form\Type\StatusType;

class VagasTypeForm extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vaga', 'text', array(
                'required' => true,
                'label' => 'Nome do Cargo',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 4,
                        'max' => 120,
                    )),
                ),
            ))
            ->add('url', 'text', array(
                'required' => false,
                'label' => 'Url',
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
            ))
            ->add('body', 'textarea', array(
                'required' => true,
                'label' => 'Breve descrição da vaga',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 4,
                    )),
                ),
            ))
            ->add('id_area', 'choice', array(
                'label' => 'Área',
                'multiple' => false,
                'choices' => array_key_exists('areas_choices', $options['data']) ? $options['data']['areas_choices'] : array(),
                'required' => false,
                'constraints' => array(
                ),
            ))

            ->add('id_unidade', 'choice', array(
                'label' => 'Unidade',
                'multiple' => false,
                'choices' => array_key_exists('unidades_choices', $options['data']) ? $options['data']['unidades_choices'] : array(),
                'required' => false,
                'constraints' => array(
                ),
            ))

            
            // enabled
            ->add('enabled', new StatusType())
        ;
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        //
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'vagas_type';
    }
}
