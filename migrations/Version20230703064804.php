<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703064804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taoption_value_provider ADD provider_id INT NOT NULL, ADD t_option_id INT NOT NULL, DROP id_provider, DROP id_option');
        $this->addSql('ALTER TABLE taoption_value_provider ADD CONSTRAINT FK_375BF28FA53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE taoption_value_provider ADD CONSTRAINT FK_375BF28F2CAE4499 FOREIGN KEY (t_option_id) REFERENCES toption (id)');
        $this->addSql('CREATE INDEX IDX_375BF28FA53A8AA ON taoption_value_provider (provider_id)');
        $this->addSql('CREATE INDEX IDX_375BF28F2CAE4499 ON taoption_value_provider (t_option_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taoption_value_provider DROP FOREIGN KEY FK_375BF28FA53A8AA');
        $this->addSql('ALTER TABLE taoption_value_provider DROP FOREIGN KEY FK_375BF28F2CAE4499');
        $this->addSql('DROP INDEX IDX_375BF28FA53A8AA ON taoption_value_provider');
        $this->addSql('DROP INDEX IDX_375BF28F2CAE4499 ON taoption_value_provider');
        $this->addSql('ALTER TABLE taoption_value_provider ADD id_provider INT NOT NULL, ADD id_option INT NOT NULL, DROP provider_id, DROP t_option_id');
    }
}
