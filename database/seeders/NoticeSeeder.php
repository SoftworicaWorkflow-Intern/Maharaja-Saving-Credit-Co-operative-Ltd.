<?php

namespace Database\Seeders;

use App\Models\Notice;
use Illuminate\Database\Seeder;

class NoticeSeeder extends Seeder
{
    public function run(): void
    {
        $notices = [
            [
                'title_en' => 'Annual General Meeting Notice',
                'title_np' => 'वार्षिक साधारण सभा सम्बन्धी सूचना',
                'body_en' => 'Members are invited to attend the upcoming annual general meeting. Please bring your membership details.',
                'body_np' => 'आगामी वार्षिक साधारण सभामा उपस्थित हुन सम्पूर्ण सदस्यहरूलाई अनुरोध गरिन्छ। कृपया आफ्नो सदस्यता विवरण साथमा ल्याउनुहोस्।',
                'category' => 'general',
            ],
            [
                'title_en' => 'Updated Savings Service Hours',
                'title_np' => 'अद्यावधिक बचत सेवा समय',
                'body_en' => 'Our service counters are open during regular office hours for savings deposits and member support.',
                'body_np' => 'बचत जम्मा तथा सदस्य सेवाका लागि हाम्रा सेवा काउण्टरहरू नियमित कार्यालय समयमा खुला रहनेछन्।',
                'category' => 'savings',
            ],
            [
                'title_en' => 'Loan Application Support Available',
                'title_np' => 'ऋण आवेदन सहायता उपलब्ध',
                'body_en' => 'Members can contact the cooperative team for guidance on personal, business, and emergency loan applications.',
                'body_np' => 'व्यक्तिगत, व्यवसायिक तथा आपतकालीन ऋण आवेदनसम्बन्धी मार्गदर्शनका लागि सदस्यहरूले सहकारी टोलीसँग सम्पर्क गर्न सक्नुहुन्छ।',
                'category' => 'loan',
            ],
        ];

        foreach ($notices as $notice) {
            Notice::query()->updateOrCreate(
                ['title_en' => $notice['title_en']],
                [
                    ...$notice,
                    'status' => 'published',
                    'featured' => true,
                    'published_at' => now(),
                ],
            );
        }
    }
}
