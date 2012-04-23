<?php

namespace Pelshoff\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository,
	Pelshoff\Blog\Model;

/**
 * @author		Pim Elshoff <pim@procurios.nl>
 */
class ArticleRepository extends EntityRepository implements Model\ArticleRepository
{
	/**
	 * @return \Doctrine\ORM\Query
	 */
	public function getArticleListQuery()
	{
		return $this->getEntityManager()->createQuery('
            SELECT a.name, a.publishTime, a.url, a.leader, COUNT(c.id) AS numberOfComments
            FROM PelshoffBlogBundle:Article a
            LEFT JOIN a.comments c
            GROUP BY a.id
            ORDER BY a.publishTime DESC
        ');
	}

	/**
	 * @param string $url
	 * @return Article
	 */
	public function findArticleByUrl($url)
	{
		try {
			return $this->getEntityManager()->createQuery('
				SELECT article
				FROM PelshoffBlogBundle:Article article
				WHERE article.url = :url
			')->setParameter('url', $url)->getSingleResult();
		} catch (\Doctrine\Orm\NoResultException $e) {
			return null;
		}
	}
}
