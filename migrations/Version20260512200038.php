<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260512200038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dishes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, is_available TINYINT NOT NULL, section_id INT NOT NULL, INDEX IDX_584DD35DD823E37A (section_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE menu_section (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, position INT NOT NULL, menu_id INT NOT NULL, INDEX IDX_A5A86751CCD7E912 (menu_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE menus (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, is_active TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE reservations (id INT AUTO_INCREMENT NOT NULL, customer_name VARCHAR(255) NOT NULL, customer_email VARCHAR(255) NOT NULL, customer_phone VARCHAR(255) NOT NULL, reservation_date DATETIME NOT NULL, reservation_time TIME NOT NULL, guests INT NOT NULL, message LONGTEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE dishes ADD CONSTRAINT FK_584DD35DD823E37A FOREIGN KEY (section_id) REFERENCES menu_section (id)');
        $this->addSql('ALTER TABLE menu_section ADD CONSTRAINT FK_A5A86751CCD7E912 FOREIGN KEY (menu_id) REFERENCES menus (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dishes DROP FOREIGN KEY FK_584DD35DD823E37A');
        $this->addSql('ALTER TABLE menu_section DROP FOREIGN KEY FK_A5A86751CCD7E912');
        $this->addSql('DROP TABLE dishes');
        $this->addSql('DROP TABLE menu_section');
        $this->addSql('DROP TABLE menus');
        $this->addSql('DROP TABLE reservations');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
