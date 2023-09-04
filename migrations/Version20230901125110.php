<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230901125110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE advantages (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clients (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, person_in_charge VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, background_color VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, object VARCHAR(255) NOT NULL, current_job VARCHAR(255) NOT NULL, is_read TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE courses (id INT AUTO_INCREMENT NOT NULL, theme_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, heading VARCHAR(255) NOT NULL, title_introduction VARCHAR(255) NOT NULL, introduction JSON NOT NULL, objectives JSON NOT NULL, learning_path JSON NOT NULL, public JSON NOT NULL, requirements JSON NOT NULL, video_introduction VARCHAR(255) NOT NULL, INDEX IDX_A9A55A4C59027487 (theme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE faq_teachers (id INT AUTO_INCREMENT NOT NULL, question VARCHAR(255) NOT NULL, answer LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fws_cookie (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) DEFAULT NULL, expire DATETIME DEFAULT NULL, path VARCHAR(255) DEFAULT NULL, domaine VARCHAR(255) DEFAULT NULL, secure TINYINT(1) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_application (id INT AUTO_INCREMENT NOT NULL, job_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, years_experience VARCHAR(2) NOT NULL, mobility VARCHAR(255) NOT NULL, cv VARCHAR(255) NOT NULL, namer_cv VARCHAR(255) NOT NULL, motivation VARCHAR(255) NOT NULL, diploma VARCHAR(255) NOT NULL, updated_at DATE NOT NULL, INDEX IDX_C737C688BE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jobs (id INT AUTO_INCREMENT NOT NULL, course_id INT DEFAULT NULL, theme_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, salary VARCHAR(255) NOT NULL, contract VARCHAR(255) NOT NULL, description JSON NOT NULL, title_description VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, date DATE NOT NULL, updated_at DATE NOT NULL, schedule VARCHAR(255) NOT NULL, available TINYINT(1) NOT NULL, mission_description VARCHAR(255) NOT NULL, main_missions JSON NOT NULL, profile_description VARCHAR(255) NOT NULL, profile_requirements JSON NOT NULL, informations JSON NOT NULL, INDEX IDX_A8936DC5591CC992 (course_id), INDEX IDX_A8936DC559027487 (theme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jobs_advantages (jobs_id INT NOT NULL, advantages_id INT NOT NULL, INDEX IDX_A7EAAF1C48704627 (jobs_id), INDEX IDX_A7EAAF1CEA24B352 (advantages_id), PRIMARY KEY(jobs_id, advantages_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, client_id INT DEFAULT NULL, course_id INT DEFAULT NULL, start_time VARCHAR(255) NOT NULL, schedule_time VARCHAR(255) NOT NULL, hours VARCHAR(255) NOT NULL, intervention VARCHAR(255) NOT NULL, mission_reference VARCHAR(255) NOT NULL, remuneration VARCHAR(255) NOT NULL, begin_at DATETIME NOT NULL, end_at DATETIME DEFAULT NULL, INDEX IDX_9067F23CA76ED395 (user_id), INDEX IDX_9067F23C19EB6921 (client_id), INDEX IDX_9067F23C591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professionals_needs (id INT AUTO_INCREMENT NOT NULL, theme_id INT DEFAULT NULL, firstname VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, current_job VARCHAR(255) NOT NULL, company VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(10) NOT NULL, motive VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, profil VARCHAR(255) NOT NULL, date DATE NOT NULL, localisation VARCHAR(255) NOT NULL, INDEX IDX_A8C2734659027487 (theme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE staff_application (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, job_id INT DEFAULT NULL, date DATE NOT NULL, INDEX IDX_55696D93A76ED395 (user_id), INDEX IDX_55696D93BE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher_application (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(10) NOT NULL, street_address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, postal_code VARCHAR(5) NOT NULL, cv VARCHAR(255) NOT NULL, updated_at DATE NOT NULL, message VARCHAR(255) NOT NULL, motif VARCHAR(255) NOT NULL, namer_cv VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE themes (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, long_description VARCHAR(255) NOT NULL, picture VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, updated_at DATE NOT NULL, kbis VARCHAR(255) NOT NULL, namer_kibs VARCHAR(255) NOT NULL, vigilance VARCHAR(255) NOT NULL, namer_vigilance VARCHAR(255) NOT NULL, identity VARCHAR(255) NOT NULL, namer_identity VARCHAR(255) NOT NULL, diplomas VARCHAR(255) NOT NULL, namer_diplomas VARCHAR(255) NOT NULL, cv VARCHAR(255) NOT NULL, namer_cv VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_courses (user_id INT NOT NULL, courses_id INT NOT NULL, INDEX IDX_F1A84446A76ED395 (user_id), INDEX IDX_F1A84446F9295384 (courses_id), PRIMARY KEY(user_id, courses_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C59027487 FOREIGN KEY (theme_id) REFERENCES themes (id)');
        $this->addSql('ALTER TABLE job_application ADD CONSTRAINT FK_C737C688BE04EA9 FOREIGN KEY (job_id) REFERENCES jobs (id)');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC5591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC559027487 FOREIGN KEY (theme_id) REFERENCES themes (id)');
        $this->addSql('ALTER TABLE jobs_advantages ADD CONSTRAINT FK_A7EAAF1C48704627 FOREIGN KEY (jobs_id) REFERENCES jobs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jobs_advantages ADD CONSTRAINT FK_A7EAAF1CEA24B352 FOREIGN KEY (advantages_id) REFERENCES advantages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE professionals_needs ADD CONSTRAINT FK_A8C2734659027487 FOREIGN KEY (theme_id) REFERENCES themes (id)');
        $this->addSql('ALTER TABLE staff_application ADD CONSTRAINT FK_55696D93A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE staff_application ADD CONSTRAINT FK_55696D93BE04EA9 FOREIGN KEY (job_id) REFERENCES jobs (id)');
        $this->addSql('ALTER TABLE user_courses ADD CONSTRAINT FK_F1A84446A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_courses ADD CONSTRAINT FK_F1A84446F9295384 FOREIGN KEY (courses_id) REFERENCES courses (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4C59027487');
        $this->addSql('ALTER TABLE job_application DROP FOREIGN KEY FK_C737C688BE04EA9');
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC5591CC992');
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC559027487');
        $this->addSql('ALTER TABLE jobs_advantages DROP FOREIGN KEY FK_A7EAAF1C48704627');
        $this->addSql('ALTER TABLE jobs_advantages DROP FOREIGN KEY FK_A7EAAF1CEA24B352');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23CA76ED395');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C19EB6921');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C591CC992');
        $this->addSql('ALTER TABLE professionals_needs DROP FOREIGN KEY FK_A8C2734659027487');
        $this->addSql('ALTER TABLE staff_application DROP FOREIGN KEY FK_55696D93A76ED395');
        $this->addSql('ALTER TABLE staff_application DROP FOREIGN KEY FK_55696D93BE04EA9');
        $this->addSql('ALTER TABLE user_courses DROP FOREIGN KEY FK_F1A84446A76ED395');
        $this->addSql('ALTER TABLE user_courses DROP FOREIGN KEY FK_F1A84446F9295384');
        $this->addSql('DROP TABLE advantages');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE courses');
        $this->addSql('DROP TABLE faq_teachers');
        $this->addSql('DROP TABLE fws_cookie');
        $this->addSql('DROP TABLE job_application');
        $this->addSql('DROP TABLE jobs');
        $this->addSql('DROP TABLE jobs_advantages');
        $this->addSql('DROP TABLE mission');
        $this->addSql('DROP TABLE professionals_needs');
        $this->addSql('DROP TABLE staff_application');
        $this->addSql('DROP TABLE teacher_application');
        $this->addSql('DROP TABLE themes');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_courses');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
