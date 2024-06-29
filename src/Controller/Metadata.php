<?php

namespace App\Controller;

use App\Repository\MediaRepository;
use App\Seo\Seo;

readonly class Metadata
{
    public function __construct(
        private MediaRepository $mediaRepository
    )
    {
    }

    public function seo(): Seo
    {
        return new Seo(
            'Vicyma Residence',
            'La résidence Vicyma est une résidence de Bafoussam proposant des appartements à la location',
            $this->mediaRepository->findAll()[0]
        );
    }
}