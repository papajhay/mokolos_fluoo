<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230818130125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hosts CHANGE amount_premuim amount_premium DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE `order` CHANGE id_product_host product_host_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398CE61CA35 FOREIGN KEY (product_host_id) REFERENCES tproduct_host (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F5299398CE61CA35 ON `order` (product_host_id)');
        $this->addSql('ALTER TABLE provider CHANGE comment comment VARCHAR(255) DEFAULT NULL, CHANGE billing_company billing_company INT DEFAULT NULL, CHANGE billing_street_adress billing_street_adress INT DEFAULT NULL, CHANGE billing_city billing_city INT DEFAULT NULL, CHANGE billing_name billing_name INT DEFAULT NULL, CHANGE billing_telephone billing_telephone INT DEFAULT NULL, CHANGE salutation salutation INT DEFAULT NULL, CHANGE date_hour_valid_cache_price date_hour_valid_cache_price DATETIME DEFAULT NULL, CHANGE number_vat number_vat INT DEFAULT NULL');
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
        $this->addSql('ALTER TABLE taproduct_host_category ADD product_host_id INT DEFAULT NULL, ADD category_id INT DEFAULT NULL, DROP id_product_host, DROP id_category');
        $this->addSql('ALTER TABLE taproduct_host_category ADD CONSTRAINT FK_ED14FF59CE61CA35 FOREIGN KEY (product_host_id) REFERENCES tproduct_host (id)');
        $this->addSql('ALTER TABLE taproduct_host_category ADD CONSTRAINT FK_ED14FF5912469DE2 FOREIGN KEY (category_id) REFERENCES tcategory (id)');
        $this->addSql('CREATE INDEX IDX_ED14FF59CE61CA35 ON taproduct_host_category (product_host_id)');
        $this->addSql('CREATE INDEX IDX_ED14FF5912469DE2 ON taproduct_host_category (category_id)');
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
        $this->addSql('ALTER TABLE taproduct_provider DROP FOREIGN KEY FK_E45742104584665A');
        $this->addSql('DROP INDEX IDX_E45742104584665A ON taproduct_provider');
        $this->addSql('ALTER TABLE taproduct_provider DROP product_id');
        $this->addSql('ALTER TABLE tavariant_option_value ADD t_option_value_id INT NOT NULL, ADD host VARCHAR(255) NOT NULL, CHANGE id_host t_product_host_id INT NOT NULL');
        $this->addSql('ALTER TABLE tavariant_option_value ADD CONSTRAINT FK_70297E2A81527E8D FOREIGN KEY (t_product_host_id) REFERENCES tproduct_host (id)');
        $this->addSql('ALTER TABLE tavariant_option_value ADD CONSTRAINT FK_70297E2A96647EBE FOREIGN KEY (t_option_value_id) REFERENCES toption_value (id)');
        $this->addSql('CREATE INDEX IDX_70297E2A81527E8D ON tavariant_option_value (t_product_host_id)');
        $this->addSql('CREATE INDEX IDX_70297E2A96647EBE ON tavariant_option_value (t_option_value_id)');
        $this->addSql('ALTER TABLE tcategory ADD host_id INT NOT NULL');
        $this->addSql('ALTER TABLE tcategory ADD CONSTRAINT FK_282840DF1FB8D185 FOREIGN KEY (host_id) REFERENCES hosts (id)');
        $this->addSql('CREATE INDEX IDX_282840DF1FB8D185 ON tcategory (host_id)');
        $this->addSql('ALTER TABLE tcms_bloc CHANGE id_cms_diapo t_cms_diapo_id INT NOT NULL');
        $this->addSql('ALTER TABLE tcms_bloc ADD CONSTRAINT FK_2E8A6139F22B1216 FOREIGN KEY (t_cms_diapo_id) REFERENCES tcms_diapo (id)');
        $this->addSql('CREATE INDEX IDX_2E8A6139F22B1216 ON tcms_bloc (t_cms_diapo_id)');
        $this->addSql('ALTER TABLE tcms_diapo ADD slider_product_ads_host_id INT DEFAULT NULL, ADD slider_product_detail_host_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tcms_diapo ADD CONSTRAINT FK_83CBF82713F867A5 FOREIGN KEY (slider_product_ads_host_id) REFERENCES tproduct_host (id)');
        $this->addSql('ALTER TABLE tcms_diapo ADD CONSTRAINT FK_83CBF827D142EAD2 FOREIGN KEY (slider_product_detail_host_id) REFERENCES tproduct_host (id)');
        $this->addSql('CREATE INDEX IDX_83CBF82713F867A5 ON tcms_diapo (slider_product_ads_host_id)');
        $this->addSql('CREATE INDEX IDX_83CBF827D142EAD2 ON tcms_diapo (slider_product_detail_host_id)');
        $this->addSql('ALTER TABLE tcms_page ADD host_id INT DEFAULT NULL, CHANGE date_heure_last_update date_time_last_update DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE tcms_page ADD CONSTRAINT FK_FDF842431FB8D185 FOREIGN KEY (host_id) REFERENCES hosts (id)');
        $this->addSql('CREATE INDEX IDX_FDF842431FB8D185 ON tcms_page (host_id)');
        $this->addSql('ALTER TABLE tcombinaison ADD t_product_id INT NOT NULL, ADD t_option_value_id INT NOT NULL, ADD t_combinaison_price_id INT DEFAULT NULL, DROP price');
        $this->addSql('ALTER TABLE tcombinaison ADD CONSTRAINT FK_5AD57E4311D15B2A FOREIGN KEY (t_product_id) REFERENCES tproduct (id)');
        $this->addSql('ALTER TABLE tcombinaison ADD CONSTRAINT FK_5AD57E4396647EBE FOREIGN KEY (t_option_value_id) REFERENCES toption_value (id)');
        $this->addSql('ALTER TABLE tcombinaison ADD CONSTRAINT FK_5AD57E4365718BF7 FOREIGN KEY (t_combinaison_price_id) REFERENCES tcombinaison_price (id)');
        $this->addSql('CREATE INDEX IDX_5AD57E4311D15B2A ON tcombinaison (t_product_id)');
        $this->addSql('CREATE INDEX IDX_5AD57E4396647EBE ON tcombinaison (t_option_value_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5AD57E4365718BF7 ON tcombinaison (t_combinaison_price_id)');
        $this->addSql('ALTER TABLE tcombinaison_price ADD provider_id INT NOT NULL');
        $this->addSql('ALTER TABLE tcombinaison_price ADD CONSTRAINT FK_FF7F0AE4A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('CREATE INDEX IDX_FF7F0AE4A53A8AA ON tcombinaison_price (provider_id)');
        $this->addSql('ALTER TABLE tproduct ADD t_aproduct_provider_id INT NOT NULL');
        $this->addSql('ALTER TABLE tproduct ADD CONSTRAINT FK_31E9BD0DAA93D17 FOREIGN KEY (t_aproduct_provider_id) REFERENCES taproduct_provider (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_31E9BD0DAA93D17 ON tproduct (t_aproduct_provider_id)');
        $this->addSql('ALTER TABLE tproduct_host ADD host_id INT DEFAULT NULL, ADD t_aproduct_provider_id INT NOT NULL, ADD produit_meta_parent_id INT DEFAULT NULL, CHANGE id_host t_product_id INT NOT NULL');
        $this->addSql('ALTER TABLE tproduct_host ADD CONSTRAINT FK_4C23A7A31FB8D185 FOREIGN KEY (host_id) REFERENCES hosts (id)');
        $this->addSql('ALTER TABLE tproduct_host ADD CONSTRAINT FK_4C23A7A311D15B2A FOREIGN KEY (t_product_id) REFERENCES tproduct (id)');
        $this->addSql('ALTER TABLE tproduct_host ADD CONSTRAINT FK_4C23A7A3DAA93D17 FOREIGN KEY (t_aproduct_provider_id) REFERENCES taproduct_provider (id)');
        $this->addSql('ALTER TABLE tproduct_host ADD CONSTRAINT FK_4C23A7A3F27FBEE5 FOREIGN KEY (produit_meta_parent_id) REFERENCES tproduct_host (id)');
        $this->addSql('CREATE INDEX IDX_4C23A7A31FB8D185 ON tproduct_host (host_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C23A7A311D15B2A ON tproduct_host (t_product_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C23A7A3DAA93D17 ON tproduct_host (t_aproduct_provider_id)');
        $this->addSql('CREATE INDEX IDX_4C23A7A3F27FBEE5 ON tproduct_host (produit_meta_parent_id)');
        $this->addSql('ALTER TABLE tproduct_host_acl ADD t_product_host_id INT DEFAULT NULL, ADD host_id INT DEFAULT NULL, DROP id_host');
        $this->addSql('ALTER TABLE tproduct_host_acl ADD CONSTRAINT FK_B3C1F33C81527E8D FOREIGN KEY (t_product_host_id) REFERENCES tproduct_host (id)');
        $this->addSql('ALTER TABLE tproduct_host_acl ADD CONSTRAINT FK_B3C1F33C1FB8D185 FOREIGN KEY (host_id) REFERENCES hosts (id)');
        $this->addSql('CREATE INDEX IDX_B3C1F33C81527E8D ON tproduct_host_acl (t_product_host_id)');
        $this->addSql('CREATE INDEX IDX_B3C1F33C1FB8D185 ON tproduct_host_acl (host_id)');
        $this->addSql('ALTER TABLE tproduct_host_more_viewed ADD host_id INT DEFAULT NULL, ADD product_host_id INT DEFAULT NULL, DROP id_host');
        $this->addSql('ALTER TABLE tproduct_host_more_viewed ADD CONSTRAINT FK_10FD62461FB8D185 FOREIGN KEY (host_id) REFERENCES hosts (id)');
        $this->addSql('ALTER TABLE tproduct_host_more_viewed ADD CONSTRAINT FK_10FD6246CE61CA35 FOREIGN KEY (product_host_id) REFERENCES tproduct_host (id)');
        $this->addSql('CREATE INDEX IDX_10FD62461FB8D185 ON tproduct_host_more_viewed (host_id)');
        $this->addSql('CREATE INDEX IDX_10FD6246CE61CA35 ON tproduct_host_more_viewed (product_host_id)');
        $this->addSql('ALTER TABLE tsupplier_order ADD provider_id INT DEFAULT NULL, ADD supplier_order_status_id INT DEFAULT NULL, DROP id_provider, DROP id_supplier_order_status');
        $this->addSql('ALTER TABLE tsupplier_order ADD CONSTRAINT FK_A67A545DA53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE tsupplier_order ADD CONSTRAINT FK_A67A545DBE73AB31 FOREIGN KEY (supplier_order_status_id) REFERENCES tsupplier_order_status (id)');
        $this->addSql('CREATE INDEX IDX_A67A545DA53A8AA ON tsupplier_order (provider_id)');
        $this->addSql('CREATE INDEX IDX_A67A545DBE73AB31 ON tsupplier_order (supplier_order_status_id)');
        $this->addSql('ALTER TABLE ttechnical_sheet ADD product_id INT DEFAULT NULL, ADD option_value_id INT DEFAULT NULL, DROP id_product, DROP id_option_value');
        $this->addSql('ALTER TABLE ttechnical_sheet ADD CONSTRAINT FK_58FA33AA4584665A FOREIGN KEY (product_id) REFERENCES tproduct (id)');
        $this->addSql('ALTER TABLE ttechnical_sheet ADD CONSTRAINT FK_58FA33AAD957CA06 FOREIGN KEY (option_value_id) REFERENCES toption_value (id)');
        $this->addSql('CREATE INDEX IDX_58FA33AA4584665A ON ttechnical_sheet (product_id)');
        $this->addSql('CREATE INDEX IDX_58FA33AAD957CA06 ON ttechnical_sheet (option_value_id)');
        $this->addSql('ALTER TABLE ttxt ADD host_id INT NOT NULL, ADD product_host_id INT DEFAULT NULL, DROP id_host, DROP id_product_host');
        $this->addSql('ALTER TABLE ttxt ADD CONSTRAINT FK_266F6E801FB8D185 FOREIGN KEY (host_id) REFERENCES hosts (id)');
        $this->addSql('ALTER TABLE ttxt ADD CONSTRAINT FK_266F6E80CE61CA35 FOREIGN KEY (product_host_id) REFERENCES tproduct_host (id)');
        $this->addSql('CREATE INDEX IDX_266F6E801FB8D185 ON ttxt (host_id)');
        $this->addSql('CREATE INDEX IDX_266F6E80CE61CA35 ON ttxt (product_host_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hosts CHANGE amount_premium amount_premuim DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398CE61CA35');
        $this->addSql('DROP INDEX UNIQ_F5299398CE61CA35 ON `order`');
        $this->addSql('ALTER TABLE `order` CHANGE product_host_id id_product_host INT DEFAULT NULL');
        $this->addSql('ALTER TABLE provider CHANGE number_vat number_vat INT NOT NULL, CHANGE comment comment VARCHAR(255) NOT NULL, CHANGE billing_company billing_company INT NOT NULL, CHANGE billing_street_adress billing_street_adress INT NOT NULL, CHANGE billing_city billing_city INT NOT NULL, CHANGE billing_name billing_name INT NOT NULL, CHANGE billing_telephone billing_telephone INT NOT NULL, CHANGE salutation salutation INT NOT NULL, CHANGE date_hour_valid_cache_price date_hour_valid_cache_price DATETIME NOT NULL');
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
        $this->addSql('ALTER TABLE taproduct_host_category DROP FOREIGN KEY FK_ED14FF59CE61CA35');
        $this->addSql('ALTER TABLE taproduct_host_category DROP FOREIGN KEY FK_ED14FF5912469DE2');
        $this->addSql('DROP INDEX IDX_ED14FF59CE61CA35 ON taproduct_host_category');
        $this->addSql('DROP INDEX IDX_ED14FF5912469DE2 ON taproduct_host_category');
        $this->addSql('ALTER TABLE taproduct_host_category ADD id_product_host INT NOT NULL, ADD id_category INT NOT NULL, DROP product_host_id, DROP category_id');
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
        $this->addSql('ALTER TABLE taproduct_provider ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE taproduct_provider ADD CONSTRAINT FK_E45742104584665A FOREIGN KEY (product_id) REFERENCES tproduct (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E45742104584665A ON taproduct_provider (product_id)');
        $this->addSql('ALTER TABLE tavariant_option_value DROP FOREIGN KEY FK_70297E2A81527E8D');
        $this->addSql('ALTER TABLE tavariant_option_value DROP FOREIGN KEY FK_70297E2A96647EBE');
        $this->addSql('DROP INDEX IDX_70297E2A81527E8D ON tavariant_option_value');
        $this->addSql('DROP INDEX IDX_70297E2A96647EBE ON tavariant_option_value');
        $this->addSql('ALTER TABLE tavariant_option_value ADD id_host INT NOT NULL, DROP t_product_host_id, DROP t_option_value_id, DROP host');
        $this->addSql('ALTER TABLE tcategory DROP FOREIGN KEY FK_282840DF1FB8D185');
        $this->addSql('DROP INDEX IDX_282840DF1FB8D185 ON tcategory');
        $this->addSql('ALTER TABLE tcategory DROP host_id');
        $this->addSql('ALTER TABLE tcms_bloc DROP FOREIGN KEY FK_2E8A6139F22B1216');
        $this->addSql('DROP INDEX IDX_2E8A6139F22B1216 ON tcms_bloc');
        $this->addSql('ALTER TABLE tcms_bloc CHANGE t_cms_diapo_id id_cms_diapo INT NOT NULL');
        $this->addSql('ALTER TABLE tcms_diapo DROP FOREIGN KEY FK_83CBF82713F867A5');
        $this->addSql('ALTER TABLE tcms_diapo DROP FOREIGN KEY FK_83CBF827D142EAD2');
        $this->addSql('DROP INDEX IDX_83CBF82713F867A5 ON tcms_diapo');
        $this->addSql('DROP INDEX IDX_83CBF827D142EAD2 ON tcms_diapo');
        $this->addSql('ALTER TABLE tcms_diapo DROP slider_product_ads_host_id, DROP slider_product_detail_host_id');
        $this->addSql('ALTER TABLE tcms_page DROP FOREIGN KEY FK_FDF842431FB8D185');
        $this->addSql('DROP INDEX IDX_FDF842431FB8D185 ON tcms_page');
        $this->addSql('ALTER TABLE tcms_page DROP host_id, CHANGE date_time_last_update date_heure_last_update DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE tcombinaison DROP FOREIGN KEY FK_5AD57E4311D15B2A');
        $this->addSql('ALTER TABLE tcombinaison DROP FOREIGN KEY FK_5AD57E4396647EBE');
        $this->addSql('ALTER TABLE tcombinaison DROP FOREIGN KEY FK_5AD57E4365718BF7');
        $this->addSql('DROP INDEX IDX_5AD57E4311D15B2A ON tcombinaison');
        $this->addSql('DROP INDEX IDX_5AD57E4396647EBE ON tcombinaison');
        $this->addSql('DROP INDEX UNIQ_5AD57E4365718BF7 ON tcombinaison');
        $this->addSql('ALTER TABLE tcombinaison ADD price DOUBLE PRECISION NOT NULL, DROP t_product_id, DROP t_option_value_id, DROP t_combinaison_price_id');
        $this->addSql('ALTER TABLE tcombinaison_price DROP FOREIGN KEY FK_FF7F0AE4A53A8AA');
        $this->addSql('DROP INDEX IDX_FF7F0AE4A53A8AA ON tcombinaison_price');
        $this->addSql('ALTER TABLE tcombinaison_price DROP provider_id');
        $this->addSql('ALTER TABLE tproduct DROP FOREIGN KEY FK_31E9BD0DAA93D17');
        $this->addSql('DROP INDEX UNIQ_31E9BD0DAA93D17 ON tproduct');
        $this->addSql('ALTER TABLE tproduct DROP t_aproduct_provider_id');
        $this->addSql('ALTER TABLE tproduct_host DROP FOREIGN KEY FK_4C23A7A31FB8D185');
        $this->addSql('ALTER TABLE tproduct_host DROP FOREIGN KEY FK_4C23A7A311D15B2A');
        $this->addSql('ALTER TABLE tproduct_host DROP FOREIGN KEY FK_4C23A7A3DAA93D17');
        $this->addSql('ALTER TABLE tproduct_host DROP FOREIGN KEY FK_4C23A7A3F27FBEE5');
        $this->addSql('DROP INDEX IDX_4C23A7A31FB8D185 ON tproduct_host');
        $this->addSql('DROP INDEX UNIQ_4C23A7A311D15B2A ON tproduct_host');
        $this->addSql('DROP INDEX UNIQ_4C23A7A3DAA93D17 ON tproduct_host');
        $this->addSql('DROP INDEX IDX_4C23A7A3F27FBEE5 ON tproduct_host');
        $this->addSql('ALTER TABLE tproduct_host ADD id_host INT NOT NULL, DROP host_id, DROP t_product_id, DROP t_aproduct_provider_id, DROP produit_meta_parent_id');
        $this->addSql('ALTER TABLE tproduct_host_acl DROP FOREIGN KEY FK_B3C1F33C81527E8D');
        $this->addSql('ALTER TABLE tproduct_host_acl DROP FOREIGN KEY FK_B3C1F33C1FB8D185');
        $this->addSql('DROP INDEX IDX_B3C1F33C81527E8D ON tproduct_host_acl');
        $this->addSql('DROP INDEX IDX_B3C1F33C1FB8D185 ON tproduct_host_acl');
        $this->addSql('ALTER TABLE tproduct_host_acl ADD id_host VARCHAR(255) NOT NULL, DROP t_product_host_id, DROP host_id');
        $this->addSql('ALTER TABLE tproduct_host_more_viewed DROP FOREIGN KEY FK_10FD62461FB8D185');
        $this->addSql('ALTER TABLE tproduct_host_more_viewed DROP FOREIGN KEY FK_10FD6246CE61CA35');
        $this->addSql('DROP INDEX IDX_10FD62461FB8D185 ON tproduct_host_more_viewed');
        $this->addSql('DROP INDEX IDX_10FD6246CE61CA35 ON tproduct_host_more_viewed');
        $this->addSql('ALTER TABLE tproduct_host_more_viewed ADD id_host INT NOT NULL, DROP host_id, DROP product_host_id');
        $this->addSql('ALTER TABLE tsupplier_order DROP FOREIGN KEY FK_A67A545DA53A8AA');
        $this->addSql('ALTER TABLE tsupplier_order DROP FOREIGN KEY FK_A67A545DBE73AB31');
        $this->addSql('DROP INDEX IDX_A67A545DA53A8AA ON tsupplier_order');
        $this->addSql('DROP INDEX IDX_A67A545DBE73AB31 ON tsupplier_order');
        $this->addSql('ALTER TABLE tsupplier_order ADD id_provider INT NOT NULL, ADD id_supplier_order_status INT NOT NULL, DROP provider_id, DROP supplier_order_status_id');
        $this->addSql('ALTER TABLE ttechnical_sheet DROP FOREIGN KEY FK_58FA33AA4584665A');
        $this->addSql('ALTER TABLE ttechnical_sheet DROP FOREIGN KEY FK_58FA33AAD957CA06');
        $this->addSql('DROP INDEX IDX_58FA33AA4584665A ON ttechnical_sheet');
        $this->addSql('DROP INDEX IDX_58FA33AAD957CA06 ON ttechnical_sheet');
        $this->addSql('ALTER TABLE ttechnical_sheet ADD id_product INT NOT NULL, ADD id_option_value INT NOT NULL, DROP product_id, DROP option_value_id');
        $this->addSql('ALTER TABLE ttxt DROP FOREIGN KEY FK_266F6E801FB8D185');
        $this->addSql('ALTER TABLE ttxt DROP FOREIGN KEY FK_266F6E80CE61CA35');
        $this->addSql('DROP INDEX IDX_266F6E801FB8D185 ON ttxt');
        $this->addSql('DROP INDEX IDX_266F6E80CE61CA35 ON ttxt');
        $this->addSql('ALTER TABLE ttxt ADD id_product_host INT NOT NULL, DROP product_host_id, CHANGE host_id id_host INT NOT NULL');
    }
}
