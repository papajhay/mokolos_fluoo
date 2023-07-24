<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230724112038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taproduct_option DROP id_option');
        $this->addSql('ALTER TABLE taproduct_option_value DROP id_option_value');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taproduct_option ADD id_option INT NOT NULL');
        $this->addSql('ALTER TABLE taproduct_option_value ADD id_option_value INT NOT NULL');
    }
}
