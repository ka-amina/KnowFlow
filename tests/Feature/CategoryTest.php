<?php

test('can see category list', function () {
    $response = $this->get('/api/categories');
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'name',
                'parent_id',
            ],
        ],
    ]);
});

test('can create a category and delete it', function () {
    // $response = $this->get('api/categories');
    // $response->assertStatus(200);

    $category = [
        'name' => 'dev',
        'parent_id' => null,
    ];

    $response = $this->post('api/categories', $category);

    $response->assertStatus(201);
    $category = $response->json('data');
    // $response->dump();

    $this->assertDatabaseHas('categories', [
        'id' => $category['id'],
        'name' => $category['name'],
        'parent_id' => $category['parent_id'],
    ]);

    $response = $this->delete("/api/categories/{$category['id']}");

    $response->status(200);

    $this->assertDatabaseMissing('categories', [
        'id' => $category['id'],
    ]);
});

test('can update a category', function () {
    $response = $this->get('api/categories');
    $response->assertStatus(200);

    $category = [
        'name' => 'test',
        'parent_id' => null
    ];

    $response = $this->post('api/categories', $category);
    $category = $response->json('data');

    $update = [
        'name' => 'test1',
        'parent_id' => null
    ];
    $response = $this->put("/api/categories/{$category['id']}", $update);
    $response->status(201);
    // $response->dump();
    $response = $this->delete("/api/categories/{$category['id']}");
    $response->status(200);
    $this->assertDatabaseMissing('categories',[
        'id'=>$category['id'],
    ]);

});

