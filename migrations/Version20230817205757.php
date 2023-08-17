<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230817205757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE professionals_needs (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, current_job VARCHAR(255) NOT NULL, company VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(10) NOT NULL, motive VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact ADD current_job VARCHAR(255) NOT NULL, ADD is_read TINYINT(1) NOT NULL, CHANGE message message VARCHAR(255) NOT NULL, CHANGE name last_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE professionals_needs');
        $this->addSql('ALTER TABLE contact ADD name VARCHAR(255) NOT NULL, DROP last_name, DROP current_job, DROP is_read, CHANGE message message LONGTEXT NOT NULL');
    }
}
