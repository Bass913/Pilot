<?php

namespace App\Tests\Entity;

use App\Entity\Statistics;
use PHPUnit\Framework\TestCase;
use ApiPlatform\Core\Annotation\ApiResource;


class StatisticsTest extends TestCase
{
    private Statistics $statistics;

    protected function setUp(): void
    {
        $this->statistics = new Statistics();
    }
    /**
    public function testAnnotations(): void
    {
        $reflectionClass = new \ReflectionClass(Statistics::class);

        // Check if class has ApiResource annotation
        $this->assertTrue($reflectionClass->hasAnnotation(ApiResource::class), 'Missing ApiResource annotation');

        // Fetch ApiResource annotation
        $apiResourceAnnotation = $reflectionClass->getAttributes(ApiResource::class)[0]->newInstance();
        $this->assertEquals('statistics', $apiResourceAnnotation->getShortName());

        // Test each operation definition
        $operations = $apiResourceAnnotation->getOperations();
        $this->assertCount(2, $operations, 'Expected 2 operations');

        // Check operation paths
        $this->assertEquals('/stats/super_admin', $operations[0]->getUriTemplate());
        $this->assertEquals('/stats/admin/{id}', $operations[1]->getUriTemplate());

        // Check operation controllers
        $this->assertEquals(StatisticsSuperAdminController::class, $operations[0]->getController());
        $this->assertEquals(StatisticsAdminController::class, $operations[1]->getController());
    }  */

    public function testGetSetTotalReservations(): void
    {
        $this->statistics->setTotalReservations(100);
        $this->assertEquals(100, $this->statistics->getTotalReservations());
    }

    public function testGetSetTotalClients(): void
    {
        $this->statistics->setTotalClients(50);
        $this->assertEquals(50, $this->statistics->getTotalClients());
    }

    public function testGetSetTodaysReservations(): void
    {
        $this->statistics->setTodaysReservations(10);
        $this->assertEquals(10, $this->statistics->getTodaysReservations());
    }

    public function testGetSetWeeklyReservations(): void
    {
        $this->statistics->setWeeklyReservations(70);
        $this->assertEquals(70, $this->statistics->getWeeklyReservations());
    }

    public function testGetSetMonthlyReservations(): void
    {
        $this->statistics->setMonthlyReservations(300);
        $this->assertEquals(300, $this->statistics->getMonthlyReservations());
    }

    public function testGetSetActiveReservations(): void
    {
        $this->statistics->setTotalActiveReservations(25);
        $this->assertEquals(25, $this->statistics->getTotalActiveReservations());
    }

    public function testGetSetCancelledReservations(): void
    {
        $this->statistics->setTotalCancelledReservations(5);
        $this->assertEquals(5, $this->statistics->getTotalCancelledReservations());
    }

    public function testGetSetDistinctClientsWithReservationsPerWeek(): void
    {
        $this->statistics->setDistinctClientsWithReservationsPerWeek(15);
        $this->assertEquals(15, $this->statistics->getDistinctClientsWithReservationsPerWeek());
    }

    public function testGetSetTotalServicesPerCompany(): void
    {
        $totalServices = [
            'Company A' => 10,
            'Company B' => 15
        ];
        $this->statistics->setTotalServicesPerCompany($totalServices);
        $this->assertEquals($totalServices, $this->statistics->getTotalServicesPerCompany());
    }
}
