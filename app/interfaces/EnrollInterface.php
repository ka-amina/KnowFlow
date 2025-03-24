<?php

namespace App\Interfaces;

interface EnrollInterface
{
    public function getAll();
    public function findById($id);
    public function create($data);
    public function update($id, $data);
    public function delete($id);
    public function updateStatus($id, $status);
    public function isUserEnrolled($userId, $courseId);
}
