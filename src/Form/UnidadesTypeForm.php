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

class UnidadesTypeForm extends AbstractType
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
        $unidades = $options['data'];
        $image_valid = array();
        $builder
            ->add('title', 'text', array(
                'required' => true,
                'label' => 'Cidade',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 4,
                        'max' => 120,
                    )),
                ),
            ))   
            ->add('endereco', 'text', array(
                'required' => true,
                'label' => 'Endereço',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 4,
                        'max' => 120,
                    )),
                ),
            ))    
            ->add('telefone', 'text', array(
                'required' => true,
                'label' => 'Telefone',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 4,
                        'max' => 120,
                    )),
                ),
            ))    
            ->add('whatsapp', 'text', array(
                'required' => true,
                'label' => 'Whatsapp',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 4,
                        'max' => 120,
                    )),
                ),
            ))      
            ->add('atendimento', 'textarea', array(
                'required' => true,
                'label' => 'Atendimento',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 4,
                    )),
                ),
            ))
            ->add('image', 'file', array(
                'required' => !array_key_exists('id', $unidades),
                'label' => 'Imagem da Unidade',
                'constraints' => array(
                    new Assert\Image($image_valid),
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
        return 'unidades_type';
    }
}
