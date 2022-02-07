<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220127064039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT NOT NULL, parent INT DEFAULT NULL, title VARCHAR(255) NOT NULL, level INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_64C19C13D8E604F ON category (parent)');
        $this->addSql('CREATE TABLE count_hist (id INT NOT NULL, product_id INT DEFAULT NULL, date DATE NOT NULL, count INT NOT NULL, delta INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A5A796A24584665A ON count_hist (product_id)');
        $this->addSql('CREATE TABLE price_hist (id INT NOT NULL, date DATE NOT NULL, delta INT NOT NULL, current_price INT NOT NULL, product VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE products (id INT NOT NULL, parent INT DEFAULT NULL, title VARCHAR(255) NOT NULL, level INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A3D8E604F ON products (parent)');
        $this->addSql('CREATE TABLE supply (id INT NOT NULL, supplier VARCHAR(255) NOT NULL, period_of_execution INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE supply_products (supply_id INT NOT NULL, products_id INT NOT NULL, PRIMARY KEY(supply_id, products_id))');
        $this->addSql('CREATE INDEX IDX_1B613253FF28C0D8 ON supply_products (supply_id)');
        $this->addSql('CREATE INDEX IDX_1B6132536C8A81A9 ON supply_products (products_id)');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, status BYTEA NOT NULL, email VARCHAR(255) NOT NULL, role JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C13D8E604F FOREIGN KEY (parent) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE count_hist ADD CONSTRAINT FK_A5A796A24584665A FOREIGN KEY (product_id) REFERENCES products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A3D8E604F FOREIGN KEY (parent) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE supply_products ADD CONSTRAINT FK_1B613253FF28C0D8 FOREIGN KEY (supply_id) REFERENCES supply (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE supply_products ADD CONSTRAINT FK_1B6132536C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE category DROP CONSTRAINT FK_64C19C13D8E604F');
        $this->addSql('ALTER TABLE products DROP CONSTRAINT FK_B3BA5A5A3D8E604F');
        $this->addSql('ALTER TABLE count_hist DROP CONSTRAINT FK_A5A796A24584665A');
        $this->addSql('ALTER TABLE supply_products DROP CONSTRAINT FK_1B6132536C8A81A9');
        $this->addSql('ALTER TABLE supply_products DROP CONSTRAINT FK_1B613253FF28C0D8');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE count_hist');
        $this->addSql('DROP TABLE price_hist');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE supply');
        $this->addSql('DROP TABLE supply_products');
        $this->addSql('DROP TABLE users');
    }
}
