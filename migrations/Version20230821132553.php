<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230821132553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jobs (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, salary VARCHAR(255) NOT NULL, contract VARCHAR(255) NOT NULL, description JSON NOT NULL, title_description VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, date DATE NOT NULL, updated_at DATE NOT NULL, schedule VARCHAR(255) NOT NULL, available TINYINT(1) NOT NULL, mission_description VARCHAR(255) NOT NULL, main_missions JSON NOT NULL, profile_description VARCHAR(255) NOT NULL, profile_requirements JSON NOT NULL, informations JSON NOT NULL, advantages JSON NOT NULL, INDEX IDX_A8936DC512469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC512469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('DROP TABLE courses_details');
        $this->addSql('ALTER TABLE courses ADD heading VARCHAR(255) NOT NULL, ADD title_introduction VARCHAR(255) NOT NULL, ADD introduction JSON NOT NULL, ADD objectives JSON NOT NULL, ADD learning_path JSON NOT NULL, ADD public JSON NOT NULL, ADD requirements JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE courses_details (id INT AUTO_INCREMENT NOT NULL, heading VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, title_introduction VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, introduction JSON NOT NULL, objectives JSON NOT NULL, learning_path JSON NOT NULL, public JSON NOT NULL, requirements JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC512469DE2');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE jobs');
        $this->addSql('ALTER TABLE courses DROP heading, DROP title_introduction, DROP introduction, DROP objectives, DROP learning_path, DROP public, DROP requirements');
    }
}
