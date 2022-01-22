<?php

namespace App\Security;

use App\Entity\Trick;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter
{
    public const DELETE = 'delete';
    public const EDIT = 'edit';
    public const SHOW = 'show';

    /**
     * {@inheritdoc}
     */
    protected function supports(string $attribute, $subject): bool
    {
        // this voter is only executed for three specific permissions on Post objects
        return $subject instanceof Trick && \in_array($attribute, [self::SHOW, self::EDIT, self::DELETE], true);
    }

    /**
     * {@inheritdoc}
     *
     * @param Trick $trick
     */
    protected function voteOnAttribute(string $attribute, $trick, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $user === $trick->getTrickAuthor();
    }
}
