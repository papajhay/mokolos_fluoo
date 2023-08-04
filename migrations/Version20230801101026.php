<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230801101026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //$this->addSql('ALTER TABLE taproduct_option_value_provider DROP id_provider, DROP id_option_value, DROP id_product');
        $this->addSql('ALTER TABLE ttxt ADD id_product_host INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE hosts');
        $this->addSql('DROP TABLE tmessage');
        $this->addSql('ALTER TABLE taproduct_option_value_provider ADD id_provider INT NOT NULL, ADD id_option_value INT NOT NULL, ADD id_product INT NOT NULL');
        $this->addSql('ALTER TABLE ttxt DROP id_product_host');
    }
}
