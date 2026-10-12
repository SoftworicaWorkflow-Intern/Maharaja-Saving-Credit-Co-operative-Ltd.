<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_shows_the_cooperative_images_in_a_three_image_carousel_and_has_search(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('images/cooperative-service.png')
            ->assertSee('images/savings-growth.png')
            ->assertSee('images/nepali-cooperative-community.svg')
            ->assertSee('Search notices')
            ->assertDontSee('images.unsplash.com');

        $this->assertSame(3, substr_count($response->getContent(), 'data-hero-slide'));
    }

    public function test_homepage_shows_english_rotating_headlines_by_default(): void
    {
        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertSee('data-rotating-headline', false)
            ->assertSee('data-headline-slide', false)
            ->assertSee('Secure Savings,')
            ->assertSee('Your Trust,')
            ->assertSee('Growing Together,')
            ->assertSee('Empowering Communities Through')
            ->assertSee('aria-live="polite"', false);

        $this->assertSame(4, substr_count($response->getContent(), 'data-headline-slide'));
    }

    public function test_homepage_rotating_headlines_follow_the_selected_nepali_language(): void
    {
        $this->post(route('language.switch', ['locale' => 'ne']))
            ->assertRedirect();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('सुरक्षित बचत,')
            ->assertSee('तपाईंको विश्वास,')
            ->assertSee('सँगै अघि बढौँ,')
            ->assertSee('सहकारी वित्तमार्फत')
            ->assertDontSee('Brighter Futures.');
    }

    public function test_login_and_registration_pages_include_password_visibility_controls(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('data-password-toggle', false)
            ->assertSee('aria-controls="login-password"', false);

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('data-password-toggle', false)
            ->assertSee('aria-controls="register-password"', false)
            ->assertSee('aria-controls="register-password-confirmation"', false);
    }

    public function test_mobile_navigation_has_a_toggle_and_contains_site_links(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('data-mobile-nav-toggle', false)
            ->assertSee('aria-controls="mobile-navigation"', false)
            ->assertSee('id="mobile-navigation"', false)
            ->assertSee(route('about'), false)
            ->assertSee(route('register'), false);
    }
}
