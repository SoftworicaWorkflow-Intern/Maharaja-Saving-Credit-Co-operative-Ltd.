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
            ->assertSee('xl:flex', false)
            ->assertSee('xl:hidden', false)
            ->assertSee('whitespace-nowrap rounded-full bg-white px-3 py-2 text-sm font-semibold text-[#04552F]', false)
            ->assertSee('>About Us</a>', false)
            ->assertSee('>Become a Member</a>', false)
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
            ->assertSee('<html lang="ne">', false)
            ->assertSee('हाम्रो बारेमा')
            ->assertSee('बचत')
            ->assertSee('सूचनाहरू')
            ->assertSee('सदस्य बन्नुहोस्')
            ->assertSee('सदस्य केन्द्रित वित्तीय सेवाहरू')
            ->assertSee('लचिलो बचत योजनाहरू')
            ->assertSee('ऋण सहायता')
            ->assertSee('नेतृत्वका सन्देशहरू')
            ->assertSee('विश्वास र सहकार्यबाट सँगै अघि बढ्दै')
            ->assertSee('अध्यक्षको सन्देश')
            ->assertSee('पूरा सन्देश पढ्नुहोस्')
            ->assertSee('सुरक्षित बचत,')
            ->assertSee('तपाईंको विश्वास,')
            ->assertSee('सँगै अघि बढौँ,')
            ->assertSee('सहकारी वित्तमार्फत')
            ->assertDontSee('Brighter Futures.');
    }

    public function test_public_information_pages_render_in_nepali_after_language_selection(): void
    {
        $pages = [
            'about' => ['सहकारीबारे', 'हाम्रो परिचय', 'ध्येय र दृष्टि'],
            'savings' => ['सदस्य बचत योजनाहरू', 'नियमित बचत', 'अहिले आवेदन दिनुहोस्'],
            'loans' => ['सदस्यका आवश्यकताका लागि वित्तीय सहयोग', 'व्यक्तिगत ऋण', 'जानकारी माग्नुहोस्'],
            'notices' => ['नवीनतम घोषणाहरू', 'सबै वर्ग', 'छान्नुहोस्', 'कुनै सूचना फेला परेन।'],
            'contact' => ['सहकारीसँग सम्पर्क गर्नुहोस्', 'कार्यालयको जानकारी', 'सन्देश पठाउनुहोस्', 'हाम्रो कार्यालय खोज्नुहोस्'],
        ];

        foreach ($pages as $page => $translatedTexts) {
            $response = $this->withSession(['locale' => 'ne'])
                ->get(route($page))
                ->assertOk()
                ->assertSee('<html lang="ne">', false);

            foreach ($translatedTexts as $translatedText) {
                $response->assertSee($translatedText);
            }
        }
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

    public function test_what_we_offer_cards_link_to_their_dedicated_pages(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('id="what-we-offer"', false)
            ->assertSee('bg-[#08713D]', false)
            ->assertSee('What we offer')
            ->assertSee('Financial services built around members')
            ->assertSee('data-offer-link="savings" href="'.route('savings').'"', false)
            ->assertSee('data-offer-link="loans" href="'.route('loans').'"', false)
            ->assertSee('data-offer-link="notices" href="'.route('notices').'"', false)
            ->assertSee('Savings Products')
            ->assertSee('Loan Support')
            ->assertSee('Notices & Updates')
            ->assertDontSee('data-offer-panel', false)
            ->assertDontSee('data-offer-tab', false)
            ->assertDontSee('Regular Savings')
            ->assertDontSee('Personal & Household Loan')
            ->assertDontSee('Stay Informed With Official Cooperative Notices');

        $this->withSession(['locale' => 'ne'])
            ->get(route('home'))
            ->assertOk()
            ->assertSee('id="what-we-offer"', false)
            ->assertSee('bg-[#08713D]', false)
            ->assertSee('data-offer-link="savings" href="'.route('savings').'"', false)
            ->assertSee('data-offer-link="loans" href="'.route('loans').'"', false)
            ->assertSee('data-offer-link="notices" href="'.route('notices').'"', false)
            ->assertSee('हामी के प्रदान गर्छौँ')
            ->assertSee('सदस्य केन्द्रित वित्तीय सेवाहरू')
            ->assertSee('बचत योजनाहरू')
            ->assertSee('ऋण सहायता')
            ->assertSee('सूचना तथा ताजा जानकारी');
    }
}
