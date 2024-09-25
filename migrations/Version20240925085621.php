<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240925085621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add driving license to customer';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer_driving_license (customer_id INT NOT NULL, driving_license_id INT NOT NULL, INDEX IDX_B71E5CA99395C3F3 (customer_id), INDEX IDX_B71E5CA93FFBF177 (driving_license_id), PRIMARY KEY(customer_id, driving_license_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_driving_license ADD CONSTRAINT FK_B71E5CA99395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE customer_driving_license ADD CONSTRAINT FK_B71E5CA93FFBF177 FOREIGN KEY (driving_license_id) REFERENCES driving_license (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_driving_license DROP FOREIGN KEY FK_B71E5CA99395C3F3');
        $this->addSql('ALTER TABLE customer_driving_license DROP FOREIGN KEY FK_B71E5CA93FFBF177');
        $this->addSql('DROP TABLE customer_driving_license');
    }
}
