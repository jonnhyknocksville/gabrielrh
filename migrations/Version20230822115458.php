<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230822115458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE advantages (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jobs_advantages (jobs_id INT NOT NULL, advantages_id INT NOT NULL, INDEX IDX_A7EAAF1C48704627 (jobs_id), INDEX IDX_A7EAAF1CEA24B352 (advantages_id), PRIMARY KEY(jobs_id, advantages_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jobs_advantages ADD CONSTRAINT FK_A7EAAF1C48704627 FOREIGN KEY (jobs_id) REFERENCES jobs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jobs_advantages ADD CONSTRAINT FK_A7EAAF1CEA24B352 FOREIGN KEY (advantages_id) REFERENCES advantages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jobs DROP advantages');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jobs_advantages DROP FOREIGN KEY FK_A7EAAF1C48704627');
        $this->addSql('ALTER TABLE jobs_advantages DROP FOREIGN KEY FK_A7EAAF1CEA24B352');
        $this->addSql('DROP TABLE advantages');
        $this->addSql('DROP TABLE jobs_advantages');
        $this->addSql('ALTER TABLE jobs ADD advantages JSON NOT NULL');
    }
}
