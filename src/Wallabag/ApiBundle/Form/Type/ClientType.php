<?php

namespace Wallabag\ApiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'developer.client.form.name_label'])
            ->add('redirect_uris', UrlType::class, [
                'required' => false,
                'label' => 'developer.client.form.redirect_uris_label',
                'property_path' => 'redirectUris',
            ])
            ->add('save', SubmitType::class, ['label' => 'developer.client.form.save_label'])
        ;

        $builder->get('redirect_uris')
            ->addModelTransformer(new CallbackTransformer(
                function ($originalUri) {
                    return $originalUri;
                },
                function ($submittedUri) {
                    return [$submittedUri];
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Wallabag\ApiBundle\Entity\Client',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'client';
    }
}
