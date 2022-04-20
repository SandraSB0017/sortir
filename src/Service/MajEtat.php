<?php

namespace App\Service;


use App\Repository\SortieRepository;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class MajEtat
{
    private SortieRepository $sortieRepository;

    public function __construct(SortieRepository $sortieRepository)
    {
        $this->sortieRepository=$sortieRepository;
    }

    public function etatMaj(){

}
}
