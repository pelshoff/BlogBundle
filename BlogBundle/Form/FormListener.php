<?php
namespace Pelshoff\BlogBundle\Form;

use Symfony\Component\HttpFoundation\Request,
	Symfony\Component\Form\Form,
	Pelshoff\Blog\UserEventListener;

/**
 *
 *
 * @author		Pim Elshoff <pim@pelshoff.com>
 */
class FormListener implements UserEventListener
{
	/**
	 * @var Form
	 */
	private $form;

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @param \Symfony\Component\Form\Form $form
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 */
	public function __construct(Form $form, Request $request)
	{
		$this->form = $form;
		$this->request = $request;
	}

	/**
	 * @return bool
	 */
	public function isSubmitted()
	{
		return $this->request->getMethod() == 'POST' && $this->form->bindRequest($this->request);
	}

	/**
	 * @return bool
	 */
	public function isValid()
	{
		return $this->form->isValid();
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->form->getData();
	}
}
