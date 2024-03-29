<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240320132754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, line1 VARCHAR(255) NOT NULL, line2 VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, region VARCHAR(255) DEFAULT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE bed (id INT AUTO_INCREMENT NOT NULL, height INT NOT NULL, width INT NOT NULL, is_extra TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) NOT NULL, file_size DOUBLE PRECISION NOT NULL, created_on DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE file_lodging (file_id INT NOT NULL, lodging_id INT NOT NULL, INDEX IDX_D653A11193CB796C (file_id), INDEX IDX_D653A11187335AF1 (lodging_id), PRIMARY KEY(file_id, lodging_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE lodging_bed (lodging_id INT NOT NULL, bed_id INT NOT NULL, INDEX IDX_47AE65BC87335AF1 (lodging_id), INDEX IDX_47AE65BC88688BB9 (bed_id), PRIMARY KEY(lodging_id, bed_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, subject VARCHAR(255) NOT NULL, content VARCHAR(500) NOT NULL, sent_on DATETIME NOT NULL, lodging_id INT DEFAULT NULL, reservation_id INT DEFAULT NULL, user_id INT NOT NULL, INDEX IDX_B6BD307F87335AF1 (lodging_id), INDEX IDX_B6BD307FB83297E7 (reservation_id), INDEX IDX_B6BD307FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, reservation_number VARCHAR(255) NOT NULL, adult_count INT NOT NULL, child_count INT DEFAULT NULL, arrival_date DATETIME NOT NULL, departure_date DATETIME NOT NULL, price DOUBLE PRECISION DEFAULT NULL, reservation_status_id INT DEFAULT NULL, user_id INT NOT NULL, INDEX IDX_42C8495571B06122 (reservation_status_id), INDEX IDX_42C84955A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE reservation_lodging (reservation_id INT NOT NULL, lodging_id INT NOT NULL, INDEX IDX_EAA0A3AFB83297E7 (reservation_id), INDEX IDX_EAA0A3AF87335AF1 (lodging_id), PRIMARY KEY(reservation_id, lodging_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE reservation_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, rate INT NOT NULL, comment VARCHAR(500) DEFAULT NULL, published_on DATETIME NOT NULL, lodging_id INT DEFAULT NULL, user_id INT NOT NULL, INDEX IDX_794381C687335AF1 (lodging_id), INDEX IDX_794381C6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone_number VARCHAR(50) NOT NULL, address_id INT NOT NULL, INDEX IDX_8D93D649F5B7AF75 (address_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE file_lodging ADD CONSTRAINT FK_D653A11193CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE file_lodging ADD CONSTRAINT FK_D653A11187335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lodging_bed ADD CONSTRAINT FK_47AE65BC87335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lodging_bed ADD CONSTRAINT FK_47AE65BC88688BB9 FOREIGN KEY (bed_id) REFERENCES bed (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F87335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495571B06122 FOREIGN KEY (reservation_status_id) REFERENCES reservation_status (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation_lodging ADD CONSTRAINT FK_EAA0A3AFB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_lodging ADD CONSTRAINT FK_EAA0A3AF87335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C687335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE lodging ADD description VARCHAR(1000) NOT NULL, ADD price_by_night DOUBLE PRECISION NOT NULL, CHANGE is_animal_allowed animal_allowed TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file_lodging DROP FOREIGN KEY FK_D653A11193CB796C');
        $this->addSql('ALTER TABLE file_lodging DROP FOREIGN KEY FK_D653A11187335AF1');
        $this->addSql('ALTER TABLE lodging_bed DROP FOREIGN KEY FK_47AE65BC87335AF1');
        $this->addSql('ALTER TABLE lodging_bed DROP FOREIGN KEY FK_47AE65BC88688BB9');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F87335AF1');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FB83297E7');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495571B06122');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation_lodging DROP FOREIGN KEY FK_EAA0A3AFB83297E7');
        $this->addSql('ALTER TABLE reservation_lodging DROP FOREIGN KEY FK_EAA0A3AF87335AF1');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C687335AF1');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F5B7AF75');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE bed');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE file_lodging');
        $this->addSql('DROP TABLE lodging_bed');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reservation_lodging');
        $this->addSql('DROP TABLE reservation_status');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE lodging DROP description, DROP price_by_night, CHANGE animal_allowed is_animal_allowed TINYINT(1) NOT NULL');
    }
}
