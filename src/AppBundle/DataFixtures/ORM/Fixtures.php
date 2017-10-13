<?php
/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 13.10.2017
 * Time: 17:03
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Page;
use AppBundle\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\CommentBundle\Model\CommentInterface;

/**
 * Class Fixtures
 * @package AppBundle\DataFixtures\ORM
 */
class Fixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $page = new Page();
            $page->setId($i + 1);
            $page->setTitle('Page ' . $i);
            $page->setText('Page content for page ' . $i);
            $page->setCommentable(true);
            $page->setPermalink('');

            $manager->persist($page);

            $prevComment = null;
            for ($j = 0; $j < 25; $j++) {
                $comment = new Comment();
                $comment->setBody('Comment #'. $j .' text from author for page content '. $i);
                $comment->setCreatedAt(new \DateTime('-'. $j .' days'));
                $comment->setThread($page);
                $comment->setState(CommentInterface::STATE_VISIBLE);
                $manager->persist($comment);
                if ($prevComment && $j % 5 === 0) {
                    $manager->flush();
                    $comment->setParent($prevComment);
                }

                $prevComment = $comment;
            }
        }

        $manager->flush();
    }
}