<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191007130056 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recommendation (id INT AUTO_INCREMENT NOT NULL, workout_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_433224D2A6CCCFC9 (workout_id), INDEX IDX_433224D2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE api_token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, expires_at DATETIME NOT NULL, INDEX IDX_7BA2F5EBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercise (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, difficulty_level INT NOT NULL, minutes INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_AEDAD51CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercise_workout (exercise_id INT NOT NULL, workout_id INT NOT NULL, INDEX IDX_BE8A0C37E934951A (exercise_id), INDEX IDX_BE8A0C37A6CCCFC9 (workout_id), PRIMARY KEY(exercise_id, workout_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workout (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_649FFB72A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recommendation ADD CONSTRAINT FK_433224D2A6CCCFC9 FOREIGN KEY (workout_id) REFERENCES workout (id)');
        $this->addSql('ALTER TABLE recommendation ADD CONSTRAINT FK_433224D2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE api_token ADD CONSTRAINT FK_7BA2F5EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exercise ADD CONSTRAINT FK_AEDAD51CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exercise_workout ADD CONSTRAINT FK_BE8A0C37E934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercise_workout ADD CONSTRAINT FK_BE8A0C37A6CCCFC9 FOREIGN KEY (workout_id) REFERENCES workout (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE workout ADD CONSTRAINT FK_649FFB72A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recommendation DROP FOREIGN KEY FK_433224D2A76ED395');
        $this->addSql('ALTER TABLE api_token DROP FOREIGN KEY FK_7BA2F5EBA76ED395');
        $this->addSql('ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51CA76ED395');
        $this->addSql('ALTER TABLE workout DROP FOREIGN KEY FK_649FFB72A76ED395');
        $this->addSql('ALTER TABLE exercise_workout DROP FOREIGN KEY FK_BE8A0C37E934951A');
        $this->addSql('ALTER TABLE recommendation DROP FOREIGN KEY FK_433224D2A6CCCFC9');
        $this->addSql('ALTER TABLE exercise_workout DROP FOREIGN KEY FK_BE8A0C37A6CCCFC9');
        $this->addSql('DROP TABLE recommendation');
        $this->addSql('DROP TABLE api_token');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE exercise');
        $this->addSql('DROP TABLE exercise_workout');
        $this->addSql('DROP TABLE workout');
    }
}
