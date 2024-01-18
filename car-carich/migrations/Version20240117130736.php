<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240117130736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE stamp_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE stamp (id INT NOT NULL, name VARCHAR(255) NOT NULL, icon VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE car ADD stamp_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE car DROP stamp');
        $this->addSql('ALTER TABLE car DROP card_img');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DFEF6E9F FOREIGN KEY (stamp_id) REFERENCES stamp (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_773DE69DFEF6E9F ON car (stamp_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69DFEF6E9F');
        $this->addSql('DROP SEQUENCE stamp_id_seq CASCADE');
        $this->addSql('DROP TABLE stamp');
        $this->addSql('DROP INDEX IDX_773DE69DFEF6E9F');
        $this->addSql('ALTER TABLE car ADD stamp VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE car ADD card_img VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE car DROP stamp_id');
    }
}
