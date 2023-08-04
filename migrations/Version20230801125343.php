<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230801125343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tcms_page ADD host_id INT DEFAULT NULL, CHANGE date_heure_last_update date_time_last_update DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE tcms_page ADD CONSTRAINT FK_FDF842431FB8D185 FOREIGN KEY (host_id) REFERENCES hosts (id)');
        $this->addSql('CREATE INDEX IDX_FDF842431FB8D185 ON tcms_page (host_id)');
        $this->addSql('ALTER TABLE ttxt CHANGE id_host host_id INT NOT NULL');
        $this->addSql('ALTER TABLE ttxt ADD CONSTRAINT FK_266F6E801FB8D185 FOREIGN KEY (host_id) REFERENCES hosts (id)');
        $this->addSql('CREATE INDEX IDX_266F6E801FB8D185 ON ttxt (host_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tcms_page DROP FOREIGN KEY FK_FDF842431FB8D185');
        $this->addSql('DROP INDEX IDX_FDF842431FB8D185 ON tcms_page');
        $this->addSql('ALTER TABLE tcms_page DROP host_id, CHANGE date_time_last_update date_heure_last_update DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE ttxt DROP FOREIGN KEY FK_266F6E801FB8D185');
        $this->addSql('DROP INDEX IDX_266F6E801FB8D185 ON ttxt');
        $this->addSql('ALTER TABLE ttxt CHANGE host_id id_host INT NOT NULL');
    }
}
