<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230706072754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achattodb_email (id INT AUTO_INCREMENT NOT NULL, email_from VARCHAR(255) NOT NULL, email_from_p VARCHAR(255) NOT NULL, email_to VARCHAR(255) NOT NULL, date_e DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_db DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status INT NOT NULL, del INT NOT NULL, subject VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, message_html LONGTEXT NOT NULL, message_size INT NOT NULL, datetime_send DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tlock_process (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, timestamp INT NOT NULL, stage VARCHAR(255) NOT NULL, stage_timestamp INT NOT NULL, memory INT NOT NULL, delete_me_on_destruct TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE achattodb_email');
        $this->addSql('DROP TABLE tlock_process');
    }
}
