<?php
namespace Pelshoff\BlogBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension,
	Symfony\Component\Form\FormInterface,
	Symfony\Component\Form\FormView,
	Symfony\Component\Form\FormBuilder;

/**
 *
 *
 * @author		Pim Elshoff <pim@pelshoff.com>
 */
class HelpMessageTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->setAttribute('help', $options['help']);
    }

    public function buildView(FormView $view, FormInterface $form)
    {
        $view->set('help', $form->getAttribute('help'));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'help' => null,
        );
    }

    public function getExtendedType()
    {
        return 'field';
    }
}
