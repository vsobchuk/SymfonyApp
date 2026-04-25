<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260425083815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add import_photos_token field to photos users';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ADD import_photos_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users DROP import_photos_token');
    }
}
