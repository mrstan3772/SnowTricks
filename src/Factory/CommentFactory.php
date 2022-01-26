<?php

namespace App\Factory;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Comment>
 *
 * @method static Comment|Proxy createOne(array $attributes = [])
 * @method static Comment[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Comment|Proxy find(object|array|mixed $criteria)
 * @method static Comment|Proxy findOrCreate(array $attributes)
 * @method static Comment|Proxy first(string $sortedField = 'id')
 * @method static Comment|Proxy last(string $sortedField = 'id')
 * @method static Comment|Proxy random(array $attributes = [])
 * @method static Comment|Proxy randomOrCreate(array $attributes = [])
 * @method static Comment[]|Proxy[] all()
 * @method static Comment[]|Proxy[] findBy(array $attributes)
 * @method static Comment[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Comment[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static CommentRepository|RepositoryProxy repository()
 * @method Comment|Proxy create(array|callable $attributes = [])
 */
final class CommentFactory extends ModelFactory
{
    private UserRepository $userRepository;

    private TrickRepository $trickRepository;

    public function __construct(UserRepository $userRepository, TrickRepository $trickRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->trickRepository = $trickRepository;
        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'comment_content' => self::faker()->paragraphs(5, true),
            'comment_author_id' => self::faker()->numberBetween(1, 10),
            'comment_trick_id' => self::faker()->numberBetween(1, 30),
            'comment_creation_date' => self::faker()->dateTime(), // TODO add DATETIME ORM type manually
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            ->afterInstantiate(
                function (Comment $comment) {
                    $user = $this->userRepository->findOneBy(
                        [
                            'id' => $comment->getCommentAuthorId(),
                        ]
                    );

                    $trick = $this->trickRepository->findOneBy(
                        [
                            'id' => $comment->getCommentTrickId(),
                        ]
                    );

                    $user->addUserComment($comment);
                    $trick->addTrickComment($comment);
                }
            );
    }

    protected static function getClass(): string
    {
        return Comment::class;
    }
}
