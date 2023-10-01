<?php
namespace BankingApp\Interface;

interface Storage
{
    public function save( string $model, array $data );
    public function load( string $model ): array;
    public function getModelPath( string $model );
}