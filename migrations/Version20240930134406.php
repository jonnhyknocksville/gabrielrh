<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240930134406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD kbis_updated_at DATETIME DEFAULT NULL, ADD diplomas_updated_at DATETIME DEFAULT NULL, ADD cv_updated_at DATETIME DEFAULT NULL, ADD criminal_record_updated_at DATETIME DEFAULT NULL, ADD attestation_vigilance_updated_at DATETIME DEFAULT NULL, DROP vigilance');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD vigilance VARCHAR(255) DEFAULT NULL, DROP kbis_updated_at, DROP diplomas_updated_at, DROP cv_updated_at, DROP criminal_record_updated_at, DROP attestation_vigilance_updated_at');
    }
}
