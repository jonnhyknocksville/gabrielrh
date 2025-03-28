<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250312082103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person_in_charge_cc (person_in_charge_source INT NOT NULL, person_in_charge_target INT NOT NULL, INDEX IDX_B03730D3D41291FA (person_in_charge_source), INDEX IDX_B03730D3CDF7C175 (person_in_charge_target), PRIMARY KEY(person_in_charge_source, person_in_charge_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person_in_charge_cc ADD CONSTRAINT FK_B03730D3D41291FA FOREIGN KEY (person_in_charge_source) REFERENCES person_in_charge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_in_charge_cc ADD CONSTRAINT FK_B03730D3CDF7C175 FOREIGN KEY (person_in_charge_target) REFERENCES person_in_charge (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person_in_charge_cc DROP FOREIGN KEY FK_B03730D3D41291FA');
        $this->addSql('ALTER TABLE person_in_charge_cc DROP FOREIGN KEY FK_B03730D3CDF7C175');
        $this->addSql('DROP TABLE person_in_charge_cc');
    }
}
