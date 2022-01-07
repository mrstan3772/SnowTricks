<?php

namespace App\Factory;

use App\Entity\Trick;
use App\Entity\User;
use App\Repository\GroupRepository;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Trick>
 *
 * @method static Trick|Proxy createOne(array $attributes = [])
 * @method static Trick[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Trick|Proxy find(object|array|mixed $criteria)
 * @method static Trick|Proxy findOrCreate(array $attributes)
 * @method static Trick|Proxy first(string $sortedField = 'id')
 * @method static Trick|Proxy last(string $sortedField = 'id')
 * @method static Trick|Proxy random(array $attributes = [])
 * @method static Trick|Proxy randomOrCreate(array $attributes = [])
 * @method static Trick[]|Proxy[] all()
 * @method static Trick[]|Proxy[] findBy(array $attributes)
 * @method static Trick[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Trick[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static TrickRepository|RepositoryProxy repository()
 * @method Trick|Proxy create(array|callable $attributes = [])
 */
final class TrickFactory extends ModelFactory
{
    private UserRepository $userRepository;

    private GroupRepository $groupRepository;

    public function __construct(UserRepository $userRepository, GroupRepository $groupRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'trick_author_id' => self::faker()->numberBetween(39, 39),
            'trick_name' => self::faker()->words(2, true),
            'trick_description' => self::faker()->paragraphs(5, true),
            'trick_thumbnail' => 'trick_003.jpg',
            'trick_group_id' => self::faker()->numberBetween(41, 45),
            'trick_creation_date' => self::faker()->dateTime(), // TODO add DATETIME ORM type manually
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            ->afterInstantiate(
                function (Trick $trick) {
                    $user = $this->userRepository->findOneBy(
                        [
                            'id' => $trick->getTrickAuthorId(),
                        ]
                    );

                    $grou = $this->userRepository->findOneBy(
                        [
                            'id' => $trick->getTrickAuthorId(),
                        ]
                    );
                    // $trick->setTrickAuthorId(39);
                    $user->addUserTrick($trick);
                }
            );
    }

    protected static function getClass(): string
    {
        return Trick::class;
    }
}
