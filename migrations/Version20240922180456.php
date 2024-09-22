<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240922180456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training ADD logo VARCHAR(255) DEFAULT NULL, ADD heading VARCHAR(255) DEFAULT NULL, ADD title_introduction VARCHAR(255) DEFAULT NULL, ADD introduction JSON DEFAULT NULL, ADD objectives JSON DEFAULT NULL, ADD learning_path JSON DEFAULT NULL, ADD public JSON DEFAULT NULL, ADD requirements JSON DEFAULT NULL, ADD video_introduction VARCHAR(255) DEFAULT NULL, ADD show_on_web TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training DROP logo, DROP heading, DROP title_introduction, DROP introduction, DROP objectives, DROP learning_path, DROP public, DROP requirements, DROP video_introduction, DROP show_on_web');
    }
}
