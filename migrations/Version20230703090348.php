<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703090348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taoption_provider CHANGE id_provider provider_id INT NOT NULL');
        $this->addSql('ALTER TABLE taoption_provider ADD CONSTRAINT FK_2C1A05C8A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('CREATE INDEX IDX_2C1A05C8A53A8AA ON taoption_provider (provider_id)');
        $this->addSql('ALTER TABLE taproduct_option CHANGE id_product product_id INT NOT NULL');
        $this->addSql('ALTER TABLE taproduct_option ADD CONSTRAINT FK_F56812134584665A FOREIGN KEY (product_id) REFERENCES tproduct (id)');
        $this->addSql('CREATE INDEX IDX_F56812134584665A ON taproduct_option (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taoption_provider DROP FOREIGN KEY FK_2C1A05C8A53A8AA');
        $this->addSql('DROP INDEX IDX_2C1A05C8A53A8AA ON taoption_provider');
        $this->addSql('ALTER TABLE taoption_provider CHANGE provider_id id_provider INT NOT NULL');
        $this->addSql('ALTER TABLE taproduct_option DROP FOREIGN KEY FK_F56812134584665A');
        $this->addSql('DROP INDEX IDX_F56812134584665A ON taproduct_option');
        $this->addSql('ALTER TABLE taproduct_option CHANGE product_id id_product INT NOT NULL');
    }
}
