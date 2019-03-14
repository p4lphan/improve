<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190219132926 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C67791BD125E3');
        $this->addSql('DROP INDEX IDX_AF3C67791BD125E3 ON publication');
        $this->addSql('ALTER TABLE publication CHANGE id_type id_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C67791BD125E3 FOREIGN KEY (id_type_id) REFERENCES type_publication (id)');
        $this->addSql('CREATE INDEX IDX_AF3C67791BD125E3 ON publication (id_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C67791BD125E3');
        $this->addSql('DROP INDEX IDX_AF3C67791BD125E3 ON publication');
        $this->addSql('ALTER TABLE publication CHANGE id_type_id id_type INT NOT NULL');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C67791BD125E3 FOREIGN KEY (id_type) REFERENCES type_publication (id)');
        $this->addSql('CREATE INDEX IDX_AF3C67791BD125E3 ON publication (id_type)');
    }
}
