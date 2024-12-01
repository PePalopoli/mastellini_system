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

class ExamesTypeForm extends AbstractType
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
            ->add('title', 'text', array(
                'required' => true,
                'label' => 'Nome do Exame',
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
            ->add('orientacoes', 'textarea', array(
                'required' => true,
                'label' => 'Orientações',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 4,
                    )),
                ),
            ))
            ->add('observacoes', 'textarea', array(
                'required' => true,
                'label' => 'Observações',
                'constraints' => array(
                    
                    new Assert\Length(array(
                        'min' => 0,
                    )),
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
        return 'exames_type';
    }
}
