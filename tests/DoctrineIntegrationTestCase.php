<?php
namespace Digbang\DoctrineExtensions;

use Digbang\DoctrineExtensions\stubs\mappings\PersonMapping;
use Digbang\DoctrineExtensions\stubs\Person;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use LaravelDoctrine\Fluent\FluentDriver;
use PHPUnit\Framework\TestCase;

abstract class DoctrineIntegrationTestCase extends TestCase
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @return EntityManager
     */
    protected function setUp(): void
    {
        $configuration = $this->metadataConfiguration();

        $conn = [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];

        $this->entityManager = EntityManager::create($conn, $configuration);

        $schema = new SchemaTool($this->entityManager);
        $schema->createSchema([
            $this->entityManager->getClassMetadata(Person::class)
        ]);
    }

    /**
     * @return Configuration
     */
    protected function metadataConfiguration(): Configuration
    {
        $config = Setup::createConfiguration();

        $driver = new FluentDriver([
            PersonMapping::class,
        ]);
        $config->setMetadataDriverImpl($driver);
        return $config;
    }
}
