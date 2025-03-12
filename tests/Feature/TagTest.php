<?php

test('can see tags list', function () {
    $res = $this->get('api/tags');
    $res->assertStatus(200);
    // $res->dump();
    $res->assertJsonStructure([
        'data' => [
            '*' => [
                'name',
            ],
        ],
    ]);
});

test('can create a tag and delete it', function () {
    // $res = $this->get('api/tags');
    // $res->assertStatus(200);

    $tag = [
        'name' => 'developement',
    ];

    $res = $this->post('api/tags', $tag);

    $res->assertStatus(201);
    $tag = $res->json('data');
    // $res->dump();

    $this->assertDatabaseHas('tags', [
        'name' => $tag['name'],
    ]);

    $res = $this->delete("/api/tags/{$tag['id']}");

    $res->status(200);

    $this->assertDatabaseMissing('tags', [
        'id' => $tag['id'],
    ]);
});

test('can update a tag', function () {
    // $res = $this->get('api/tags');
    // $res->assertStatus(200);

    $tag = [
        'name' => 'tagName',
    ];

    $res = $this->post('api/tags', $tag);
    // $res->dump();
    $tag = $res->json('data');

    $update = [
        'name' => 'tagName1',
    ];
    $res = $this->put("/api/tags/{$tag['id']}", $update);
    $res->status(201);
    // $res->dump();
    $res = $this->delete("/api/tags/{$tag['id']}");
    $res->status(200);
    $this->assertDatabaseMissing('tags',[
        'id'=>$tag['id'],
    ]);

});

test('cannot update a non-existent tag', function () {
    $update = ['name' => 'newTagName'];

    $res = $this->put('/api/tags/9999', $update); 

    $res->assertStatus(404);
});
