<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307162818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, subject VARCHAR(255) NOT NULL, content VARCHAR(500) NOT NULL, sent_on DATETIME NOT NULL, lodging_id INT DEFAULT NULL, reservation_id INT DEFAULT NULL, INDEX IDX_B6BD307F87335AF1 (lodging_id), INDEX IDX_B6BD307FB83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F87335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F87335AF1');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FB83297E7');
        $this->addSql('DROP TABLE message');
    }
}
