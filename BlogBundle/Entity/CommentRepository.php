<?php

namespace Pelshoff\BlogBundle\Entity;

use Pelshoff\Blog\Model,
	Doctrine\ORM\EntityRepository;

/**
 *
 *
 * @author		Pim Elshoff <pim@pelshoff.com>
 */
class CommentRepository extends EntityRepository implements Model\CommentRepository
{
	/**
	 * @return Comment
	 */
	public function create()
	{
		return new Comment();
	}

	/**
	 * @param \Pelshoff\Blog\Model\Comment $comment
	 */
	public function store(Model\Comment $comment)
	{
		$em = $this->getEntityManager();
		$em->persist($comment);
		$em->flush();
	}

}
