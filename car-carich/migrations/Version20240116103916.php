<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240116103916 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE car_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE car (id INT NOT NULL, country_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type_engine INT NOT NULL, weight VARCHAR(255) DEFAULT NULL, size VARCHAR(255) DEFAULT NULL, year INT NOT NULL, battery VARCHAR(255) DEFAULT NULL, mileage VARCHAR(255) DEFAULT NULL, full_price INT NOT NULL, standard_price INT DEFAULT NULL, stamp VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_773DE69DF92F3E70 ON car (country_id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE car_id_seq CASCADE');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69DF92F3E70');
        $this->addSql('DROP TABLE car');
    }
}
