<?php

use App\Models\Category;

test('display course list', function () {
    $res = $this->get('/api/courses');
    $res->assertStatus(200);
    $res->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'title',
                'description',
                'duration',
                'level',
                'status',
                'category',
                'tags',
            ],
        ],
    ]);
});

test('can create a course and delete it', function () {


    $res = $this->get('api/categories');
    $res->assertStatus(200);

    $category = [
        'name' => 'dev',
        'parent_id' => null,
    ];

    $res = $this->post('api/categories', $category);

    $res->assertStatus(201);
    $category = $res->json('data');
    // $res->dump();



    $res = $this->get('api/tags');
    $res->assertStatus(200);

    $tag = [
        'name' => 'developement',
    ];

    $res = $this->post('api/tags', $tag);

    $res->assertStatus(201);
    $tag = $res->json('data');



    $course = [
        'title' => 'sample title',
        'description' => 'sample desc',
        'duration' => 5,
        'level' => 'intermediate',
        'status' => 'open',
        'category_id' => $category['id'],
        'tags' => [$tag['id']],
    ];

    $res = $this->post('api/courses', $course);

    $res->assertStatus(201);
    $course = $res->json('data');
    $res->dump();

    $this->assertDatabaseHas('courses', [
        'id' => $course['id'],
        'title' => $course['title'],
        'description' => $course['description'],
        'duration' => $course['duration'],
        'level' => $course['level'],
        'status' => $course['status'],
        'category_id' => $category['id'],
    ]);

    foreach ($course['tags'] as $tag) {
        $this->assertDatabaseHas('course_tag', [
            'course_id' => $course['id'],
            'tag_id' => $tag['id'],
        ]);
    }

    $res = $this->delete("/api/courses/{$course['id']}");

    $res->status(200);

    $this->assertDatabaseMissing('courses', [
        'id' => $course['id'],
    ]);
});

test('can update a course', function () {

    $category = [
        'name' => 'test',
        'parent_id' => null
    ];

    $res = $this->post('api/categories', $category);
    $res->assertStatus(201);
    $category = $res->json('data');

    $course = [
        'title' => 'new course',
        'description' => 'new course',
        'duration' => 2,
        'level' => 'intermediate',
        'status' => 'open',
        'category_id' => $category['id'], 
    ];

    $res = $this->post('api/courses', $course);
    $res->assertStatus(201);
    $course = $res->json('data');

    $update = [
        'title' => 'update',
        'description' => 'update',
        'duration' => 2,
        'level' => 'intermediate',
        'status' => 'open',
        'category_id' => $category['id'], 
    ];

    $res = $this->put("/api/courses/{$course['id']}", $update);
    $res->assertStatus(200); 
});

