<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210310125227 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE logiciel (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE version (id INT AUTO_INCREMENT NOT NULL, logiciel_id INT NOT NULL, numero INT NOT NULL, commentaires VARCHAR(255) DEFAULT NULL, url_mac VARCHAR(255) DEFAULT NULL, url_win VARCHAR(255) DEFAULT NULL, date_ajout DATE NOT NULL, INDEX IDX_BF1CD3C3CA84195D (logiciel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE version ADD CONSTRAINT FK_BF1CD3C3CA84195D FOREIGN KEY (logiciel_id) REFERENCES logiciel (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE version DROP FOREIGN KEY FK_BF1CD3C3CA84195D');
        $this->addSql('DROP TABLE logiciel');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE version');
    }
}
