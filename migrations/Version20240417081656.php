<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240417081656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address ADD created_on DATETIME NOT NULL, ADD updated_on DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE bed ADD created_on DATETIME NOT NULL, ADD updated_on DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE lodging ADD created_on DATETIME NOT NULL, ADD updated_on DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD updated_on DATETIME DEFAULT NULL, CHANGE media_name media_path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE message ADD updated_on DATETIME DEFAULT NULL, CHANGE subject subject VARCHAR(300) NOT NULL, CHANGE content content VARCHAR(2000) NOT NULL, CHANGE sent_on created_on DATETIME NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD created_on DATETIME NOT NULL, ADD updated_on DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation_status ADD created_on DATETIME NOT NULL, ADD updated_on DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE review ADD updated_on DATETIME DEFAULT NULL, CHANGE comment comment VARCHAR(1000) DEFAULT NULL, CHANGE published_on created_on DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F5B7AF75');
        $this->addSql('ALTER TABLE user ADD is_deleted TINYINT(1) NOT NULL, ADD created_on DATETIME NOT NULL, ADD updated_on DATETIME DEFAULT NULL, CHANGE address_id address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP created_on, DROP updated_on');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F5B7AF75');
        $this->addSql('ALTER TABLE user DROP is_deleted, DROP created_on, DROP updated_on, CHANGE address_id address_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE bed DROP created_on, DROP updated_on');
        $this->addSql('ALTER TABLE message DROP updated_on, CHANGE subject subject VARCHAR(255) NOT NULL, CHANGE content content VARCHAR(500) NOT NULL, CHANGE created_on sent_on DATETIME NOT NULL');
        $this->addSql('ALTER TABLE reservation_status DROP created_on, DROP updated_on');
        $this->addSql('ALTER TABLE lodging DROP created_on, DROP updated_on');
        $this->addSql('ALTER TABLE review DROP updated_on, CHANGE comment comment VARCHAR(500) DEFAULT NULL, CHANGE created_on published_on DATETIME NOT NULL');
        $this->addSql('ALTER TABLE reservation DROP created_on, DROP updated_on');
        $this->addSql('ALTER TABLE media DROP updated_on, CHANGE media_path media_name VARCHAR(255) NOT NULL');
    }
}
