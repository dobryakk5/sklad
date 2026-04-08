<?php

namespace App\Domain\Box;

enum RentalMode: string
{
    case Box = 'box';
    case Container = 'container';
    case Cell = 'cell';
    case Storage = 'storage';
    case Room = 'room';
}
