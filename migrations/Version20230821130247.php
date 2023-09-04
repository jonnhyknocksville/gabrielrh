<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230821130247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE courses (id INT AUTO_INCREMENT NOT NULL, theme_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_A9A55A4C59027487 (theme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE courses_details (id INT AUTO_INCREMENT NOT NULL, heading VARCHAR(255) NOT NULL, title_introduction VARCHAR(255) NOT NULL, introduction JSON NOT NULL, objectives JSON NOT NULL, learning_path JSON NOT NULL, public JSON NOT NULL, requirements JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE themes (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, long_description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C59027487 FOREIGN KEY (theme_id) REFERENCES themes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4C59027487');
        $this->addSql('DROP TABLE courses');
        $this->addSql('DROP TABLE courses_details');
        $this->addSql('DROP TABLE themes');
    }
}
