<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923155124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mission CHANGE mission_reference mission_reference VARCHAR(255) DEFAULT NULL, CHANGE time_table time_table VARCHAR(255) DEFAULT NULL, CHANGE nbr_days nbr_days INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mission CHANGE mission_reference mission_reference VARCHAR(255) NOT NULL, CHANGE time_table time_table VARCHAR(255) NOT NULL, CHANGE nbr_days nbr_days INT NOT NULL');
    }
}
