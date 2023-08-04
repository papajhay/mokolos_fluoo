<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230801131652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tavariant_option_value ADD host VARCHAR(255) NOT NULL, DROP id_host');
        $this->addSql('ALTER TABLE tproduct_host ADD host_id INT DEFAULT NULL, DROP id_host');
        $this->addSql('ALTER TABLE tproduct_host ADD CONSTRAINT FK_4C23A7A31FB8D185 FOREIGN KEY (host_id) REFERENCES hosts (id)');
        $this->addSql('CREATE INDEX IDX_4C23A7A31FB8D185 ON tproduct_host (host_id)');
        $this->addSql('ALTER TABLE tproduct_host_more_viewed ADD host_id INT DEFAULT NULL, DROP id_host');
        $this->addSql('ALTER TABLE tproduct_host_more_viewed ADD CONSTRAINT FK_10FD62461FB8D185 FOREIGN KEY (host_id) REFERENCES hosts (id)');
        $this->addSql('CREATE INDEX IDX_10FD62461FB8D185 ON tproduct_host_more_viewed (host_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tavariant_option_value ADD id_host INT NOT NULL, DROP host');
        $this->addSql('ALTER TABLE tproduct_host DROP FOREIGN KEY FK_4C23A7A31FB8D185');
        $this->addSql('DROP INDEX IDX_4C23A7A31FB8D185 ON tproduct_host');
        $this->addSql('ALTER TABLE tproduct_host ADD id_host INT NOT NULL, DROP host_id');
        $this->addSql('ALTER TABLE tproduct_host_more_viewed DROP FOREIGN KEY FK_10FD62461FB8D185');
        $this->addSql('DROP INDEX IDX_10FD62461FB8D185 ON tproduct_host_more_viewed');
        $this->addSql('ALTER TABLE tproduct_host_more_viewed ADD id_host INT NOT NULL, DROP host_id');
    }
}
