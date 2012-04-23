<?php
namespace Pelshoff\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
	Symfony\Component\HttpFoundation\Response,
	Symfony\Component\Form\Form,
	Pelshoff\BlogBundle\Form\FormListener,
	Pelshoff\Blog\Comment\PostCommentContext,
	Pelshoff\Blog\Comment\PostCommentRequest,
	Pelshoff\Blog\Comment\PostCommentResult;

/**
 * @author		Pim Elshoff <pim@pelshoff.com>
 */
class DefaultController extends Controller
{
    public function indexAction()
    {
        $query = $this->getArticleRepository()->getArticleListQuery();
        $pagination = $this->get('knp_paginator')->paginate($query, $this->get('request')->query->get('page', 1), 5);
        return $this->render('PelshoffBlogBundle:Default:index.html.twig', array('pagination' => $pagination));
    }

    public function articleAction(Request $request, $article)
    {
		$form = $this->createForm(new \Pelshoff\BlogBundle\Form\CommentForm());
		$context = $this->buildContext($form, $request);
		$contextRequest = new PostCommentRequest($article, $request->getClientIp());
		$contextResult = $context->execute(new FormListener($form, $request), $contextRequest);
		return $this->buildResponse($contextResult, $request, $form, $article);
    }

	/**
	 * @return \Pelshoff\Blog\Comment\PostCommentContext
	 */
	private function buildContext()
	{
		$articleRepository = $this->getArticleRepository();
		$commentRepository = $this->getCommentRepository();
		return new PostCommentContext($articleRepository, $commentRepository);
	}

	/**
	 * @param \Pelshoff\Blog\Comment\PostCommentResult $result
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param \Symfony\Component\Form\Form $form
	 * @param string $article
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	private function buildResponse(PostCommentResult $result, Request $request, Form $form, $article)
	{
		if ($this->shouldReturn404Response($result)) {
			return $this->get404Response($article);
		}
		if ($this->shouldReturnDefaultResponse($result, $request)) {
			return $this->getDefaultResponse($form, $result->getArticle());
		}
		if ($this->shouldReturnFailedAjaxResponse($result, $request)) {
			return $this->getAjaxResponse($form, $result->getArticle(), false);
		}
		if ($this->shouldReturnAjaxResponse($request)) {
			$form = $this->createForm(new \Pelshoff\BlogBundle\Form\CommentForm());
			return $this->getAjaxResponse($form, $result->getArticle(), true, $result->getComment());
		}
		return $this->redirect($request->getPathInfo());
	}

	/**
	 * @param \Pelshoff\Blog\Comment\PostCommentResult $result
	 * @return bool
	 */
	private function shouldReturn404Response(PostCommentResult $result)
	{
		return null == $result->getArticle();
	}

	/**
	 * @param \Pelshoff\Blog\Comment\PostCommentResult $result
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return bool
	 */
	private function shouldReturnDefaultResponse(PostCommentResult $result, Request $request)
	{
		return !$result->isOpened() || !$result->isSuccess() && !$request->isXmlHttpRequest();
	}

	/**
	 * @param \Pelshoff\Blog\Comment\PostCommentResult $result
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return bool
	 */
	private function shouldReturnFailedAjaxResponse(PostCommentResult $result, Request $request)
	{
		return !$result->isSuccess() && $request->isXmlHttpRequest();
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return bool
	 */
	private function shouldReturnAjaxResponse(Request $request)
	{
		return $request->isXmlHttpRequest();
	}

	/**
	 * @return \Doctrine\ORM\EntityManager
	 */
	private function getEntityManager()
	{
		return $this->getDoctrine()->getEntityManager();
	}

	/**
	 * @return \Pelshoff\BlogBundle\Entity\ArticleRepository
	 */
	private function getArticleRepository()
	{
		return $this->getEntityManager()->getRepository('PelshoffBlogBundle:Article');
	}

	/**
	 * @return \Pelshoff\BlogBundle\Entity\CommentRepository
	 */
	private function getCommentRepository()
	{
		return $this->getEntityManager()->getRepository('PelshoffBlogBundle:Comment');
	}

	/**
	 * @param string $article
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	private function get404Response($article)
	{
		return $this->render('PelshoffBlogBundle:Default:404.html.twig', array('article' => $article), new Response('', 404));
	}

	/**
	 * @param \Symfony\Component\Form\Form $form
	 * @param \Pelshoff\Blog\Model\Article $article
	 * @param bool $success
	 * @param \Pelshoff\Blog\Model\Comment $comment
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	private function getAjaxResponse($form, $article, $success, $comment = null)
	{
		$data = array(
			'success' => $success,
			'comment' => $comment ? $this->renderView('PelshoffBlogBundle:Default:comment.html.twig', array('comment' => $comment)) : array(),
			'form' => $this->renderView('PelshoffBlogBundle:Default:comment-form.html.twig', array(
				'article' => $article,
				'form' => $form->createView(),
			))
		);
		return new Response(json_encode($data), 200);
	}

	/**
	 * @param \Symfony\Component\Form\Form $form
	 * @param \Pelshoff\Blog\Model\Article $article
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	private function getDefaultResponse($form, $article)
	{
		$returnValues = array(
			'article' => $article,
			'title' => $article->getName(),
			'form' => $form->createView(),
		);
		return $this->render('PelshoffBlogBundle:Default:article.html.twig', $returnValues);
	}
}
