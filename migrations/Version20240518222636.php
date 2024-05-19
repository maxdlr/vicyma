<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240518222636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bed_type (id INT AUTO_INCREMENT NOT NULL, height INT NOT NULL, width INT NOT NULL, is_extra TINYINT(1) NOT NULL, created_on DATETIME NOT NULL, updated_on DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, created_on DATETIME NOT NULL, updated_on DATETIME DEFAULT NULL, conversation_id VARCHAR(100) NOT NULL, user_id INT NOT NULL, INDEX IDX_8A8E26E9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE lodging_bed_type (lodging_id INT NOT NULL, bed_type_id INT NOT NULL, INDEX IDX_64EA6CBE87335AF1 (lodging_id), INDEX IDX_64EA6CBE8158330E (bed_type_id), PRIMARY KEY(lodging_id, bed_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE lodging_bed_type ADD CONSTRAINT FK_64EA6CBE87335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lodging_bed_type ADD CONSTRAINT FK_64EA6CBE8158330E FOREIGN KEY (bed_type_id) REFERENCES bed_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lodging_bed DROP FOREIGN KEY FK_47AE65BC88688BB9');
        $this->addSql('ALTER TABLE lodging_bed DROP FOREIGN KEY FK_47AE65BC87335AF1');
        $this->addSql('DROP TABLE bed');
        $this->addSql('DROP TABLE lodging_bed');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F87335AF1');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('ALTER TABLE message ADD is_read_by_admin TINYINT(1) NOT NULL, ADD admin_id INT DEFAULT NULL, ADD conversation_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F87335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_B6BD307F642B8210 ON message (admin_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F9AC0396 ON message (conversation_id)');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation CHANGE price price DOUBLE PRECISION NOT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE reservation_status ADD description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE review ADD status VARCHAR(20) NOT NULL, CHANGE lodging_id lodging_id INT NOT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bed (id INT AUTO_INCREMENT NOT NULL, height INT NOT NULL, width INT NOT NULL, is_extra TINYINT(1) NOT NULL, created_on DATETIME NOT NULL, updated_on DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE lodging_bed (lodging_id INT NOT NULL, bed_id INT NOT NULL, INDEX IDX_47AE65BC88688BB9 (bed_id), INDEX IDX_47AE65BC87335AF1 (lodging_id), PRIMARY KEY(lodging_id, bed_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE lodging_bed ADD CONSTRAINT FK_47AE65BC88688BB9 FOREIGN KEY (bed_id) REFERENCES bed (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lodging_bed ADD CONSTRAINT FK_47AE65BC87335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9A76ED395');
        $this->addSql('ALTER TABLE lodging_bed_type DROP FOREIGN KEY FK_64EA6CBE87335AF1');
        $this->addSql('ALTER TABLE lodging_bed_type DROP FOREIGN KEY FK_64EA6CBE8158330E');
        $this->addSql('DROP TABLE bed_type');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE lodging_bed_type');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F642B8210');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9AC0396');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F87335AF1');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('DROP INDEX IDX_B6BD307F642B8210 ON message');
        $this->addSql('DROP INDEX IDX_B6BD307F9AC0396 ON message');
        $this->addSql('ALTER TABLE message DROP is_read_by_admin, DROP admin_id, DROP conversation_id, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F87335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation_status DROP description');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE review DROP status, CHANGE lodging_id lodging_id INT DEFAULT NULL, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation CHANGE price price DOUBLE PRECISION DEFAULT NULL, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }
}
