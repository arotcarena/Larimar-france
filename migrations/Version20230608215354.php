<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230608215354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category CHANGE en_name en_name VARCHAR(255) DEFAULT NULL, CHANGE en_slug en_slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE en_designation en_designation VARCHAR(255) DEFAULT NULL, CHANGE en_slug en_slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sub_category CHANGE en_name en_name VARCHAR(255) DEFAULT NULL, CHANGE en_slug en_slug VARCHAR(255) DEFAULT NULL');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category CHANGE en_name en_name VARCHAR(255) NOT NULL, CHANGE en_slug en_slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product CHANGE en_designation en_designation VARCHAR(255) NOT NULL, CHANGE en_slug en_slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sub_category CHANGE en_name en_name VARCHAR(255) NOT NULL, CHANGE en_slug en_slug VARCHAR(255) NOT NULL');

    }
}
