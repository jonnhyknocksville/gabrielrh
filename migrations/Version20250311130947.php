<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250311130947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE email_template (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exchange (id INT AUTO_INCREMENT NOT NULL, person_in_charge_id INT DEFAULT NULL, email_template_id INT DEFAULT NULL, date DATETIME NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_D33BB079D4BC4DFA (person_in_charge_id), INDEX IDX_D33BB079131A730F (email_template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE list_contact (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_in_charge (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, school VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, position VARCHAR(255) NOT NULL, phone_number VARCHAR(20) NOT NULL, source VARCHAR(255) DEFAULT NULL, collaboration TINYINT(1) DEFAULT NULL, collaboration_subject VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_5AB5EEBBE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_list_contact (person_in_charge_id INT NOT NULL, list_contact_id INT NOT NULL, INDEX IDX_7323DB78D4BC4DFA (person_in_charge_id), INDEX IDX_7323DB783D8C5196 (list_contact_id), PRIMARY KEY(person_in_charge_id, list_contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_in_charge_courses (person_in_charge_id INT NOT NULL, courses_id INT NOT NULL, INDEX IDX_3D4AF926D4BC4DFA (person_in_charge_id), INDEX IDX_3D4AF926F9295384 (courses_id), PRIMARY KEY(person_in_charge_id, courses_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exchange ADD CONSTRAINT FK_D33BB079D4BC4DFA FOREIGN KEY (person_in_charge_id) REFERENCES person_in_charge (id)');
        $this->addSql('ALTER TABLE exchange ADD CONSTRAINT FK_D33BB079131A730F FOREIGN KEY (email_template_id) REFERENCES email_template (id)');
        $this->addSql('ALTER TABLE person_list_contact ADD CONSTRAINT FK_7323DB78D4BC4DFA FOREIGN KEY (person_in_charge_id) REFERENCES person_in_charge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_list_contact ADD CONSTRAINT FK_7323DB783D8C5196 FOREIGN KEY (list_contact_id) REFERENCES list_contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_in_charge_courses ADD CONSTRAINT FK_3D4AF926D4BC4DFA FOREIGN KEY (person_in_charge_id) REFERENCES person_in_charge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_in_charge_courses ADD CONSTRAINT FK_3D4AF926F9295384 FOREIGN KEY (courses_id) REFERENCES courses (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exchange DROP FOREIGN KEY FK_D33BB079D4BC4DFA');
        $this->addSql('ALTER TABLE exchange DROP FOREIGN KEY FK_D33BB079131A730F');
        $this->addSql('ALTER TABLE person_list_contact DROP FOREIGN KEY FK_7323DB78D4BC4DFA');
        $this->addSql('ALTER TABLE person_list_contact DROP FOREIGN KEY FK_7323DB783D8C5196');
        $this->addSql('ALTER TABLE person_in_charge_courses DROP FOREIGN KEY FK_3D4AF926D4BC4DFA');
        $this->addSql('ALTER TABLE person_in_charge_courses DROP FOREIGN KEY FK_3D4AF926F9295384');
        $this->addSql('DROP TABLE email_template');
        $this->addSql('DROP TABLE exchange');
        $this->addSql('DROP TABLE list_contact');
        $this->addSql('DROP TABLE person_in_charge');
        $this->addSql('DROP TABLE person_list_contact');
        $this->addSql('DROP TABLE person_in_charge_courses');
    }
}
