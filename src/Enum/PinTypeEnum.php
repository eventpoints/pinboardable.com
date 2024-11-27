<?php

declare(strict_types=1);

namespace App\Enum;

enum PinTypeEnum: string
{
    case BUSINESS = 'business'; // Businesses, whether new or existing
    case PRODUCT = 'product'; // Physical or digital products (e.g., books, games, merchandise)
    case SERVICE = 'service'; // Specific services offered (e.g., consulting, repairs)
    case EVENT = 'event'; // Events like workshops, launches, or meetups
    case OFFER = 'offer'; // Promotions, discounts, or special deals
    case ANNOUNCEMENT = 'announcement'; // General updates or public notices
}
