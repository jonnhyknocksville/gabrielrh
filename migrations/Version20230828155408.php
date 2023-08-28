<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230828155408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE professionals_needs ADD theme_id INT DEFAULT NULL, DROP theme');
        $this->addSql('ALTER TABLE professionals_needs ADD CONSTRAINT FK_A8C2734659027487 FOREIGN KEY (theme_id) REFERENCES themes (id)');
        $this->addSql('CREATE INDEX IDX_A8C2734659027487 ON professionals_needs (theme_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE professionals_needs DROP FOREIGN KEY FK_A8C2734659027487');
        $this->addSql('DROP INDEX IDX_A8C2734659027487 ON professionals_needs');
        $this->addSql('ALTER TABLE professionals_needs ADD theme VARCHAR(255) NOT NULL, DROP theme_id');
    }
}
