<?php

namespace App\Factory;

use App\Entity\TrickAttachment;
use App\Repository\TrickAttachmentRepository;
use App\Repository\TrickRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<TrickAttachment>
 *
 * @method static TrickAttachment|Proxy createOne(array $attributes = [])
 * @method static TrickAttachment[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static TrickAttachment|Proxy find(object|array|mixed $criteria)
 * @method static TrickAttachment|Proxy findOrCreate(array $attributes)
 * @method static TrickAttachment|Proxy first(string $sortedField = 'id')
 * @method static TrickAttachment|Proxy last(string $sortedField = 'id')
 * @method static TrickAttachment|Proxy random(array $attributes = [])
 * @method static TrickAttachment|Proxy randomOrCreate(array $attributes = [])
 * @method static TrickAttachment[]|Proxy[] all()
 * @method static TrickAttachment[]|Proxy[] findBy(array $attributes)
 * @method static TrickAttachment[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static TrickAttachment[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static TrickAttachmentRepository|RepositoryProxy repository()
 * @method TrickAttachment|Proxy create(array|callable $attributes = [])
 */
final class TrickAttachmentFactory extends ModelFactory
{
    private TrickRepository $trickRepository;

    public function __construct(TrickRepository $trickRepository)
    {
        parent::__construct();
        $this->trickRepository = $trickRepository;
        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        $path_list = ['trick_002.jpg', 'trick_003.jpg', 'trick_001.jpg', 'Ollie.jpg', 'indy-grabs.jpeg', 'frontside-180s.jpg', 'butters.jpg'];
        $rand_val = $path_list[array_rand($path_list)];

        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'ta_trick_id' => self::faker()->numberBetween(1, 30),
            'ta_type' => 'image',
            'ta_original_filename' => $rand_val,
            'ta_filename' => $rand_val /* uniqid().'-'.$rand_val */,
            'ta_mime_type' => 'image/jpeg'
        ];

         // FOR VIDEOS

        // $path_list = ['001.webm', '002.webm', '003.webm', '004.webm', '005.webm', '006.webm', '007.webm', '008.webm', '009.webm'];
        // $rand_val = $path_list[array_rand($path_list)];

        // return [
        //     // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
        //     'ta_trick_id' => self::faker()->numberBetween(38, 68),
        //     'ta_type' => 'video',
        //     'ta_original_filename' => $rand_val,
        //     'ta_filename' => $rand_val /* uniqid().'-'.$rand_val */,
        //     'ta_mime_type' => 'video/webm'
        // ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            ->afterInstantiate(
                function (TrickAttachment $trickAttachment) {
                    $trick = $this->trickRepository->findOneBy(
                        [
                            'id' => $trickAttachment->getTaTrickId(),
                        ]
                    );

                    $trick->addTrickAttachment($trickAttachment);
                }
            )
            // ->afterInstantiate(function(TrickAttachment $trickAttachment): void {})
        ;
    }

    protected static function getClass(): string
    {
        return TrickAttachment::class;
    }
}
