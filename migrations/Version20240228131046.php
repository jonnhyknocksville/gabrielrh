<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228131046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mission ADD invoice_client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C7C4498BD FOREIGN KEY (invoice_client_id) REFERENCES clients (id)');
        $this->addSql('CREATE INDEX IDX_9067F23C7C4498BD ON mission (invoice_client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C7C4498BD');
        $this->addSql('DROP INDEX IDX_9067F23C7C4498BD ON mission');
        $this->addSql('ALTER TABLE mission DROP invoice_client_id');
    }
}
