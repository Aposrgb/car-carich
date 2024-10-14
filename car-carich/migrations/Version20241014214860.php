<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241014214860 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql("insert into public.settings (id, type, value, img) values  (9, 9, null, null);");
        $this->addSql("alter sequence settings_id_seq restart start 10;");
    }

    public function down(Schema $schema): void
    {
    }
}
