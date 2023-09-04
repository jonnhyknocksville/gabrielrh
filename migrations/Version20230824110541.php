<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230824110541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clients ADD person_in_charge VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE mission ADD course_id INT DEFAULT NULL, DROP course');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
        $this->addSql('CREATE INDEX IDX_9067F23C591CC992 ON mission (course_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clients DROP person_in_charge');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C591CC992');
        $this->addSql('DROP INDEX IDX_9067F23C591CC992 ON mission');
        $this->addSql('ALTER TABLE mission ADD course VARCHAR(255) NOT NULL, DROP course_id');
    }
}
