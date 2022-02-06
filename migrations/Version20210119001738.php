<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210119001738 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE tag_id tag_id INT DEFAULT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66F675F31B ON article (author_id)');
        $this->addSql('CREATE INDEX IDX_23A0E66BAD26311 ON article (tag_id)');
        $this->addSql('ALTER TABLE author CHANGE likes likes INT DEFAULT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE tags ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F675F31B');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66BAD26311');
        $this->addSql('DROP INDEX IDX_23A0E66F675F31B ON article');
        $this->addSql('DROP INDEX IDX_23A0E66BAD26311 ON article');
        $this->addSql('ALTER TABLE article DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE article CHANGE tag_id tag_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE author MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE author DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE author CHANGE likes likes INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tags MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE tags DROP PRIMARY KEY');
    }
}
