<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230829100447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC512469DE2');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP INDEX IDX_A8936DC512469DE2 ON jobs');
        $this->addSql('ALTER TABLE jobs CHANGE category_id theme_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC559027487 FOREIGN KEY (theme_id) REFERENCES themes (id)');
        $this->addSql('CREATE INDEX IDX_A8936DC559027487 ON jobs (theme_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC559027487');
        $this->addSql('DROP INDEX IDX_A8936DC559027487 ON jobs');
        $this->addSql('ALTER TABLE jobs CHANGE theme_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC512469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_A8936DC512469DE2 ON jobs (category_id)');
    }
}
