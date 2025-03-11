<?php

namespace App\Interfaces;

interface TagInterface
{
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function delete($id);
    public function update($id, $data);
}
