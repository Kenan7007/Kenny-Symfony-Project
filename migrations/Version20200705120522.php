<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200705120522 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD tag_id INT DEFAULT NULL, ADD tags VARCHAR(255) DEFAULT NULL, DROP tags_id');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66BAD26311 ON article (tag_id)');
        $this->addSql('ALTER TABLE author CHANGE likes likes INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66BAD26311');
        $this->addSql('DROP INDEX IDX_23A0E66BAD26311 ON article');
        $this->addSql('ALTER TABLE article ADD tags_id INT NOT NULL, DROP tag_id, DROP tags');
        $this->addSql('ALTER TABLE author CHANGE likes likes INT DEFAULT NULL');
    }
}
