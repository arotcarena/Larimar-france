<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230626191520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD meta_description VARCHAR(255) DEFAULT NULL, ADD en_meta_description VARCHAR(255) DEFAULT NULL, ADD total_dimension INT DEFAULT NULL, ADD cabochon_dimension INT DEFAULT NULL, ADD weight INT DEFAULT NULL, ADD finger_size INT DEFAULT NULL, ADD material VARCHAR(255) DEFAULT NULL, ADD color VARCHAR(255) DEFAULT NULL, ADD en_color VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP meta_description, DROP en_meta_description, DROP total_dimension, DROP cabochon_dimension, DROP weight, DROP finger_size, DROP material, DROP color, DROP en_color');
        
    }
}
