<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230825104135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jobs ADD course_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC5591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
        $this->addSql('CREATE INDEX IDX_A8936DC5591CC992 ON jobs (course_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC5591CC992');
        $this->addSql('DROP INDEX IDX_A8936DC5591CC992 ON jobs');
        $this->addSql('ALTER TABLE jobs DROP course_id');
    }
}
