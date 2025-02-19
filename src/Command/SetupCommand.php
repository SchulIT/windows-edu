<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;

#[AsCommand(name: 'app:setup', description: 'Runs the initial setup.')]
class SetupCommand extends Command {

    public function __construct(private readonly Connection $dbalConnection,
                                private readonly PdoSessionHandler $pdoSessionHandler,
                                ?string $name = null) {
        parent::__construct($name);
    }

    public function execute(InputInterface $input, OutputInterface $output): int {
        $io = new SymfonyStyle($input, $output);
        $this->setupSessions($io);
        $io->success('Setup abgeschlossen');

        return Command::SUCCESS;
    }

    private function setupSessions(SymfonyStyle $io): void {
        $io->section('Sessions');
        $io->write('Prüfe, ob sessions-Tabelle existiert...');
        $sql = "SHOW TABLES LIKE 'sessions';";
        $row = $this->dbalConnection->executeQuery($sql);

        if($row->fetchAssociative() === false) {
            $io->write('Tabelle existiert nicht, lege an...');
            $this->pdoSessionHandler->createTable();
            $io->success('Tabelle angelegt');
        } else {
            $io->success('Tabelle existiert bereits');
        }

        $io->writeln('Prüfe, ob Feld für Daten MEDIUMBLOB ist...');
        // Check if field is of type "MEDIUMBLOB" to allow more session data
        $sql = "SHOW COLUMNS FROM `sessions` WHERE `Field` = 'sess_data'";
        $row = $this->dbalConnection->executeQuery($sql)->fetchAssociative();

        if(strtolower((string) $row['Type']) === 'mediumblob') {
            $io->success('Feld ist bereits MEDIUMBLOB. Fertig.');
        } else {
            $io->writeln(sprintf('Ändere Feld von %s zu MEDIUMBLOB', $row['Type']));
            $sql = "ALTER TABLE `sessions` MODIFY `sess_data` MEDIUMBLOB";
            $this->dbalConnection->executeQuery($sql);
            $io->success('Fertig');
        }
    }
}