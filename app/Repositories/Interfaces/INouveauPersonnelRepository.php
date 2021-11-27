<?php
namespace App\Repositories\Interfaces;

interface INouveauPersonnelRepository {
    public function create(array $params);
    public function login(array $params);
}