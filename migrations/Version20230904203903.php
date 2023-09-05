<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230904203903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE courses CHANGE introduction introduction JSON DEFAULT NULL, CHANGE objectives objectives JSON DEFAULT NULL, CHANGE learning_path learning_path JSON DEFAULT NULL, CHANGE public public JSON DEFAULT NULL, CHANGE requirements requirements JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE courses CHANGE introduction introduction JSON NOT NULL, CHANGE objectives objectives JSON NOT NULL, CHANGE learning_path learning_path JSON NOT NULL, CHANGE public public JSON NOT NULL, CHANGE requirements requirements JSON NOT NULL');
    }
}
