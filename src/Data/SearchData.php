<?php

namespace App\Data;

use App\Entity\Campus;
use App\Entity\Sortie;
use Symfony\Component\Validator\Constraints as Assert;

class SearchData
{
    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var Campus
     */
    public $campus ;

    /**
     * @var boolean
     */
    public $organisateur;

    /**
     * @var boolean
     */
    public $inscrit;

    /**
     * @var boolean
     */
    public $nonInscrit;

    /**
     * @var boolean
     */
    public $sortiePassees;

    /**
     * @var Assert\Date
     */
    public $dateDebut ;

    /**
     * @var Assert\Date
     */
    public $dateFin ;

    /**
     * @var string
     */
    public $q = '';


}