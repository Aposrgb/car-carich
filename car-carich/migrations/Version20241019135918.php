<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241019135918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD phone INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD photo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD telegram_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD telegram_username VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ALTER login DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER password DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD vk_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" DROP name');
        $this->addSql('ALTER TABLE "user" DROP email');
        $this->addSql('ALTER TABLE "user" DROP phone');
        $this->addSql('ALTER TABLE "user" DROP photo');
        $this->addSql('ALTER TABLE "user" DROP telegram_id');
        $this->addSql('ALTER TABLE "user" DROP telegram_username');
        $this->addSql('ALTER TABLE "user" ALTER login SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER password SET NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP vk_id');
        $this->addSql('ALTER TABLE "user" DROP created_at');
    }
}
