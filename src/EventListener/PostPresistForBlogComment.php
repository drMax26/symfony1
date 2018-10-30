<?php
// src/EventListener/SearchIndexer.php
namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\BlogComment;
use Symfony\Component\Security\Core\Security;

class PostPresistForBlogComment
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $blog = $args->getObject();

        if ($blog instanceof BlogComment) {
            $blog->setUser($this->security->getToken()->getUser());
        }

        // only act on some "Product" entity
        if (!$entity instanceof BlogComment) {
            return;
        }

        $entityManager = $args->getEntityManager();
    }
}