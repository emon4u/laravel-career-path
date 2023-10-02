<?php
namespace BankingApp\Classes;

use BankingApp\Interface\Storage;

class JsonStorage implements Storage
{
    public function save( string $model, array $data ): bool
    {
        $jsonData = json_encode( $data, JSON_PRETTY_PRINT );

        $written = file_put_contents( $this->getModelPath( $model ), $jsonData );

        if ( $written !== false ) {
            return true;
        }

        return false;
    }

    public function load( string $model ): array
    {
        $data = [];

        if ( file_exists( $this->getModelPath( $model ) ) ) {
            $jsonData = file_get_contents( $this->getModelPath( $model ) );

            return json_decode( $jsonData, true );
        }

        return $data;
    }

    public function getModelPath( string $model ): string
    {
        return __DIR__ . '/../data/' . $model . ".json";
    }
}
