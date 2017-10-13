<?php
namespace AppBundle\Service;

use FOS\CommentBundle\Entity\CommentManager as BaseCommentManager;
use FOS\CommentBundle\Model\CommentInterface;
use FOS\CommentBundle\Model\ThreadInterface;

/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 13.10.2017
 * Time: 16:55
 */
class CommentManager extends BaseCommentManager
{
    /**
     * Returns a flat array of comments from the specified thread.
     *
     * The sorter parameter should be left alone if you are sorting in the
     * tree methods.
     *
     * @param ThreadInterface $thread
     * @param integer|null    $depth
     * @param string|null     $sorterAlias
     *
     * @return CommentInterface[] An array of commentInterfaces
     */
    public function findCommentsByThread(ThreadInterface $thread, $depth = null, $sorterAlias = null)
    {

        $qb = $this->repository
            ->createQueryBuilder('c')
            ->join('c.thread', 't')
            ->where('t.id = :thread')
            ->andWhere('c.depth = 0')
            ->orderBy('c.ancestors', 'ASC')
            ->setParameter('thread', $thread->getId());


        if (null !== $depth && $depth >= 0) {
            // Queries for an additional level so templates can determine
            // if the final 'depth' layer has children.

            $qb->andWhere('c.depth < :depth')
                ->setParameter('depth', $depth + 1);
        }

        $comments = $qb
            ->getQuery()
            ->execute();

        if (null !== $sorterAlias) {
            $sorter = $this->sortingFactory->getSorter($sorterAlias);
            $comments = $sorter->sortFlat($comments);
        }

        return $comments;
    }

}