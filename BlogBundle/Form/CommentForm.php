<?php
namespace Pelshoff\BlogBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilder;

/**
 *
 *
 * @author		Pim Elshoff <pim@pelshoff.com>
 */
class CommentForm extends AbstractType
{
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    function getName()
    {
        return 'comment';
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('message', 'textarea', array(
            'label' => 'Comment',
            'attr' => array(
                'class' => 'input-xlarge',
            )
        ));
        $builder->add('name', 'text', array(
            'label' => 'Your name',
            'attr' => array(
                'class' => 'input-xlarge',
            )
        ));
        $builder->add('emailAddress', 'email', array(
            'label' => 'Your e-mail address',
            'help' => 'Your e-mail is only used for generating the gravatar image',
            'required' => false,
            'attr' => array(
                'class' => 'input-xlarge',
            )
        ));
        $builder->add('website', 'url', array(
            'label' => 'Your website',
            'help' => 'For example, your twitter or blog',
            'required' => false,
            'attr' => array(
                'class' => 'input-xlarge',
            )
        ));
//        $builder->add('storeInCookie', 'checkbox', array(
//            'label' => 'Store my info in a cookie',
//            'help' => 'Your name, e-mail and website will be saved on this computer for a year or until cookies are cleared',
//            'required' => false,
//            'property_path' => false,
//        ));
        $builder->add('captcha', 'genemu_captcha', array(
            'label' => 'Captcha',
            'help' => 'I\'m sorry this is necessary, but I get a lot of spam if I don\'t',
            'property_path' => false,
            'attr' => array(
                'class' => 'captcha'
            )
        ));
    }
}
