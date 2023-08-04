<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230803071154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tahost_cms_bloc ADD tcms_bloc_id INT NOT NULL, ADD tcms_diapo_id INT NOT NULL, DROP t_cms_bloc, DROP t_cms_diapo');
        $this->addSql('ALTER TABLE tahost_cms_bloc ADD CONSTRAINT FK_145250C43C702BD9 FOREIGN KEY (tcms_bloc_id) REFERENCES tcms_bloc (id)');
        $this->addSql('ALTER TABLE tahost_cms_bloc ADD CONSTRAINT FK_145250C48B90E55B FOREIGN KEY (tcms_diapo_id) REFERENCES tcms_diapo (id)');
        $this->addSql('CREATE INDEX IDX_145250C43C702BD9 ON tahost_cms_bloc (tcms_bloc_id)');
        $this->addSql('CREATE INDEX IDX_145250C48B90E55B ON tahost_cms_bloc (tcms_diapo_id)');
        $this->addSql('ALTER TABLE taoption_provider ADD t_option_id INT DEFAULT NULL, ADD t_product_id INT DEFAULT NULL, DROP id_option, DROP id_product');
        $this->addSql('ALTER TABLE taoption_provider ADD CONSTRAINT FK_2C1A05C82CAE4499 FOREIGN KEY (t_option_id) REFERENCES toption (id)');
        $this->addSql('ALTER TABLE taoption_provider ADD CONSTRAINT FK_2C1A05C811D15B2A FOREIGN KEY (t_product_id) REFERENCES tproduct (id)');
        $this->addSql('CREATE INDEX IDX_2C1A05C82CAE4499 ON taoption_provider (t_option_id)');
        $this->addSql('CREATE INDEX IDX_2C1A05C811D15B2A ON taoption_provider (t_product_id)');
        $this->addSql('ALTER TABLE taoption_value_provider ADD t_option_value_id INT DEFAULT NULL, DROP id_option_value');
        $this->addSql('ALTER TABLE taoption_value_provider ADD CONSTRAINT FK_375BF28F96647EBE FOREIGN KEY (t_option_value_id) REFERENCES toption_value (id)');
        $this->addSql('CREATE INDEX IDX_375BF28F96647EBE ON taoption_value_provider (t_option_value_id)');
        $this->addSql('ALTER TABLE taproduct_option ADD host_id INT DEFAULT NULL, DROP id_host');
        $this->addSql('ALTER TABLE taproduct_option ADD CONSTRAINT FK_F56812131FB8D185 FOREIGN KEY (host_id) REFERENCES hosts (id)');
        $this->addSql('CREATE INDEX IDX_F56812131FB8D185 ON taproduct_option (host_id)');
        $this->addSql('ALTER TABLE taproduct_option_value ADD host_id INT DEFAULT NULL, DROP id_host');
        $this->addSql('ALTER TABLE taproduct_option_value ADD CONSTRAINT FK_45B06EA81FB8D185 FOREIGN KEY (host_id) REFERENCES hosts (id)');
        $this->addSql('CREATE INDEX IDX_45B06EA81FB8D185 ON taproduct_option_value (host_id)');
        $this->addSql('ALTER TABLE taproduct_option_value_provider ADD provider_id INT NOT NULL, ADD t_option_value_id INT NOT NULL, ADD t_product_id INT NOT NULL, ADD id_source VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE taproduct_option_value_provider ADD CONSTRAINT FK_59F970FEA53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE taproduct_option_value_provider ADD CONSTRAINT FK_59F970FE96647EBE FOREIGN KEY (t_option_value_id) REFERENCES toption_value (id)');
        $this->addSql('ALTER TABLE taproduct_option_value_provider ADD CONSTRAINT FK_59F970FE11D15B2A FOREIGN KEY (t_product_id) REFERENCES tproduct (id)');
        $this->addSql('CREATE INDEX IDX_59F970FEA53A8AA ON taproduct_option_value_provider (provider_id)');
        $this->addSql('CREATE INDEX IDX_59F970FE96647EBE ON taproduct_option_value_provider (t_option_value_id)');
        $this->addSql('CREATE INDEX IDX_59F970FE11D15B2A ON taproduct_option_value_provider (t_product_id)');
        $this->addSql('ALTER TABLE tavariant_option_value ADD t_product_host_id INT NOT NULL, ADD t_option_value_id INT NOT NULL');
        $this->addSql('ALTER TABLE tavariant_option_value ADD CONSTRAINT FK_70297E2A81527E8D FOREIGN KEY (t_product_host_id) REFERENCES tproduct_host (id)');
        $this->addSql('ALTER TABLE tavariant_option_value ADD CONSTRAINT FK_70297E2A96647EBE FOREIGN KEY (t_option_value_id) REFERENCES toption_value (id)');
        $this->addSql('CREATE INDEX IDX_70297E2A81527E8D ON tavariant_option_value (t_product_host_id)');
        $this->addSql('CREATE INDEX IDX_70297E2A96647EBE ON tavariant_option_value (t_option_value_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tahost_cms_bloc DROP FOREIGN KEY FK_145250C43C702BD9');
        $this->addSql('ALTER TABLE tahost_cms_bloc DROP FOREIGN KEY FK_145250C48B90E55B');
        $this->addSql('DROP INDEX IDX_145250C43C702BD9 ON tahost_cms_bloc');
        $this->addSql('DROP INDEX IDX_145250C48B90E55B ON tahost_cms_bloc');
        $this->addSql('ALTER TABLE tahost_cms_bloc ADD t_cms_bloc VARCHAR(255) NOT NULL, ADD t_cms_diapo VARCHAR(255) NOT NULL, DROP tcms_bloc_id, DROP tcms_diapo_id');
        $this->addSql('ALTER TABLE taoption_provider DROP FOREIGN KEY FK_2C1A05C82CAE4499');
        $this->addSql('ALTER TABLE taoption_provider DROP FOREIGN KEY FK_2C1A05C811D15B2A');
        $this->addSql('DROP INDEX IDX_2C1A05C82CAE4499 ON taoption_provider');
        $this->addSql('DROP INDEX IDX_2C1A05C811D15B2A ON taoption_provider');
        $this->addSql('ALTER TABLE taoption_provider ADD id_option INT NOT NULL, ADD id_product INT NOT NULL, DROP t_option_id, DROP t_product_id');
        $this->addSql('ALTER TABLE taoption_value_provider DROP FOREIGN KEY FK_375BF28F96647EBE');
        $this->addSql('DROP INDEX IDX_375BF28F96647EBE ON taoption_value_provider');
        $this->addSql('ALTER TABLE taoption_value_provider ADD id_option_value INT NOT NULL, DROP t_option_value_id');
        $this->addSql('ALTER TABLE taproduct_option DROP FOREIGN KEY FK_F56812131FB8D185');
        $this->addSql('DROP INDEX IDX_F56812131FB8D185 ON taproduct_option');
        $this->addSql('ALTER TABLE taproduct_option ADD id_host VARCHAR(255) NOT NULL, DROP host_id');
        $this->addSql('ALTER TABLE taproduct_option_value DROP FOREIGN KEY FK_45B06EA81FB8D185');
        $this->addSql('DROP INDEX IDX_45B06EA81FB8D185 ON taproduct_option_value');
        $this->addSql('ALTER TABLE taproduct_option_value ADD id_host INT NOT NULL, DROP host_id');
        $this->addSql('ALTER TABLE taproduct_option_value_provider DROP FOREIGN KEY FK_59F970FEA53A8AA');
        $this->addSql('ALTER TABLE taproduct_option_value_provider DROP FOREIGN KEY FK_59F970FE96647EBE');
        $this->addSql('ALTER TABLE taproduct_option_value_provider DROP FOREIGN KEY FK_59F970FE11D15B2A');
        $this->addSql('DROP INDEX IDX_59F970FEA53A8AA ON taproduct_option_value_provider');
        $this->addSql('DROP INDEX IDX_59F970FE96647EBE ON taproduct_option_value_provider');
        $this->addSql('DROP INDEX IDX_59F970FE11D15B2A ON taproduct_option_value_provider');
        $this->addSql('ALTER TABLE taproduct_option_value_provider DROP provider_id, DROP t_option_value_id, DROP t_product_id, DROP id_source');
        $this->addSql('ALTER TABLE tavariant_option_value DROP FOREIGN KEY FK_70297E2A81527E8D');
        $this->addSql('ALTER TABLE tavariant_option_value DROP FOREIGN KEY FK_70297E2A96647EBE');
        $this->addSql('DROP INDEX IDX_70297E2A81527E8D ON tavariant_option_value');
        $this->addSql('DROP INDEX IDX_70297E2A96647EBE ON tavariant_option_value');
        $this->addSql('ALTER TABLE tavariant_option_value DROP t_product_host_id, DROP t_option_value_id');
    }
}
