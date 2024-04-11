<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240409190239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, media_name VARCHAR(255) NOT NULL, media_size DOUBLE PRECISION NOT NULL, created_on DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE media_lodging (media_id INT NOT NULL, lodging_id INT NOT NULL, INDEX IDX_ECE29E63EA9FDD75 (media_id), INDEX IDX_ECE29E6387335AF1 (lodging_id), PRIMARY KEY(media_id, lodging_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE media_lodging ADD CONSTRAINT FK_ECE29E63EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_lodging ADD CONSTRAINT FK_ECE29E6387335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE file_lodging DROP FOREIGN KEY FK_D653A11187335AF1');
        $this->addSql('ALTER TABLE file_lodging DROP FOREIGN KEY FK_D653A11193CB796C');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE file_lodging');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, file_size DOUBLE PRECISION NOT NULL, created_on DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE file_lodging (file_id INT NOT NULL, lodging_id INT NOT NULL, INDEX IDX_D653A11193CB796C (file_id), INDEX IDX_D653A11187335AF1 (lodging_id), PRIMARY KEY(file_id, lodging_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE file_lodging ADD CONSTRAINT FK_D653A11187335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE file_lodging ADD CONSTRAINT FK_D653A11193CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_lodging DROP FOREIGN KEY FK_ECE29E63EA9FDD75');
        $this->addSql('ALTER TABLE media_lodging DROP FOREIGN KEY FK_ECE29E6387335AF1');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE media_lodging');
    }
}