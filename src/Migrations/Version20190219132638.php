<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190219132638 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE publication ADD id_type_id INT NOT NULL, ADD name VARCHAR(50) NOT NULL, DROP id_user, DROP id_type, CHANGE create_date create_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C67791BD125E3 FOREIGN KEY (id_type_id) REFERENCES type_publication (id)');
        $this->addSql('CREATE INDEX IDX_AF3C67791BD125E3 ON publication (id_type_id)');
        $this->addSql('DROP INDEX name_UNIQUE ON type_publication');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C67791BD125E3');
        $this->addSql('DROP INDEX IDX_AF3C67791BD125E3 ON publication');
        $this->addSql('ALTER TABLE publication ADD id_type INT NOT NULL, DROP name, CHANGE create_date create_date DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE id_type_id id_user INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX name_UNIQUE ON type_publication (name)');
    }
}
