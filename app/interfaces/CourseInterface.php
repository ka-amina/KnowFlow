<?php

namespace App\Interfaces;

interface CourseInterface
{
    public function getAll();
    public function getById($id);
    public function create($data);
    public function update($id, $data);
    public function delete($id);
    public function filterCourses($categoryName, $difficulty);
    public function searchCourses($input);
}
