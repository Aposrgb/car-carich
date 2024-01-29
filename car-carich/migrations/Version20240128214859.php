<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240128214859 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car ADD volume VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE car ADD mileage_one_charge VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE car DROP volume');
        $this->addSql('ALTER TABLE car DROP mileage_one_charge');
    }
}
