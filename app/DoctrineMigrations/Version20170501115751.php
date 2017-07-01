<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Add site credential table to store username & password for some website (behind authentication or paywall).
 */
class Version20170501115751 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->skipIf($schema->hasTable($this->getTable('site_credential')), 'It seems that you already played this migration.');

        $table = $schema->createTable($this->getTable('site_credential'));
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('user_id', 'integer');
        $table->addColumn('host', 'string', ['length' => 255]);
        $table->addColumn('username', 'text');
        $table->addColumn('password', 'text');
        $table->addColumn('createdAt', 'datetime');
        $table->addIndex(['user_id'], 'idx_user');
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint($this->getTable('user'), ['user_id'], ['id'], [], 'fk_user');

        if ('postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $schema->dropSequence('site_credential_id_seq');
            $schema->createSequence('site_credential_id_seq');
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable($this->getTable('site_credential'));
    }

    private function getTable($tableName)
    {
        return $this->container->getParameter('database_table_prefix') . $tableName;
    }
}
