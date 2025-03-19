<?php

namespace App\Interfaces;

interface VideoInterface
{
    public function getAll();
    public function getByCourseId($courseId);
    public function getById($id);
    public function create($data, $courseId);
    public function update($id, $data);
    public function delete($id);
}
