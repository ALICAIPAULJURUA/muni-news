<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function(){
    // Ensure roles exist for testing (DatabaseSeeder not run in Pest)
    foreach(['super_admin','comm_admin','editor','viewer'] as $r){
        Role::firstOrCreate(['name'=>$r]);
    }
});

test('admin dashboard requires auth', function(){
    $res = $this->get('/admin/dashboard');
    $res->assertRedirect('/login');
});

test('super_admin can access admin dashboard', function(){
    $admin = User::factory()->create();
    $admin->assignRole('super_admin');
    $res = $this->actingAs($admin)->get('/admin/dashboard');
    $res->assertStatus(200);
    $res->assertSee('Dashboard');
});

test('viewer cannot access admin dashboard', function(){
    $viewer = User::factory()->create();
    $viewer->assignRole('viewer');
    $res = $this->actingAs($viewer)->get('/admin/dashboard');
    $res->assertStatus(403);
});

test('editor can access articles index', function(){
    $editor = User::factory()->create();
    $editor->assignRole('editor');
    $res = $this->actingAs($editor)->get('/admin/articles');
    $res->assertStatus(200);
    $res->assertSee('Articles');
});

test('editor cannot access categories (only comm_admin/super_admin)', function(){
    $editor = User::factory()->create();
    $editor->assignRole('editor');
    $res = $this->actingAs($editor)->get('/admin/categories');
    $res->assertStatus(403);
});

test('comm_admin can access categories', function(){
    $user = User::factory()->create();
    $user->assignRole('comm_admin');
    $res = $this->actingAs($user)->get('/admin/categories');
    $res->assertStatus(200);
});

test('admin article create page has TinyMCE', function(){
    $admin = User::factory()->create();
    $admin->assignRole('super_admin');
    $res = $this->actingAs($admin)->get('/admin/articles/create');
    $res->assertStatus(200);
    $res->assertSee('tinymce');
    $res->assertSee('/admin/upload-image');
});

test('TinyMCE image upload validates and stores', function(){
    $admin = User::factory()->create();
    $admin->assignRole('editor');
    $file = \Illuminate\Http\UploadedFile::fake()->image('test.jpg', 800, 600);
    $res = $this->actingAs($admin)->post('/admin/upload-image', ['file'=>$file]);
    $res->assertStatus(200);
    $res->assertJson(['location'=>true]);
    expect($res->json('location'))->toContain('/storage/articles/');
});

test('admin can create article with tags', function(){
    $admin = User::factory()->create();
    $admin->assignRole('editor');
    $cat = \App\Models\Category::first() ?? \App\Models\Category::firstOrCreate(['slug'=>'test-cat'], ['name'=>'Test Cat','sort_order'=>0]);
    $res = $this->actingAs($admin)->post('/admin/articles', [
        'title'=>'Test Article From Admin',
        'category_id'=>$cat->id,
        'summary'=>'Test summary for admin article',
        'content'=>'<p>Test content with <strong>bold</strong></p>',
        'is_published'=>1,
        'tags'=>['test','muni'],
    ]);
    $res->assertRedirect(route('admin.articles.index'));
    $this->assertDatabaseHas('articles', ['title'=>'Test Article From Admin']);
});
