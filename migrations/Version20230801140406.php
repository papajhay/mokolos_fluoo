<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230801140406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tahost_cms_bloc ADD host_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tahost_cms_bloc ADD CONSTRAINT FK_145250C41FB8D185 FOREIGN KEY (host_id) REFERENCES hosts (id)');
        $this->addSql('CREATE INDEX IDX_145250C41FB8D185 ON tahost_cms_bloc (host_id)');
        $this->addSql('ALTER TABLE taproduct_meta ADD host_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE taproduct_meta ADD CONSTRAINT FK_D0D2B6B91FB8D185 FOREIGN KEY (host_id) REFERENCES hosts (id)');
        $this->addSql('CREATE INDEX IDX_D0D2B6B91FB8D185 ON taproduct_meta (host_id)');
        $this->addSql('ALTER TABLE tonline_product_rule ADD product_index VARCHAR(255) NOT NULL, ADD hide_row VARCHAR(255) NOT NULL, ADD if_every_is_selected VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tahost_cms_bloc DROP FOREIGN KEY FK_145250C41FB8D185');
        $this->addSql('DROP INDEX IDX_145250C41FB8D185 ON tahost_cms_bloc');
        $this->addSql('ALTER TABLE tahost_cms_bloc DROP host_id');
        $this->addSql('ALTER TABLE taproduct_meta DROP FOREIGN KEY FK_D0D2B6B91FB8D185');
        $this->addSql('DROP INDEX IDX_D0D2B6B91FB8D185 ON taproduct_meta');
        $this->addSql('ALTER TABLE taproduct_meta DROP host_id');
        $this->addSql('ALTER TABLE tonline_product_rule DROP product_index, DROP hide_row, DROP if_every_is_selected');
    }
}
