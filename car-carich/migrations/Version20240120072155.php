<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240120072155 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE settings_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE settings (id INT NOT NULL, type INT NOT NULL, value VARCHAR(300) DEFAULT NULL, img VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql("insert into public.settings (id, type, value, img) values  (1, 1, null, null),(2, 2, null, null),(3, 3, null, null),(4, 4, null, null);");
        $this->addSql('alter sequence settings_id_seq restart start 5;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE settings_id_seq CASCADE');
        $this->addSql('DROP TABLE settings');
    }
}
