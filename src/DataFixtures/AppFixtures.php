<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Customer;
use App\Entity\DrivingLicense;
use App\Entity\Model;
use App\Entity\Option;
use App\Entity\Reservation;
use App\Entity\Type;
use App\Entity\Vehicle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // vehicle types
        $types = [
            'Berline', 'Break', 'Coupé', 'Cabriolet', 'Monospace', 'SUV', 'Utilitaire'
        ];
        $typeEntities = [];
        foreach ($types as $type) {
            $vehicleType = new Type();
            $vehicleType->setName($type);
            $typeEntities[] = $vehicleType;
        }

        // vehicle brands
        $brands = [
            'Citroën', 'Peugeot', 'Renault', 'Toyota',
        ];
        $brandEntities = [];
        foreach ($brands as $brand) {
            $vehicleBrand = new Brand();
            $vehicleBrand->setName($brand);
            $brandEntities[] = $vehicleBrand;
        }

        // vehicle models
        $models = [
            'Citroën' => ['C1', 'C3', 'C3 Aircross', 'C4', 'C4 Cactus', 'C5 Aircross', 'Berlingo', 'C-Zero', 'Jumper', 'Jumpy', 'Spacetourer', 'Rifter'],
            'Peugeot' => ['208', '2008', '308', '3008', '5008', '508', 'Rifter'],
            'Renault' => ['Zoe', 'Twingo', 'Clio', 'Mégane', 'Scénic', 'Talisman', 'Kangoo', 'Trafic', 'Master'],
            'Toyota' => ['Kadjar', 'Captur', 'Koleos', 'C-HR', 'Yaris', 'Aygo', 'Corolla', 'RAV4', 'Land Cruiser'],
        ];
        $modelEntities = [];
        foreach ($models as $brand => $brandModels) {
            foreach ($brandModels as $model) {
                $vehicleModel = new Model();
                $vehicleModel->setName($model);
                $vehicleModel->setBrand($brandEntities[array_search($brand, array_column($brandEntities, 'name'))]);
                $modelEntities[] = $vehicleModel;
            }
        }

        // vehicle options
        $options = [
            'Climatisation', 'GPS', 'Radar de recul', 'Caméra de recul', 'Régulateur de vitesse', 'Bluetooth', 'Sièges chauffants', 'Toit panoramique', 'Vitres teintées', 'Jantes alliage', 'Peinture métallisée', 'Attelage', 'Barres de toit', 'Coffre de toit', 'Porte-vélos', 'Chaînes à neige', 'Siège bébé', 'Siège enfant', 'Siège rehausseur', 'Pneus neige', 'Pneus chaînés', 'Pneus cloutés', 'Pneus hiver', 'Pneus été', 'Pneus 4 saisons', 'Pneus runflat', 'Pneus anti-crevaison', 'Pneus rechapés', 'Pneus rechapés neige', 'Pneus rechapés hiver', 'Pneus rechapés été', 'Pneus rechapés 4 saisons', 'Pneus rechapés runflat', 'Pneus rechapés anti-crevaison', 'Pneus rechapés cloutés', 'Pneus rechapés chaînés',
        ];
        $optionEntities = [];
        foreach ($options as $option) {
            $vehicleOption = new Option();
            $vehicleOption->setName($option);
            $optionEntities[] = $vehicleOption;
        }

        // vehicles
        $vehicleEntities = [];
        foreach ($modelEntities as $model) {
            foreach ($typeEntities as $type) {
                $vehicle = new Vehicle();
                $vehicle->setModel($model);
                $vehicle->setType($type);
                $vehicle->setPassengers(random_int(2, 9));
                $vehicle->setDailyRent(random_int(20, 200));
                $vehicle->setOdometer(random_int(0, 300000));
                $vehicle->setLicensePlate(sprintf('%s-%s-%s', chr(random_int(65, 90)), random_int(100, 999), chr(random_int(65, 90))));
                $vehicle->setProductionYear(random_int(2000, 2022));
                $vehicle->setPicture('https://picsum.photos/300');
                $vehicle->addOption($optionEntities[random_int(0, count($optionEntities) - 1)]);
                $vehicle->addOption($optionEntities[random_int(0, count($optionEntities) - 1)]);
                $vehicle->addOption($optionEntities[random_int(0, count($optionEntities) - 1)]);
                $vehicleEntities[] = $vehicle;
            }
        }

        // driving licenses
        $licenses = [
            'A', 'AM', 'B', 'C', 'CE',
        ];
        $licenseEntities = [];
        foreach ($licenses as $license) {
            $licenseEntity = new DrivingLicense();
            $licenseEntity->setName($license);
            $licenseEntities[] = $licenseEntity;
        }

        // customers
        $customers = [
            ['John', 'Doe', '10 Downing Street', 'SW1A 2AA', 'London', '[email protected]', '+44 20 7930 4832'],
            ['Jane', 'Doe', '1600 Pennsylvania Avenue NW', '20500', 'Washington, D.C.', ' [email protected]', '+1 202-456-1111'],
            ['Jean', 'Dupont', '55 Rue du Faubourg Saint-Honoré', '75008', 'Paris', ' [email protected]', '+33 1 42 92 81 00'],
            ['Pierre', 'Dupont', '2 Rue de l\'Élysée', '75008', 'Paris', ' [email protected]', '+33 1 42 92 81 00'],
        ];
        $customerEntities = [];
        foreach ($customers as $customer) {
            $customerEntity = new Customer();
            $customerEntity->setFirstName($customer[0]);
            $customerEntity->setLastName($customer[1]);
            $customerEntity->setAddress($customer[2]);
            $customerEntity->setZipCode($customer[3]);
            $customerEntity->setCity($customer[4]);
            $customerEntity->setEmail($customer[5]);
            $customerEntity->setPhoneNumber($customer[6]);
            $customerEntity->addDrivingLicense($licenseEntities[random_int(0, count($licenseEntities) - 1)]);
            $customerEntity->addDrivingLicense($licenseEntities[random_int(0, count($licenseEntities) - 1)]);
            $customerEntities[] = $customerEntity;
        }

        // reservation status
        $statuses = [
            'en attente', 'confirmée', 'annulée',
        ];
        $statusEntities = [];
        foreach ($statuses as $status) {
            $statusEntity = new \App\Entity\Status();
            $statusEntity->setName($status);
            $statusEntities[] = $statusEntity;
        }

        // reservations
        $reservations = [
            ['2022-01-01', '2022-01-02', 'en attente'],
            ['2022-01-03', '2022-01-04', 'en attente'],
            ['2022-01-05', '2022-01-06', 'en attente'],
            ['2022-01-07', '2022-01-08', 'en attente'],
        ];
        $reservationEntities = [];
        foreach ($reservations as $reservation) {
            $reservationEntity = new Reservation();
            $reservationEntity->setStartDate(new \DateTimeImmutable($reservation[0]));
            $reservationEntity->setEndDate(new \DateTimeImmutable($reservation[1]));
            $reservationEntity->setCustomer($customerEntities[random_int(0, count($customerEntities) - 1)]);
            $reservationEntity->setVehicle($vehicleEntities[random_int(0, count($vehicleEntities) - 1)]);
            $reservationEntity->setStatus($manager->getRepository(\App\Entity\Status::class)->findOneBy(['name' => $reservation[2]]));
            $reservationEntity->setReference(uniqid());
            $reservationEntity->setStatus($statusEntities[0]);
            $reservationEntities[] = $reservationEntity;
        }

        // persist entities
        foreach ($typeEntities as $type) {
            $manager->persist($type);
        }
        foreach ($brandEntities as $brand) {
            $manager->persist($brand);
        }
        foreach ($modelEntities as $model) {
            $manager->persist($model);
        }
        foreach ($optionEntities as $option) {
            $manager->persist($option);
        }
        foreach ($vehicleEntities as $vehicle) {
            $manager->persist($vehicle);
        }
        foreach ($licenseEntities as $license) {
            $manager->persist($license);
        }
        foreach ($customerEntities as $customer) {
            $manager->persist($customer);
        }
        foreach ($statusEntities as $status) {
            $manager->persist($status);
        }
        foreach ($reservationEntities as $reservation) {
            $manager->persist($reservation);
        }

        $manager->flush();
    }
}
