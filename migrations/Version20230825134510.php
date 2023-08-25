<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230825134510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE staff_application (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, job_id INT DEFAULT NULL, date DATE NOT NULL, INDEX IDX_55696D93A76ED395 (user_id), INDEX IDX_55696D93BE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE staff_application ADD CONSTRAINT FK_55696D93A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE staff_application ADD CONSTRAINT FK_55696D93BE04EA9 FOREIGN KEY (job_id) REFERENCES jobs (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE staff_application DROP FOREIGN KEY FK_55696D93A76ED395');
        $this->addSql('ALTER TABLE staff_application DROP FOREIGN KEY FK_55696D93BE04EA9');
        $this->addSql('DROP TABLE staff_application');
    }
}
