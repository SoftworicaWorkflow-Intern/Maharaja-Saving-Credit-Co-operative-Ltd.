<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'category' => 'membership',
                'question_en' => 'How can I become a member of Maharaj Bachat?',
                'question_np' => 'महाराज बचतको सदस्य कसरी बन्न सकिन्छ?',
                'answer_en' => 'Any permanent resident within our operating area can become a member by filling out the official Membership Application form, purchasing the minimum mandatory share units, paying the admission fee, and submitting the required identification documents to our office.',
                'answer_np' => 'हाम्रो कार्यक्षेत्रभित्र बसोबास गर्ने नेपाली नागरिकले संस्थाको सदस्यता फारम भरी, न्यूनतम सेयर खरिद गरी, प्रवेश शुल्क र आवश्यक परिचय कागजात पेस गरेर सजिलै सदस्य बन्न सक्नुहुन्छ। तपाईं अनलाइनमार्फत पनि आवेदन दिन सक्नुहुन्छ।',
                'display_order' => 1,
            ],
            [
                'category' => 'membership',
                'question_en' => 'What documents are required for cooperative membership?',
                'question_np' => 'सदस्यता लिन कुन-कुन कागजातहरू आवश्यक पर्छन्?',
                'answer_en' => 'You will need: (1) Copy of Nepali Citizenship Certificate, (2) Two passport-sized photographs, (3) Nominee identification and relation proof, and (4) Proof of permanent address (such as electricity bill, land certificate, or local municipality recommendation).',
                'answer_np' => 'सदस्यताका लागि आवश्यक कागजातहरू: (१) नेपाली नागरिकताको प्रमाणपत्रको प्रतिलिपि, (२) हालसालै खिचिएको २ प्रति पासपोर्ट साइजको फोटो, (३) हकवालाको परिचयपत्र र नाता प्रमाणित, र (४) ठेगाना प्रमाणित हुने कागजात (बिजुली बिल, लालपुर्जा वा वडाको सिफारिस)।',
                'display_order' => 2,
            ],
            [
                'category' => 'savings',
                'question_en' => 'How can I open a savings account?',
                'question_np' => 'बचत खाता कसरी खोल्न सकिन्छ?',
                'answer_en' => 'Once your membership is verified, you can immediately open any savings scheme (Regular, Monthly Recurring, Children\'s, Special, or Fixed Deposit) at any counter by completing a simple account opening slip with an initial opening balance starting from NPR 100.',
                'answer_np' => 'सहकारी सदस्यता प्राप्त भएपछि संस्थाको जुनसुकै काउन्टरमा गई साधारण, मासिक, बाल, विशेष वा मुद्दती बचत योजनामध्ये आफ्नो रोजाइको खाता खोल्न सक्नुहुन्छ। न्यूनतम रु. १०० बाटै साधारण खाता खोल्न सकिन्छ।',
                'display_order' => 3,
            ],
            [
                'category' => 'loans',
                'question_en' => 'How can I apply for a loan facility?',
                'question_np' => 'ऋण लिनको लागि के गर्नुपर्छ?',
                'answer_en' => 'Members with an active membership of at least 6 months and regular saving history can apply for credit by submitting the loan application form, collateral documentation (land/building ownership Lalpurja and municipal tax clearance), personal guarantee from a co-member, and proof of income. Loan applications are evaluated within 24 to 48 hours.',
                'answer_np' => 'न्यूनतम ६ महिनादेखि नियमित बचत गर्दै आएका सदस्यहरूले ऋण माग फारम, धितो सम्बन्धी कागजात (घरजग्गाको लालपुर्जा, नक्सा, तिरो रसिद), आयस्रोतको विवरण र अर्का एक सदस्यको जमानी पेस गरी कर्जाका लागि आवेदन दिन सक्नुहुन्छ। ऋण उपसमितिले २४ देखि ४८ घण्टाभित्र मूल्याङ्कन पूरा गर्छ।',
                'display_order' => 4,
            ],
            [
                'category' => 'interest',
                'question_en' => 'Where can I get verified information about current interest rates?',
                'question_np' => 'हालको ब्याजदर सम्बन्धी आधिकारिक जानकारी कहाँ पाइन्छ?',
                'answer_en' => 'Our interest rates are determined transparently by the Board of Directors in accordance with regulatory cooperative guidelines. You can view official interest rate notices on our website Notice Board, inside the branch premises on the public notice board, or by speaking directly with our customer service desk at 078-540905.',
                'answer_np' => 'संस्थाको ब्याजदर सहकारी ऐन र नियामक निकायको निर्देशन अनुसार सञ्चालक समितिले पारदर्शी रूपमा निर्धारण गर्दछ। अद्यावधिक ब्याजदर संस्थाको सूचना पाटी (वेबसाइट), कार्यालय परिसरको सूचना बोर्ड वा फोन नं ०७८-५४०९०५ मा सिधै सम्पर्क गरेर बुझ्न सकिन्छ।',
                'display_order' => 5,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::query()->updateOrCreate(
                ['question_en' => $faq['question_en']],
                $faq
            );
        }
    }
}

