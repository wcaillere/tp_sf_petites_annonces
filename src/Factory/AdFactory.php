<?php

namespace App\Factory;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Ad>
 *
 * @method        Ad|Proxy create(array|callable $attributes = [])
 * @method static Ad|Proxy createOne(array $attributes = [])
 * @method static Ad|Proxy find(object|array|mixed $criteria)
 * @method static Ad|Proxy findOrCreate(array $attributes)
 * @method static Ad|Proxy first(string $sortedField = 'id')
 * @method static Ad|Proxy last(string $sortedField = 'id')
 * @method static Ad|Proxy random(array $attributes = [])
 * @method static Ad|Proxy randomOrCreate(array $attributes = [])
 * @method static AdRepository|RepositoryProxy repository()
 * @method static Ad[]|Proxy[] all()
 * @method static Ad[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Ad[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Ad[]|Proxy[] findBy(array $attributes)
 * @method static Ad[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Ad[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class AdFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'author' => UserFactory::new(),
            'category' => CategoryFactory::new(),
            'city' => self::faker()->city(),
            'status' => StatusFactory::new(),
            'text' => self::faker()->text(),
            'title' => self::faker()->text(20),
            'zip_code' => substr(self::faker()->postcode(),0,5)
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Ad $ad): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Ad::class;
    }
}
