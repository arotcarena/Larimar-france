<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230608214523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD en_name VARCHAR(255) NOT NULL, ADD en_slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product ADD en_designation VARCHAR(255) NOT NULL, ADD en_slug VARCHAR(255) NOT NULL, ADD description LONGTEXT DEFAULT NULL, ADD en_description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE sub_category ADD en_name VARCHAR(255) NOT NULL, ADD en_slug VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP en_name, DROP en_slug');
        $this->addSql('ALTER TABLE product DROP en_designation, DROP en_slug, DROP description, DROP en_description');
        $this->addSql('ALTER TABLE sub_category DROP en_name, DROP en_slug');

    }
}
