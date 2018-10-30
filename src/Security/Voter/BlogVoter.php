<?php

namespace App\Security\Voter;

use App\Entity\Blog;
use App\Entity\BlogComment;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;



class BlogVoter extends Voter
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        //dump($attribute);
        //dump($subject);
        return is_numeric($attribute) || ($subject instanceof Blog) || ($subject instanceof BlogComment);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        //dump($token->getRoles());
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        //dump($attribute);
        //dump($user->getId());

        if ($attribute == $user->getId()){
            return true;
        }

        if (!$subject->getUser()){
            return true;
        }
/**

        dump($subject->getUser()->getId());
        dump($user->getId());
/**/
        if ($subject->getUser()->getId() == $user->getId()){
            return true;
        }
/**/

        return false;
    }
}
