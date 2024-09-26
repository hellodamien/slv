<?php

namespace App\DTO;

use App\Entity\Type;
use DateTime;

class HomeVehicleSearch
{
    public DateTime $startDate;
    public DateTime $endDate;
    public ?Type $type;
    public int $limit;

    public static function create
    (
        DateTime $startDate,
        DateTime $endDate,
        Type     $type,
        int      $limit = 6
    ): self
    {
        $dto = new self();

        $dto->startDate = $startDate;
        $dto->endDate   = $endDate;
        $dto->type      = $type;
        $dto->limit     = $limit;

        return $dto;
    }
}