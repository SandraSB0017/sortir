<?php

namespace App\Data;

use App\Entity\Campus;

class SearchData
{

    public int $page = 1;


    public ?Campus $campus=null ;


    public bool $organisateur = false;


    public bool $inscrit = false;


    public bool $nonInscrit=false;


    public bool $sortiePassees =false;


    public ?\DateTime $dateDebut =null;


    public ?\DateTime $dateFin =null;


    public ?string $q = '';


}