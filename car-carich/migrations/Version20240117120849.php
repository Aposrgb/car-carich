<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240117120849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE car DROP COLUMN mileage');
        $this->addSql("ALTER TABLE car ADD mileage int DEFAULT NULL");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE car DROP COLUMN mileage');
        $this->addSql("ALTER TABLE car ADD mileage VARCHAR(255) DEFAULT NULL");
    }
}
