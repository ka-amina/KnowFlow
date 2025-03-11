<?php

namespace App\interfaces;

interface CategoryInterface
{
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function delete($id);
    public function update($id, $data);
}
