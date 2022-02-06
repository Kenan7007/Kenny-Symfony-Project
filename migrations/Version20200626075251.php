<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200626075251 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE author (id_author INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, likes INT NOT NULL, PRIMARY KEY(id_author)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE authors');
        $this->addSql('ALTER TABLE articles CHANGE title title VARCHAR(255) NOT NULL, CHANGE id_author id_author INT DEFAULT NULL, CHANGE date date DATETIME NOT NULL, CHANGE tags tags VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD31689B986D25 FOREIGN KEY (id_author) REFERENCES author (id_author)');
        $this->addSql('CREATE INDEX IDX_BFDD31689B986D25 ON articles (id_author)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD31689B986D25');
        $this->addSql('CREATE TABLE authors (id_author INT NOT NULL, email VARCHAR(320) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nume VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, likes INT NOT NULL, UNIQUE INDEX UC_Authors (email), PRIMARY KEY(id_author)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP INDEX IDX_BFDD31689B986D25 ON articles');
        $this->addSql('ALTER TABLE articles CHANGE id_author id_author INT NOT NULL, CHANGE title title VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE date date DATETIME DEFAULT \'NULL\', CHANGE tags tags VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE image image VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`');
    }
}
