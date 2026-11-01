<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            // SAVINGS
            [
                'slug' => 'regular-savings',
                'category' => 'savings',
                'title_en' => 'Regular Savings',
                'title_np' => 'साधारण बचत',
                'short_description_en' => 'Flexible everyday savings scheme designed to encourage regular saving habits with complete liquidity.',
                'short_description_np' => 'दैनिक तथा नियमित बचत गर्ने बानी विकास गर्न र आवश्यक परेको बखत सहज भुक्तानी पाउने लचिलो बचत योजना।',
                'full_description_en' => 'The Regular Savings scheme is designed for all cooperative members who desire a safe place for their daily or weekly savings. Members can deposit and withdraw funds at any time during counter hours with no withdrawal restrictions, while enjoying competitive interest rates calculated on daily balances.',
                'full_description_np' => 'साधारण बचत खाता सहकारीका सम्पूर्ण सदस्यहरूका लागि सुरक्षित र सहज बचत सेवा प्रदान गर्न सञ्चालन गरिएको हो। सदस्यहरूले दैनिक मौज्दातमा प्रतिस्पर्धी ब्याजदर प्राप्त गर्दै कार्यालय समयमा जुनसुकै बेला रकम जम्मा र झिक्न सक्नुहुन्छ।',
                'icon' => 'wallet',
                'benefits_en' => [
                    'Unlimited deposits and withdrawals during business hours',
                    'Interest calculated on daily minimum balance',
                    'Free passbook and instant SMS notification on transactions',
                    'Eligible to apply for loan facilities against savings'
                ],
                'benefits_np' => [
                    'कार्यालय समयभित्र असीमित रकम जम्मा तथा भुक्तानी सुविधा',
                    'दैनिक न्यूनतम मौज्दातमा ब्याज गणना',
                    'निःशुल्क पासबुक तथा एसएमएस सूचना सेवा',
                    'बचत धितोमा सहज ऋण सुविधा प्राप्त गर्न योग्य'
                ],
                'eligibility_en' => 'Open to all registered members of Maharaj Bachat with an active cooperative membership.',
                'eligibility_np' => 'महाराज बचत तथा ऋण सहकारी संस्थाका सबै सक्रिय सदस्यहरूका लागि खुला।',
                'required_documents_en' => [
                    'Cooperative Membership Certificate / Member ID',
                    'Copy of Nepali Citizenship Certificate',
                    'Two recent passport-sized photographs',
                    'Nominee identification and relationship details'
                ],
                'required_documents_np' => [
                    'सहकारी सदस्यता प्रमाणपत्र वा सदस्य परिचयपत्र',
                    'नेपाली नागरिकताको प्रमाणपत्रको प्रतिलिपि',
                    'हालसालै खिचिएको २ प्रति पासपोर्ट साइजको फोटो',
                    'हकवालाको परिचय तथा नाता प्रमाणित विवरण'
                ],
                'purpose_en' => 'Everyday liquidity and financial security for individuals and families.',
                'purpose_np' => 'व्यक्ति तथा परिवारको दैनिक तरलता र आर्थिक सुरक्षा।',
                'process_en' => 'Visit any counter with your membership passbook and fill out the regular savings account opening slip.',
                'process_np' => 'सदस्यता पासबुकसहित संस्थाको काउन्टरमा सम्पर्क गरी साधारण बचत खाता फारम भर्नुहोस्।',
                'display_order' => 1,
            ],
            [
                'slug' => 'monthly-savings',
                'category' => 'savings',
                'title_en' => 'Monthly Savings',
                'title_np' => 'मासिक बचत योजना',
                'short_description_en' => 'Disciplined recurring deposit scheme with predetermined monthly contributions and attractive accumulated returns.',
                'short_description_np' => 'निश्चित मासिक रकम नियमित रूपमा जम्मा गरी आकर्षक प्रतिफल प्राप्त गर्न सकिने अनुशासित क्रमिक बचत योजना।',
                'full_description_en' => 'The Monthly Savings scheme fosters disciplined financial planning. Members commit to saving a fixed amount every month for a chosen term (1 to 5 years). Interest is compounded periodically, providing a substantial financial lump sum upon maturity for family milestones or planned investments.',
                'full_description_np' => 'मासिक बचत योजना सदस्यहरूलाई नियमित रूपमा निश्चित रकम बचत गर्न प्रेरित गर्ने योजना हो। १ देखि ५ वर्षसम्मको अवधि निर्धारण गरी मासिक रूपमा नियमित रकम जम्मा गर्दा चक्रवर्ती ब्याजसहित एकमुष्ट रकम प्राप्त हुन्छ।',
                'icon' => 'calendar-check',
                'benefits_en' => [
                    'Flexible monthly contribution amounts starting from NPR 500',
                    'Higher interest yield than regular savings',
                    'Maturity bonus upon regular completion of installment cycle',
                    'Emergency loan facility available up to 90% of accumulated balance'
                ],
                'benefits_np' => [
                    'मासिक न्यूनतम रु. ५०० बाट सुरु गर्न सकिने लचिलो योजना',
                    'साधारण बचतभन्दा उच्च ब्याजदर प्रतिफल',
                    'नियमित किस्ता भुक्तानी पूरा गरेपछि बोनस सुविधा',
                    'जम्मा भएको कुल रकमको ९०% सम्म आकस्मिक ऋण सुविधा'
                ],
                'eligibility_en' => 'All verified cooperative members seeking structured periodic savings.',
                'eligibility_np' => 'नियमित र योजनाबद्ध बचत गर्न चाहने सबै प्रमाणित सदस्यहरू।',
                'required_documents_en' => [
                    'Active membership passbook',
                    'Citizenship certificate copy',
                    'Monthly savings mandate agreement'
                ],
                'required_documents_np' => [
                    'सक्रिय सदस्यता पासबुक',
                    'नागरिकता प्रमाणपत्रको प्रतिलिपि',
                    'मासिक बचत सम्झौता फारम'
                ],
                'purpose_en' => 'Goal-based wealth accumulation for planned future expenditures.',
                'purpose_np' => 'भविष्यका योजनाबद्ध खर्च तथा पुँजी निर्माणका लागि लक्ष्य-केन्द्रित बचत।',
                'process_en' => 'Select a monthly target amount and tenor, complete the agreement form, and set up your monthly counter or automated deposit.',
                'process_np' => 'मासिक रकम र अवधि छनोट गरी सम्झौता फारम भर्नुहोस् र नियमित किस्ता जम्मा सुरु गर्नुहोस्।',
                'display_order' => 2,
            ],
            [
                'slug' => 'children-savings',
                'category' => 'savings',
                'title_en' => "Children's Savings",
                'title_np' => 'बाल बचत योजना',
                'short_description_en' => 'Special long-term savings plan dedicated to securing higher education and future milestones for children.',
                'short_description_np' => 'बालबालिकाको उच्च शिक्षा, स्वास्थ्य र उज्ज्वल भविष्य सुनिश्चित गर्न अभिभावकद्वारा सञ्चालन गरिने विशेष बचत।',
                'full_description_en' => 'Our Children\'s Savings account empowers parents and guardians to build a secure financial reserve for their minor children (up to 18 years). With special bonus interest and opening gifts, this plan instills early financial literacy while accumulating funds for quality education.',
                'full_description_np' => 'बाल बचत योजना १८ वर्ष मुनिका बालबालिकाको नाममा अभिभावकले सञ्चालन गर्न सक्ने योजना हो। यसले बालबालिकामा बचतको संस्कार विकास गर्नुका साथै उनीहरूको उच्च शिक्षा तथा भविष्यका आवश्यकताका लागि भरपर्दो आर्थिक आधार तयार गर्दछ।',
                'icon' => 'baby',
                'benefits_en' => [
                    'Special attractive interest bonus for minors',
                    'Free educational gift upon opening account',
                    'Operated conveniently by parents or legal guardians',
                    'Maturity transition to regular member account upon reaching 18 years'
                ],
                'benefits_np' => [
                    'बालबालिकाका लागि विशेष थप ब्याजदर सुविधा',
                    'खाता खोल्दा शैक्षिक उपहार तथा प्रोत्साहन',
                    'अभिभावकद्वारा सहज रूपमा खाता सञ्चालन',
                    '१८ वर्ष पूरा भएपछि स्वतः साधारण सदस्य खातामा रूपान्तरण'
                ],
                'eligibility_en' => 'Minor child under 18 years whose parent or legal guardian is a registered member.',
                'eligibility_np' => 'सहकारीका सदस्य अभिभावकका १८ वर्ष मुनिका बालबालिकाहरू।',
                'required_documents_en' => [
                    'Child\'s official Birth Registration Certificate',
                    'Guardian\'s cooperative membership ID and citizenship copy',
                    'Photographs of both child and guardian'
                ],
                'required_documents_np' => [
                    'बालबालिकाको जन्मदर्ता प्रमाणपत्रको प्रतिलिपि',
                    'अभिभावकको सहकारी परिचयपत्र तथा नागरिकताको प्रतिलिपि',
                    'बालबालिका तथा अभिभावकको फोटो'
                ],
                'purpose_en' => 'Higher education, skills development, and long-term security for children.',
                'purpose_np' => 'बालबालिकाको उच्च शिक्षा, सीप विकास र दीर्घकालीन भविष्य सुरक्षा।',
                'process_en' => 'Guardian submits child\'s birth certificate and guardian credentials to open the account.',
                'process_np' => 'अभिभावकले बालबालिकाको जन्मदर्ता प्रमाणपत्र पेस गरी काउन्टरबाट सहजै खाता खोल्न सक्नुहुन्छ।',
                'display_order' => 3,
            ],
            [
                'slug' => 'special-savings',
                'category' => 'savings',
                'title_en' => 'Special Savings',
                'title_np' => 'विशेष बचत योजना',
                'short_description_en' => 'Tailored welfare and festive savings schemes tailored for festivals, senior citizens, and community groups.',
                'short_description_np' => 'चाडपर्व, ज्येष्ठ नागरिक तथा समुदायका विशेष आवश्यकतालाई लक्षित गरी तयार पारिएको कल्याणकारी बचत योजना।',
                'full_description_en' => 'Special Savings covers customized financial products such as Festive Savings (Dashain-Tihar), Senior Citizen Welfare Savings, and Group Welfare Funds. These accounts cater to seasonal requirements with timely pre-festival maturity and dedicated member welfare services.',
                'full_description_np' => 'विशेष बचत योजनाअन्तर्गत चाडपर्व बचत (दशैं-तिहार), ज्येष्ठ नागरिक सम्मान बचत तथा समूह कल्याणकारी बचत समावेश छन्। यसले सदस्यहरूलाई चाडपर्वमा आर्थिक दबाब पर्न नदिन र ज्येष्ठ नागरिकहरूलाई थप सेवा तथा सुरक्षा दिन मद्दत गर्दछ।',
                'icon' => 'sparkles',
                'benefits_en' => [
                    'Pre-festival automated maturity payout for Dashain & Tihar',
                    'Extra 0.5% interest consideration for senior citizens',
                    'Priority counter service and doorstep assistance for elderly members',
                    'Direct linkage to cooperative social security funds'
                ],
                'benefits_np' => [
                    'दशैं-तिहार अगावै एकमुष्ट रकम तथा ब्याज भुक्तानी',
                    'ज्येष्ठ नागरिकहरूका लागि थप ०.५% सम्म विशेष ब्याज सुविधा',
                    'काउन्टरमा प्राथमिकता सेवा तथा ज्येष्ठ सदस्यहरूका लागि सहजीकरण',
                    'सहकारी सामाजिक सुरक्षा कोषसँग आबद्धता'
                ],
                'eligibility_en' => 'All cooperative members, women groups, and senior citizen members.',
                'eligibility_np' => 'सहकारीका सम्पूर्ण सदस्यहरू, महिला समूह तथा ज्येष्ठ नागरिक सदस्यहरू।',
                'required_documents_en' => [
                    'Cooperative membership card',
                    'Citizenship or Senior Citizen identity card (for senior scheme)'
                ],
                'required_documents_np' => [
                    'सहकारी सदस्यता परिचयपत्र',
                    'नागरिकता वा ज्येष्ठ नागरिक परिचयपत्र'
                ],
                'purpose_en' => 'Festival preparedness, seasonal farming expenditure, and senior citizen welfare.',
                'purpose_np' => 'चाडपर्व व्यवस्थापन, मौसमी कृषि खर्च तथा ज्येष्ठ नागरिक हित।',
                'process_en' => 'Choose your desired special scheme at the branch counter and complete the quick enrollment form.',
                'process_np' => 'संस्थाको शाखामा गई आफ्नो आवश्यकता अनुसारको विशेष योजना छनोट गरी खाता सुरु गर्नुहोस्।',
                'display_order' => 4,
            ],
            [
                'slug' => 'fixed-deposit',
                'category' => 'savings',
                'title_en' => 'Fixed Deposit',
                'title_np' => 'मुद्दती बचत (आवधिक बचत)',
                'short_description_en' => 'Highest guaranteed interest yields for fixed terms ranging from 3 months up to 5 years.',
                'short_description_np' => '३ महिनादेखि ५ वर्षसम्मको निश्चित अवधिका लागि अधिकतम तथा सुनिश्चित ब्याजदर प्रदान गर्ने मुद्दती बचत।',
                'full_description_en' => 'Fixed Deposit (Term Savings) is Maharaj Bachat’s premier capital preservation product. Members deposit a lump sum for a contracted term with guaranteed high returns. Interest can be paid monthly, quarterly, or accumulated upon maturity. Members also enjoy loan facilities up to 90% against their deposit certificate.',
                'full_description_np' => 'मुद्दती बचत सदस्यहरूको पुँजी सुरक्षित राख्दै अधिकतम प्रतिफल प्राप्त गर्ने उत्कृष्ट योजना हो। ३ महिना, ६ महिना, १ वर्षदेखि ५ वर्षसम्मको अवधि छनोट गर्न सकिन्छ। ब्याज रकम मासिक, त्रैमासिक वा अवधि समाप्तिमा भुक्तानी लिन सकिन्छ र प्रमाणपत्र धितोमा ९०% सम्म कर्जा सुविधा समेत उपलब्ध छ।',
                'icon' => 'shield-check',
                'benefits_en' => [
                    'Guaranteed institutional high-yield interest rate',
                    'Flexible tenure options from 3 months to 5 years',
                    'Periodic interest disbursement directly to regular account',
                    'Fast loan facility up to 90% without breaking the deposit'
                ],
                'benefits_np' => [
                    'संस्थाद्वारा स्वीकृत सुरक्षित तथा अधिकतम ब्याजदर',
                    '३ महिनादेखि ५ वर्षसम्मका लचिलो समयावधि विकल्पहरू',
                    'ब्याज रकम नियमित बचत खातामा मासिक वा त्रैमासिक जम्मा हुने सुविधा',
                    'मुद्दती नतोडीकनै प्रमाणपत्र धितोमा ९०% सम्म तत्काल कर्जा'
                ],
                'eligibility_en' => 'Any active member with verified KYC and minimum deposit requirements.',
                'eligibility_np' => 'केवाईसी अद्यावधिक भएका संस्थाका सम्पूर्ण सक्रिय सदस्यहरू।',
                'required_documents_en' => [
                    'Member ID and citizenship certificate',
                    'Fixed Deposit application form',
                    'Nominee declaration with identification'
                ],
                'required_documents_np' => [
                    'सदस्य परिचयपत्र तथा नागरिकता प्रमाणपत्र',
                    'मुद्दती बचत आवेदन फारम',
                    'हकवालाको विवरण तथा पहिचान'
                ],
                'purpose_en' => 'Secured high-yield capital investment and retirement savings.',
                'purpose_np' => 'सुरक्षित पुँजी वृद्धि, अवकाश कोष तथा दीर्घकालीन वित्तीय स्थायित्व।',
                'process_en' => 'Deposit funds at the counter, select term and interest payout frequency, and receive your official Fixed Deposit Certificate.',
                'process_np' => 'काउन्टरमा रकम जम्मा गरी अवधि र ब्याज भुक्तानी तालिका छनोट गर्नुहोस् र मुद्दती प्रमाणपत्र प्राप्त गर्नुहोस्।',
                'display_order' => 5,
            ],

            // LOANS
            [
                'slug' => 'personal-loan',
                'category' => 'loan',
                'title_en' => 'Personal & Household Loan',
                'title_np' => 'व्यक्तिगत तथा घरायसी कर्जा',
                'short_description_en' => 'Reliable credit support for home improvement, family ceremonies, health treatment, and personal needs.',
                'short_description_np' => 'घर मर्मत, पारिवारिक संस्कार, उपचार तथा व्यक्तिगत आवश्यकताका लागि सहज र भरपर्दो कर्जा सुविधा।',
                'full_description_en' => 'The Personal Loan scheme provides quick, hassle-free credit to members for meeting unavoidable personal or household expenses. With transparent reducing interest calculations, zero hidden penalties, and structured EMI repayment options, we help you manage family obligations with dignity.',
                'full_description_np' => 'व्यक्तिगत कर्जा सदस्यहरूलाई घरायसी तथा व्यक्तिगत आवश्यकताहरू पूरा गर्न सहज रूपमा प्रदान गरिने कर्जा हो। पारदर्शी ब्याजदर, सहज किस्ताबन्दी तालिका र द्रुत प्रक्रियामार्फत सदस्यहरूलाई आर्थिक राहत पुर्‍याइन्छ।',
                'icon' => 'home',
                'benefits_en' => [
                    'Fast 48-hour approval turnaround upon document verification',
                    'Flexible repayment schedules tailored to monthly income',
                    'No prepayment penalties for early settlement',
                    'Transparent reducing interest rate without hidden fees'
                ],
                'benefits_np' => [
                    'आवश्यक कागजात पेस भएपछि २४ देखि ४८ घण्टाभित्र द्रुत स्वीकृति',
                    'मासिक आम्दानी अनुसारको सहज किस्ता भुक्तानी तालिका',
                    'अग्रिम भुक्तानी गर्दा कुनै अतिरिक्त जरिवाना नलाग्ने',
                    'कुनै लुकेको शुल्क बिना पारदर्शी घट्दो ब्याजदर'
                ],
                'eligibility_en' => 'Active membership for minimum 6 months with verified regular income or savings history.',
                'eligibility_np' => 'न्यूनतम ६ महिनादेखि सदस्य भई नियमित बचत वा प्रमाणित आम्दानी स्रोत भएका सदस्यहरू।',
                'required_documents_en' => [
                    'Citizenship copies of applicant and guarantor',
                    'Income verification or business PAN/registration',
                    'Proof of collateral (land/building ownership Lalpurja and tax clearance)',
                    'Passport-sized photographs of borrower and guarantor'
                ],
                'required_documents_np' => [
                    'ऋणी र जमानीकर्ताको नागरिकताको प्रतिलिपि',
                    'आम्दानी प्रमाणित गर्ने कागजात वा व्यवसाय दर्ता',
                    'धितो रहने जग्गा/घरको लालपुर्जा र तिरो तिरेको रसिद',
                    'ऋणी र जमानीकर्ताको पासपोर्ट साइजको फोटो'
                ],
                'purpose_en' => 'Household renovations, medical treatments, social celebrations, and debt consolidation.',
                'purpose_np' => 'घर मर्मत, स्वास्थ्य उपचार, सामाजिक कार्य तथा पारिवारिक आवश्यकता।',
                'process_en' => 'Submit filled loan application form with collateral documents; evaluation completed in 24-48 hours followed by disbursement.',
                'process_np' => 'कर्जा फारम भरी आवश्यक धितो कागजात पेस गर्नुहोस्; ऋण उपसमितिको मूल्याङ्कन पश्चात् रकम खातामा निकासा हुनेछ।',
                'display_order' => 10,
            ],
            [
                'slug' => 'business-loan',
                'category' => 'loan',
                'title_en' => 'Business & Enterprise Loan',
                'title_np' => 'व्यवसाय तथा उद्यम कर्जा',
                'short_description_en' => 'Working capital and capital expenditure credit for local shops, trade, transport, and cottage enterprises.',
                'short_description_np' => 'स्थानीय व्यापार, साना तथा मझौला उद्योग र व्यावसायिक विस्तारका लागि कार्यशील पुँजी तथा लगानी कर्जा।',
                'full_description_en' => 'Maharaj Bachat supports local entrepreneurship with dedicated Business Loans. Whether you need inventory working capital, machinery acquisition, retail store expansion, or trade refinancing, our cooperative partners with your business journey.',
                'full_description_np' => 'स्थानीय उद्यमशीलता र रोजगारी प्रवर्द्धन गर्न संस्थाले व्यवसाय कर्जा प्रवाह गर्दछ। पसल, व्यवसाय विस्तार, यन्त्र-उपकरण खरिद तथा कच्चा पदार्थ जोहो गर्नका लागि सरल प्रक्रियामा कर्जा उपलब्ध गराइन्छ।',
                'icon' => 'briefcase',
                'benefits_en' => [
                    'Higher credit ceiling based on business cash flow',
                    'Custom repayment terms aligned with trade revenue cycle',
                    'Revolving working capital and term loan structures available',
                    'Advisory support from experienced cooperative finance officers'
                ],
                'benefits_np' => [
                    'व्यवसायको नगद प्रवाहको आधारमा आवश्यकता अनुसारको कर्जा सीमा',
                    'व्यापार चक्र सुहाउँदो लचिलो भुक्तानी तालिका',
                    'कार्यशील पुँजी तथा आवधिक कर्जा दुवै विकल्प उपलब्ध',
                    'सहकारीका अनुभवी अधिकृतहरूबाट वित्तीय परामर्श सहयोग'
                ],
                'eligibility_en' => 'Registered business enterprise owned by cooperative member operating for at least 1 year.',
                'eligibility_np' => 'सदस्यद्वारा सञ्चालित र सम्बन्धित निकायमा दर्ता भई कम्तीमा १ वर्षदेखि चालु रहेको व्यवसाय।',
                'required_documents_en' => [
                    'Business Registration Certificate and PAN/VAT registration',
                    'Past 6 months bank statement or audited financial statement',
                    'Collateral ownership documents and municipal house completion certificate',
                    'Citizenship copies and tax clearance receipt'
                ],
                'required_documents_np' => [
                    'व्यवसाय दर्ता प्रमाणपत्र तथा प्यान/भ्याट प्रमाणपत्र',
                    'पछिल्लो ६ महिनाको बैंक स्टेटमेन्ट वा कारोबार विवरण',
                    'धितो रहने सम्पत्तिको लालपुर्जा, नक्सा र कर चुक्ता रसिद',
                    'सञ्चालक तथा जमानीकर्ताको नागरिकता'
                ],
                'purpose_en' => 'Store inventory, purchase of equipment, working capital, and enterprise expansion.',
                'purpose_np' => 'पसलको स्टक खरिद, मेसिनरी, कार्यशील पुँजी तथा व्यापार विस्तार।',
                'process_en' => 'Submit business credentials and collateral file; on-site business inspection conducted by field officer before committee approval.',
                'process_np' => 'कागजात पेस भएपछि स्थलगत निरीक्षण गरी ऋण उपसमितिको सिफारिसमा कर्जा स्वीकृत गरिन्छ।',
                'display_order' => 11,
            ],
            [
                'slug' => 'agricultural-loan',
                'category' => 'loan',
                'title_en' => 'Agricultural & Livestock Loan',
                'title_np' => 'कृषि तथा पशुपालन कर्जा',
                'short_description_en' => 'Specialized rural financing for seeds, fertilizers, farm machinery, dairy farming, poultry, and fisheries.',
                'short_description_np' => 'बिउबिजन, मलखाद, कृषि औजार, गाईभैंसी पालन, कुखुरा तथा माछापालनका लागि सहुलियतपूर्ण कृषि कर्जा।',
                'full_description_en' => 'Designed specifically for our farming members, the Agricultural & Livestock Loan provides financial support aligned with crop cycles and livestock breeding seasons. We help farmers modernize irrigation, purchase quality hybrid seeds, expand poultry/dairy farms, and install greenhouse structures.',
                'full_description_np' => 'कृषि पेशामा आबद्ध सदस्यहरूलाई उत्पादन वृद्धि र आधुनिकीकरणका लागि बाली तथा पशुपालन चक्र अनुसार यो कर्जा प्रदान गरिन्छ। उन्नत बिउबिजन, कृषि यन्त्र, सिँचाइ, टनेल खेती तथा डेरी फार्म विस्तारमा यसले टेवा पुर्‍याउँछ।',
                'icon' => 'sprout',
                'benefits_en' => [
                    'Harvest-linked seasonal or semi-annual repayment options',
                    'Subsidized institutional interest rates for farming members',
                    'Livestock insurance assistance through partner providers',
                    'Quick processing during peak sowing and harvesting windows'
                ],
                'benefits_np' => [
                    'बाली भित्र्याउने समयसँग मेल खाने गरी किस्ता भुक्तानी तालिका',
                    'कृषक सदस्यहरूका लागि सहुलियतपूर्ण ब्याजदर व्यवस्था',
                    'पशुपक्षी बीमा सहजीकरण सुविधा',
                    'खेतीपातीको मौसम अनुसार प्राथमिकताका साथ द्रुत कर्जा प्रवाह'
                ],
                'eligibility_en' => 'Member farmers with land ownership or registered tenancy agreements engaged in farming or livestock.',
                'eligibility_np' => 'कृषि वा पशुपालन व्यवसायमा संलग्न तथा जग्गा धनी वा सम्झौता करार भएका कृषक सदस्यहरू।',
                'required_documents_en' => [
                    'Farm land Lalpurja or registered land lease contract',
                    'Recommendation from local agricultural ward / cooperative committee',
                    'Applicant and guarantor citizenship copies',
                    'Livestock vaccination / inspection record if applicable'
                ],
                'required_documents_np' => [
                    'कृषि जग्गाको लालपुर्जा वा जग्गा भाडा सम्झौता पत्र',
                    'वडा वा स्थानीय कृषि प्राविधिकको सिफारिस',
                    'ऋणी र जमानीकर्ताको नागरिकता प्रमाणपत्र',
                    'पशुपालन भए खोप तथा स्वास्थ्य प्रमाणित विवरण'
                ],
                'purpose_en' => 'Seeds, fertilizers, irrigation motors, dairy cattle, poultry shed, and farm mechanization.',
                'purpose_np' => 'बिउबिजन, मलखाद, सिँचाइ उपकरण, उन्नत पशु खरिद तथा फार्म व्यवस्थापन।',
                'process_en' => 'Submit farm plan and land details; inspection done by cooperative loan team with swift disbursement.',
                'process_np' => 'कृषि कार्ययोजना र जग्गा विवरण पेस गरी निरीक्षण पश्चात् कर्जा उपलब्ध गराइन्छ।',
                'display_order' => 12,
            ],
            [
                'slug' => 'educational-loan',
                'category' => 'loan',
                'title_en' => 'Educational Loan',
                'title_np' => 'शैक्षिक कर्जा',
                'short_description_en' => 'Affordable financing to cover university tuition, technical training, and higher education in Nepal and abroad.',
                'short_description_np' => 'स्वदेश तथा विदेशमा उच्च शिक्षा, प्राविधिक तालिम र कलेज भर्ना शुल्कका लागि भरपर्दो शैक्षिक कर्जा।',
                'full_description_en' => 'We believe quality education is the greatest investment for families. Educational Loans assist members and their children in funding tuition fees, examination costs, living expenses, and study abroad preparation with grace periods throughout the study program.',
                'full_description_np' => 'गुणस्तरीय शिक्षाले परिवार र समुदायको भविष्य उज्यालो बनाउँछ। सदस्यका छोराछोरीको उच्च शिक्षा, इन्जिनियरिङ, चिकित्सा, व्यवस्थापन वा वैदेशिक अध्ययनका लागि आवश्यक कलेज शुल्क र खर्च जोहो गर्न यो कर्जा प्रदान गरिन्छ।',
                'icon' => 'graduation-cap',
                'benefits_en' => [
                    'Moratorium period on principal repayment during duration of studies',
                    'Direct payment to accredited colleges and universities upon request',
                    'Covers admission, tuition, books, and essential living costs',
                    'Student loan counseling and fair reducing interest rates'
                ],
                'benefits_np' => [
                    'अध्ययन अवधिभर किस्ता तिर्न सहज ग्रेस पिरियड (सहुलियत समय)',
                    'आवश्यकता अनुसार शिक्षण संस्थाको नाममा सिधै शुल्क भुक्तानी',
                    'भर्ना शुल्क, मासिक शुल्क, पुस्तक तथा बसोबास खर्च समेत समावेश',
                    'विद्यार्थी तथा अभिभावकका लागि वित्तीय परामर्श सेवा'
                ],
                'eligibility_en' => 'Member parents or guardians on behalf of admitted students in recognized educational institutions.',
                'eligibility_np' => 'मान्यता प्राप्त शिक्षण संस्थामा भर्ना भएका विद्यार्थीका सदस्य अभिभावकहरू।',
                'required_documents_en' => [
                    'College admission letter and official fee breakdown',
                    'Academic certificates and transcripts of the student',
                    'Guardian\'s membership ID, citizenship, and income proof',
                    'Collateral documents as per cooperative policy'
                ],
                'required_documents_np' => [
                    'कलेजको भर्ना पत्र तथा शुल्क विवरण (Fee Structure)',
                    'विद्यार्थीको शैक्षिक प्रमाणपत्र र चारित्रिक प्रमाणपत्र',
                    'अभिभावकको नागरिकता, सदस्यता र आम्दानी विवरण',
                    'धितो सम्बन्धी आवश्यक कागजात'
                ],
                'purpose_en' => 'Tuition fees, lab fees, living accommodation, and educational materials.',
                'purpose_np' => 'कलेज भर्ना शुल्क, होस्टेल तथा शैक्षिक सामग्री खर्च।',
                'process_en' => 'Submit student admission package along with family guarantee and collateral for expedited committee review.',
                'process_np' => 'कलेजको भर्ना कागजात र अभिभावकको धितो कागजात पेस गरी प्रक्रिया अगाडि बढाइन्छ।',
                'display_order' => 13,
            ],
            [
                'slug' => 'emergency-loan',
                'category' => 'loan',
                'title_en' => 'Emergency Relief Loan',
                'title_np' => 'आपतकालीन तथा राहत कर्जा',
                'short_description_en' => 'Rapid same-day release credit to assist members facing unexpected medical emergencies or urgent crises.',
                'short_description_np' => 'अचानक आइपर्ने स्वास्थ्य उपचार वा आकस्मिक संकटको सामना गर्न २४ घण्टाभित्रै उपलब्ध हुने तत्काल राहत कर्जा।',
                'full_description_en' => 'True cooperative values shine in moments of urgent need. The Emergency Relief Loan is designed with minimum red tape and expedited evaluation so members can access immediate funds for emergency hospitalizations, disaster repair, or sudden family crises.',
                'full_description_np' => 'सहकारी अप्ठ्यारो पर्दाको सच्चा साथी हो। अस्पताल भर्ना, आकस्मिक शल्यक्रिया वा प्राकृतिक विपद् जस्ता तत्काल रकम आवश्यक पर्ने अवस्थामा यो कर्जा २४ घण्टाभित्रै प्रक्रिया पूरा गरी उपलब्ध गराइन्छ।',
                'icon' => 'heart-pulse',
                'benefits_en' => [
                    'Fast expedited approval within 12 to 24 hours',
                    'Simplified documentation and immediate counter disbursement',
                    'Reasonable short-term repayment terms designed for recovery',
                    'Available against member savings deposits or personal guarantee'
                ],
                'benefits_np' => [
                    '१२ देखि २४ घण्टाभित्रै द्रुत स्वीकृति र तत्काल भुक्तानी',
                    'अत्यन्त सरल कागजी प्रक्रिया र झन्झटरहित सेवा',
                    'सहज रूपमा कर्जा फर्काउन सकिने लचिलो समय',
                    'सदस्यको मौज्दात बचत वा व्यक्तिगत जमानीको आधारमा उपलब्ध'
                ],
                'eligibility_en' => 'Active members experiencing verified urgent personal or medical emergencies.',
                'eligibility_np' => 'आकस्मिक उपचार वा संकटमा परेका संस्थाका सक्रिय सदस्यहरू।',
                'required_documents_en' => [
                    'Hospital admission or medical doctor recommendation / proof of emergency',
                    'Active member passbook and citizenship card',
                    'One verified co-member personal guarantee'
                ],
                'required_documents_np' => [
                    'अस्पतालको भर्ना पुर्जी वा डाक्टरको सिफारिस / आपतकाल प्रमाणित पत्र',
                    'सक्रिय सदस्यता पासबुक तथा नागरिकता',
                    'सहकारीका अर्का एक सदस्यको जमानी'
                ],
                'purpose_en' => 'Emergency hospitalization, surgical treatment, critical medicines, and immediate disaster relief.',
                'purpose_np' => 'आकस्मिक उपचार, अस्पताल शुल्क, औषधि तथा विपद् राहत।',
                'process_en' => 'Contact the branch manager with emergency documentation for priority immediate counter evaluation.',
                'process_np' => 'अस्पताल कागजातसहित शाखा प्रमुखलाई सम्पर्क गरी प्राथमिकताका साथ तत्काल सेवा लिनुहोस्।',
                'display_order' => 14,
            ],

            // MEMBER SUPPORT SERVICES
            [
                'slug' => 'mini-banking-services',
                'category' => 'member_support',
                'title_en' => 'Mini-Banking & Counter Services',
                'title_np' => 'लघु बैंकिङ तथा काउन्टर सेवा',
                'short_description_en' => 'Instant cash deposit, withdrawal, statement printing, and digital member account access across service centers.',
                'short_description_np' => 'शाखा काउन्टरबाट तत्काल रकम जम्मा, भुक्तानी, स्टेटमेन्ट र डिजिटल खाता व्यवस्थापन सेवा।',
                'full_description_en' => 'Our service counters provide full mini-banking facilities for hassle-free daily transactions. With computerized accounting and instant SMS alerts, members manage their money securely with complete transparency.',
                'full_description_np' => 'संस्थाका सेवा काउन्टरहरूबाट कम्प्युटराइज्ड प्रणालीमार्फत छिटो र भरपर्दो लघु बैंकिङ सेवा प्रवाह गरिन्छ। हरेक कारोबारको तत्काल एसएमएस जानकारी र खाता विवरण सदस्यलाई उपलब्ध हुन्छ।',
                'icon' => 'landmark',
                'benefits_en' => [
                    'Fast counter service with minimal waiting time',
                    'Instant SMS alert for every debit and credit',
                    'Free passbook and account statement on demand',
                    'Digital QR payment integration at branch desks'
                ],
                'benefits_np' => [
                    'छिटो छरितो काउन्टर सेवा र झन्झटरहित कारोबार',
                    'हरेक कारोबारमा तत्काल एसएमएस सूचना',
                    'माग अनुसार निःशुल्क पासबुक तथा स्टेटमेन्ट',
                    'डिजिटल क्युआर भुक्तानी सुविधा'
                ],
                'eligibility_en' => 'All active cooperative members.',
                'eligibility_np' => 'सबै सक्रिय सहकारी सदस्यहरू।',
                'display_order' => 20,
            ],
            [
                'slug' => 'remittance-utility-services',
                'category' => 'member_support',
                'title_en' => 'Remittance & Utility Payments',
                'title_np' => 'विप्रेषण (रेमिट्यान्स) तथा महसुल भुक्तानी',
                'short_description_en' => 'Convenient domestic and international money transfer, electricity, water, and telecom bill settlement.',
                'short_description_np' => 'स्वदेशी तथा विदेशी रेमिट्यान्स भुक्तानी, बिजुली, खानेपानी र टेलिफोन महसुल बुझाउने सहज सुविधा।',
                'full_description_en' => 'Members can collect remittances from family working abroad (Gulf, Korea, Japan, Europe, Australia) directly from our cooperative counters. We also offer one-stop payment for electricity, drinking water, and telecom bills.',
                'full_description_np' => 'वैदेशिक रोजगारीमा रहेका परिवारका सदस्यहरूले पठाएको रेमिट्यान्स संस्थाको काउन्टरबाट सिधै सुरक्षित भुक्तानी लिन सकिन्छ। साथै विद्युत, खानेपानी र टेलिफोनको महसुल समेत काउन्टरबाटै बुझाउन सकिन्छ।',
                'icon' => 'send',
                'benefits_en' => [
                    'Payout from major domestic and international remittance providers',
                    'Direct deposit of remittances into member savings accounts',
                    'One-stop utility bill payment without traveling to government offices',
                    'Secure verification with zero extra hidden surcharges'
                ],
                'benefits_np' => [
                    'प्रमुख स्वदेशी तथा अन्तर्राष्ट्रिय रेमिट्यान्स कम्पनीहरूमार्फत भुक्तानी',
                    'रेमिट्यान्स रकम सिधै बचत खातामा जम्मा गर्ने सुविधा',
                    'बिजुली, पानीको महसुल एउटै स्थानबाट बुझाउन सकिने',
                    'सुरक्षित र भरपर्दो भुक्तानी प्रणाली'
                ],
                'eligibility_en' => 'Open to members and their immediate family members.',
                'eligibility_np' => 'सदस्य तथा उहाँहरूका परिवारका सदस्यहरू।',
                'display_order' => 21,
            ],
            [
                'slug' => 'member-welfare-guidance',
                'category' => 'member_support',
                'title_en' => 'Member Welfare & Financial Guidance',
                'title_np' => 'सदस्य कल्याण तथा वित्तीय परामर्श',
                'short_description_en' => 'Maternity allowances, bereavement relief, healthcare assistance, and free cooperative business mentoring.',
                'short_description_np' => 'सुत्केरी राहत, काजकिरिया राहत, स्वास्थ्य उपचार सहायता र निःशुल्क वित्तीय परामर्श सेवा।',
                'full_description_en' => 'Cooperative principles are founded on mutual care. Maharaj Bachat provides non-refundable welfare relief grants during major life events including child birth, critical illness, and family bereavement, alongside regular financial literacy workshops.',
                'full_description_np' => 'सहकारी सदस्यहरूको सुख-दुःखको साथी हो। संस्थाले सदस्य राहत कोषमार्फत सुत्केरी पोषण भत्ता, काजकिरिया खर्च, गम्भीर बिरामी उपचार सहायता र नियमित वित्तीय साक्षरता तथा उद्यमशीलता तालिम प्रदान गर्दै आएको छ।',
                'icon' => 'users',
                'benefits_en' => [
                    'Institutional maternity welfare allowance upon childbirth',
                    'Bereavement relief grant paid immediately to grieving families',
                    'Periodic health screening camps and doctor consultations',
                    'Free business management and cooperative governance training'
                ],
                'benefits_np' => [
                    'सुत्केरी हुँदा संस्थाद्वारा पोषण तथा सम्मान भत्ता',
                    'परिवारमा शोक पर्दा तत्काल काजकिरिया राहत रकम',
                    'नियमित निःशुल्क स्वास्थ्य शिविर तथा स्वास्थ्य परीक्षण',
                    'उद्यमशीलता तथा वित्तीय साक्षरता तालिम कार्यक्रमहरू'
                ],
                'eligibility_en' => 'Registered members following cooperative welfare by-laws.',
                'eligibility_np' => 'संस्थाको राहत कोष कार्यविधि अनुसारका नियमित सदस्यहरू।',
                'display_order' => 22,
            ],
        ];

        foreach ($services as $service) {
            Service::query()->updateOrCreate(
                ['slug' => $service['slug']],
                $service
            );
        }
    }
}

