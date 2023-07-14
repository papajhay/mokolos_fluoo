<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230712144759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taorder_supplier_order ADD t_supplier_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE taorder_supplier_order ADD CONSTRAINT FK_A8CAE89DE00619AF FOREIGN KEY (t_supplier_order_id) REFERENCES tsupplier_order (id)');
        $this->addSql('CREATE INDEX IDX_A8CAE89DE00619AF ON taorder_supplier_order (t_supplier_order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taorder_supplier_order DROP FOREIGN KEY FK_A8CAE89DE00619AF');
        $this->addSql('DROP INDEX IDX_A8CAE89DE00619AF ON taorder_supplier_order');
        $this->addSql('ALTER TABLE taorder_supplier_order DROP t_supplier_order_id');
    }
}
