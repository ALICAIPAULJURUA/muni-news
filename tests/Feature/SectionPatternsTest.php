<?php

namespace Tests\Feature;

use App\Models\SectionPattern;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SectionPatternsTest extends TestCase
{
    use RefreshDatabase;

    private function roles(): void
    {
        foreach (['super_admin', 'comm_admin', 'editor', 'viewer'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }

    private function superAdmin(): User
    {
        $this->roles();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        return $user;
    }

    public function test_super_admin_can_upload_pattern_image(): void
    {
        Storage::fake('public');
        $user = $this->superAdmin();

        $resp = $this->actingAs($user)->post(route('admin.patterns.store'), [
            'section_slug' => 'newsletter_cta',
            'section_name' => 'Newsletter CTA Section',
            'image' => UploadedFile::fake()->image('afro.png', 800, 800),
            'opacity' => '0.15',
            'blend_mode' => 'multiply',
            'is_active' => '1',
        ]);

        $resp->assertRedirect(route('admin.patterns.index'));

        $pattern = SectionPattern::where('section_slug', 'newsletter_cta')->first();
        $this->assertNotNull($pattern);
        $this->assertTrue($pattern->is_active);
        $this->assertSame('multiply', $pattern->blend_mode);
        $this->assertEqualsWithDelta(0.15, $pattern->opacity, 0.001);
        $this->assertNotNull($pattern->image_path);
        Storage::disk('public')->assertExists($pattern->image_path);
    }

    public function test_homepage_renders_active_pattern_overlay_on_cta(): void
    {
        SectionPattern::create([
            'section_slug' => 'newsletter_cta',
            'section_name' => 'Newsletter CTA Section',
            'image_path' => 'patterns/afro.png',
            'opacity' => 0.15,
            'blend_mode' => 'multiply',
            'is_active' => true,
        ]);

        $resp = $this->get(route('home'));
        $resp->assertOk();
        $resp->assertSee('newsletter-cta-section');
        $resp->assertSee('pattern-overlay');
        $resp->assertSee('mix-blend-mode:multiply');
        $resp->assertSee("storage/patterns/afro.png");
    }

    public function test_inactive_pattern_does_not_render_overlay(): void
    {
        SectionPattern::create([
            'section_slug' => 'newsletter_cta',
            'section_name' => 'Newsletter CTA Section',
            'image_path' => 'patterns/afro.png',
            'opacity' => 0.15,
            'blend_mode' => 'multiply',
            'is_active' => false,
        ]);

        $resp = $this->get(route('home'));
        $resp->assertOk();
        $resp->assertSee('newsletter-cta-section');
        $resp->assertDontSee('pattern-overlay');
    }

    public function test_viewer_cannot_access_patterns(): void
    {
        $this->roles();
        $user = User::factory()->create();
        $user->assignRole('viewer');

        $resp = $this->actingAs($user)->get(route('admin.patterns.index'));
        $resp->assertForbidden();
    }

    public function test_update_without_image_keeps_existing_image(): void
    {
        $user = $this->superAdmin();
        $pattern = SectionPattern::create([
            'section_slug' => 'hero',
            'section_name' => 'Hero Section',
            'image_path' => 'patterns/old.png',
            'opacity' => 0.15,
            'blend_mode' => 'multiply',
            'is_active' => true,
        ]);

        $resp = $this->actingAs($user)->put(route('admin.patterns.update', $pattern), [
            'section_slug' => 'hero',
            'section_name' => 'Hero Section',
            'image' => '',
            'opacity' => '0.2',
            'blend_mode' => 'overlay',
            'is_active' => '1',
        ]);

        $resp->assertRedirect(route('admin.patterns.index'));
        $pattern->refresh();
        $this->assertSame('patterns/old.png', $pattern->image_path);
        $this->assertSame('overlay', $pattern->blend_mode);
        $this->assertEqualsWithDelta(0.2, $pattern->opacity, 0.001);
    }
}