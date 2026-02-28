<?php

namespace Tests\Feature;

use Tests\TestCase;

class MobileNavbarSectionTitleTest extends TestCase
{
    public function test_section_pages_render_mobile_section_title_in_brand(): void
    {
        $cases = [
            '/projects' => 'Projects',
            '/experience' => 'Experience',
            '/video' => 'Video',
        ];

        foreach ($cases as $uri => $expectedLabel) {
            $response = $this->get($uri);

            $response->assertStatus(200);
            $response->assertSeeTextInOrder(['Gan4x4', '/', $expectedLabel]);
        }
    }

    public function test_home_page_does_not_render_mobile_section_title_in_brand(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('brand-section d-inline d-md-none', false);
    }
}
