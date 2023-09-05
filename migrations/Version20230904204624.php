<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230904204624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE diplomas diplomas VARCHAR(255) DEFAULT NULL, CHANGE cv cv VARCHAR(255) DEFAULT NULL, CHANGE kbis kbis VARCHAR(255) DEFAULT NULL, CHANGE vigilance vigilance VARCHAR(255) DEFAULT NULL, CHANGE identity identity VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE kbis kbis VARCHAR(255) NOT NULL, CHANGE vigilance vigilance VARCHAR(255) NOT NULL, CHANGE identity identity VARCHAR(255) NOT NULL, CHANGE diplomas diplomas VARCHAR(255) NOT NULL, CHANGE cv cv VARCHAR(255) NOT NULL');
    }
}
