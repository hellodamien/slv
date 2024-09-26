<?php

namespace App\DTO;

use App\Entity\Type;
use DateTime;

class HomeVehicleSearch
{
    public DateTime $startDate;
    public DateTime $endDate;
    public ?Type    $type;
    public int      $page;
    public int      $itemsPerPage;

    public static function create
    (
        DateTime $startDate,
        DateTime $endDate,
        Type     $type,
        int      $page,
        int      $itemsPerPage = 6,
    ): self
    {
        $dto = new self();

        $dto->startDate    = $startDate;
        $dto->endDate      = $endDate;
        $dto->type         = $type;
        $dto->page         = $page;
        $dto->itemsPerPage = $itemsPerPage;

        return $dto;
    }
}