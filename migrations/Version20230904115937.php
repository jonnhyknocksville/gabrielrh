<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230904115937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD updated_at DATE NOT NULL, ADD namer_kibs VARCHAR(255) NOT NULL, ADD namer_vigilance VARCHAR(255) NOT NULL, ADD namer_identity VARCHAR(255) NOT NULL, ADD namer_diplomas VARCHAR(255) NOT NULL, ADD namer_cv VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP updated_at, DROP namer_kibs, DROP namer_vigilance, DROP namer_identity, DROP namer_diplomas, DROP namer_cv');
    }
}
