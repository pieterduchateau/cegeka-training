<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Psr\Log\LoggerInterface;
use App\Entity\Hotel;
use App\Entity\Flight;

class GetDataCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'get-data';

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine')->getManager();

        $agencies = $this->em->getRepository("App:Agency")->findAll();
        foreach($agencies as $agency) {
            $data = $this->getApiData($agency,"hotels");
            $this->addHotels($agency,$data);
            $data = $this->getApiData($agency,"flights");
            $this->addFlights($agency,$data);
        }
    }

    protected function getApiData($agency,$type)
    {
        $username = "api";
        $password = "api";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $agency->getUrl() . 'api/'. $type . '.json');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        $response = curl_exec($ch);

        $data = json_decode($response);

        return $data;
    }

    protected function addHotels($agency,$data)
    {
        if ($data) {
            foreach($data as $hotel) {
                if ($hotel->owned) {
                    $entity = $this->em->getRepository("App:Hotel")->findOneBy(array('agency' => $agency, 'remoteId' => $hotel->id));
                    if (!$entity) {
                        $entity = new Hotel();
                        $entity->setAgency($agency);
                        $entity->setRemoteId($hotel->id);
                    }
                    $entity->setName($hotel->name);
                    $entity->setLocation($hotel->location);
                    $entity->setStart(new \DateTime($hotel->start));
                    $entity->setEnd(new \DateTime($hotel->end));
                    $entity->setStars($hotel->stars);
                    $entity->setPrice($hotel->price);
                    $entity->setOwned(false);

                    $this->em->persist($entity);
                }
            }
            $this->em->flush();
        } else {

        }
    }

    protected function addFlights($agency,$data)
    {
        if ($data) {
            foreach($data as $flight) {
                if ($flight->owned) {
                    $entity = $this->em->getRepository("App:Flight")->findOneBy(array('agency' => $agency, 'remoteId' => $flight->id));
                    if (!$entity) {
                        $entity = new Flight();
                        $entity->setAgency($agency);
                        $entity->setRemoteId($flight->id);
                    }
                    var_dump($data);
                    $entity->setAirline($flight->airline);
                    $entity->setFlightFrom($flight->from);
                    $entity->setFlightTo($flight->to);
                    $entity->setStart(new \DateTime($flight->start));
                    $entity->setEnd(new \DateTime($flight->end));
                    $entity->setDuration($flight->duration);
                    $entity->setTimeofday(new \DateTime($flight->timeofday));
                    $entity->setPrice($flight->price);
                    $entity->setOwned(false);

                    $this->em->persist($entity);
                }
            }
            $this->em->flush();
        }
    }
}
