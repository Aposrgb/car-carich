<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240128214860 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql("insert into public.settings (id, type, value, img) values  (5, 5, null, null),(6, 6, null, null),(7, 7, null, null),(8, 8, null, null);");
        $this->addSql("alter sequence settings_id_seq restart start 9;");
    }

    public function down(Schema $schema): void
    {
    }
}
