<?php

namespace EMVQR;

/**
 * Class EmvMerchant
 * @package EMVQR
 */
class EmvMerchant {

    /**
     * MODES
     */
    const MODE_GENERATE = 'GENERATE';
    const MODE_DECODE = 'DECODE';

    /**
     * PAYLOAD FORMAT INDICATOR (00)
     */
    const ID_PAYLOAD_FORMAT_INDICATOR = '00';
    const PAYLOAD_FORMAT_INDICATOR_VALUE = '01';

    /**
     * POINT OF INITIATION (01)
     */
    const ID_POINT_OF_INITIATION = '01';
    const POINT_OF_INITIATION_STATIC = '11';
    const POINT_OF_INITIATION_STATIC_VALUE = 'STATIC';
    const POINT_OF_INITIATION_DYNAMIC = '12';
    const POINT_OF_INITIATION_DYNAMIC_VALUE = 'DYNAMIC';

    /**
     * ACCOUNTS (02-51)
     */
    const ID_ACCOUNT_LOWER_BOUNDARY = 2;
    const ID_ACCOUNT_START_INDEX = 26;
    const ID_ACCOUNT_UPPER_BOUNDARY = 51;
    const ID_ORIGINAL_LABEL = 'original_id';

    /**
     * MERCHANT CATEGORY CODE (52)
     */
    const ID_MERCHANT_CATEGORY_CODE = '52';
    const MERCHANT_CATEGORY_CODE_GENERIC = '0000';
    const MERCHANT_CATEGORY_UNKNOWN = 'UNKNOWN';
    /**
     * @var string[] Merchant category codes ISO18245;
     * Src: https://docs.checkout.com/resources/codes/merchant-category-codes
     */
    protected $merchant_category_codes = [
        '0000' => 'Generic',
        '0742' => 'Veterinary services',
        '0763' => 'Agricultural co-operative',
        '0780' => 'Landscaping and horticultural services',
        '1520' => 'General contractors - residential & commercial',
        '1711' => 'Heating, plumbing, and air conditioning contractors',
        '1731' => 'Electrical contractors',
        '1740' => 'Masonry, stonework, tile setting, plastering & insulation contractors',
        '1750' => 'Carpentry contractors',
        '1761' => 'Roofing and siding contractors',
        '1771' => 'Concrete work contractors',
        '1799' => 'Special trade contractors (not elsewhere classified)',
        '2741' => 'Miscellaneous publishing and printing',
        '2791' => 'Typesetting, plate making, and related services',
        '2842' => 'Specialty cleaning, polishing, & sanitation preparations',
        '3357' => 'Hertz',
        '3359' => 'Payless car rental',
        '3366' => 'Budget rent-a-car',
        '3370' => 'Rent-a-wreck',
        '3385' => 'Tropical rent-a-car',
        '3389' => 'Avis rent-a-car',
        '3390' => 'Dollar rent-a-car',
        '3393' => 'National car rental',
        '3395' => 'Thrifty car rental',
        '3398' => 'Econo-car rent-a-car',
        '3501' => 'Holiday Inns',
        '3502' => 'Best Western',
        '3503' => 'Sheraton',
        '3504' => 'Hilton',
        '3506' => 'Golden Tulip',
        '3507' => 'Friendship Inns',
        '3508' => 'Quality International',
        '3509' => 'Marriott',
        '3510' => 'Days Inns',
        '3512' => 'Intercontinental',
        '3515' => 'Rodeway Inns',
        '3516' => 'La Quinta Motor Inns',
        '3517' => 'Americana Hotels',
        '3520' => 'Meridien',
        '3527' => 'Downtowner Passport',
        '3528' => 'Red Lion',
        '3535' => 'Hilton International',
        '3536' => 'AMFAC Hotels',
        '3539' => 'Summerfield Suites Hotels',
        '3542' => 'Royal Hotels',
        '3543' => 'Four Seasons Hotels',
        '3546' => 'Hotel Sierra',
        '3550' => 'Regal 8 Inns',
        '3562' => 'Comfort Hotel International',
        '3565' => 'Relax Inns',
        '3573' => 'Sandman Hotels',
        '3574' => 'Venture Inn',
        '3575' => 'Vagabond Hotels',
        '3579' => 'Hotel Mercure',
        '3588' => 'Helmsley Hotels',
        '3590' => 'Fairmont Hotels Corporation',
        '3591' => 'Sonesta International Hotels',
        '3592' => 'Omni International',
        '3595' => 'Hospitality Inns',
        '3615' => 'Travelodge Motels',
        '3631' => 'Sleep Inn',
        '3637' => 'Ramada Inns',
        '3638' => 'Howard Johnson',
        '3641' => 'Sofitel Hotels',
        '3644' => 'Econo-Travel Motor Hotel',
        '3648' => 'De Vere Hotels',
        '3649' => 'Radisson',
        '3650' => 'Red Roof Inns',
        '3652' => 'Embassy Hotels',
        '3654' => 'Loews Hotels',
        '3660' => 'Knights Inns',
        '3665' => 'Hampton Inns',
        '3681' => 'Adams Mark',
        '3684' => 'Budget Host Inn',
        '3685' => 'Budgetel',
        '3687' => 'Clarion Hotel',
        '3690' => 'Courtyard by Marriott',
        '3692' => 'Doubletree',
        '3693' => 'Drury Inn',
        '3694' => 'Economy Inns of America',
        '3695' => 'Embassy Suites',
        '3699' => 'Midway Motor Lodge',
        '3700' => 'Motel 6',
        '3703' => 'Residence Inns',
        '3704' => 'Royce Hotel',
        '3705' => 'Sandman Inns',
        '3706' => 'Shilo Inns',
        '3707' => 'Shoney’s Inns',
        '3709' => 'Super 8 Motels',
        '3715' => 'Fairfield Inns',
        '3716' => 'Carlton Hotels',
        '3722' => 'Wyndham',
        '3731' => 'Harrah’s Hotels and Casinos',
        '3738' => 'Tropicana Resort and Casino',
        '3742' => 'Club Med',
        '3747' => 'Club Corp / Club Resorts',
        '3748' => 'Wellesley Inns',
        '3750' => 'Crowne Plaza Hotels',
        '3773' => 'The Venetian Resort Hotel Casino',
        '3783' => 'Town and Country Resort',
        '4011' => 'Freight Railways',
        '4111' => 'Local passenger transportation',
        '4112' => 'Passenger railways',
        '4119' => 'Ambulance services',
        '4121' => 'Taxicabs and limousines',
        '4131' => 'Bus lines',
        '4214' => 'Motor freight carriers and trucking',
        '4215' => 'Courier services',
        '4225' => 'Public warehousing and storage',
        '4411' => 'Steamship and cruise lines',
        '4457' => 'Boat rentals and leasing',
        '4468' => 'Marinas, marine service, and supplies',
        '4511' => 'Airlines and air carriers (not elsewhere classified)',
        '4582' => 'Airports, flying fields, and airport terminals',
        '4722' => 'Travel agencies and tour operators',
        '4784' => 'Toll and bridge fees',
        '4789' => 'Transportation services (not elsewhere classified)',
        '4812' => 'Telecommunication equipment and telephone sales',
        '4814' => 'Telecommunication service',
        '4815' => 'Visaphone',
        '4816' => 'Computer network / information services',
        '4821' => 'Telegraph services',
        '4829' => 'Wire Transfer Money Orders (WTMOS)',
        '4899' => 'Cable, satellite and other pay television, and radio services',
        '4900' => 'Utilities - electric, gas, water, sanitary',
        '5013' => 'Motor vehicle supplies and new parts',
        '5021' => 'Office and commercial furniture',
        '5039' => 'Construction materials (not elsewhere classified)',
        '5044' => 'Photographic, photocopy, microfilm equipment and supplies',
        '5045' => 'Computers and computer peripheral equipment and services',
        '5046' => 'Commercial equipment (not elsewhere classified)',
        '5047' => 'Medical, dental, ophthalmic, and hospital equipment and supplies',
        '5051' => 'Metal service centers and offices',
        '5065' => 'Electrical parts and equipment',
        '5072' => 'Hardware, equipment and supplies',
        '5074' => 'Plumbing and heating equipment and supplies',
        '5085' => 'Industrial supplies (not elsewhere classified)',
        '5094' => 'Precious stones, metals, watches, and jewelry',
        '5099' => 'Durable goods (not elsewhere classified)',
        '5111' => 'Stationery, office supplies, printing, and writing paper',
        '5122' => 'Drugs, drug proprietaries, and druggist sundries',
        '5131' => 'Piece goods, notions, and other dry goods',
        '5137' => 'Men’s, women’s, and children’s uniforms, and commercial clothing',
        '5139' => 'Commercial footwear',
        '5169' => 'Chemicals and allied products (not elsewhere classified)',
        '5172' => 'Petroleum and petroleum products',
        '5192' => 'Books, periodicals, and newspapers',
        '5193' => 'Florist supplies, nursery stock, and flowers',
        '5198' => 'Paint, varnishes, and supplies',
        '5199' => 'Nondurable goods (not elsewhere classified)',
        '5200' => 'Home supply warehouse stores',
        '5211' => 'Lumber and building materials stores',
        '5231' => 'Glass, paint, and wallpaper stores',
        '5251' => 'Hardware stores',
        '5261' => 'Nurseries and lawn and garden supply stores',
        '5271' => 'Mobile home dealers',
        '5300' => 'Wholesale clubs',
        '5309' => 'Duty free stores',
        '5310' => 'Discount stores',
        '5311' => 'Department stores',
        '5331' => 'Variety stores',
        '5399' => 'Miscellaneous general merchandise',
        '5411' => 'Grocery stores and supermarkets',
        '5422' => 'Freezer and locker meat provisioners',
        '5441' => 'Candy, nut, and confectionery stores',
        '5451' => 'Dairy products stores',
        '5462' => 'Bakeries',
        '5499' => 'Miscellaneous food stores - convenience stores and specialty markets',
        '5511' => 'Car and truck dealers (new and used) sales, service, repairs, parts, and leasing',
        '5521' => 'Car and truck dealers (used only) sales, service, repairs, parts, and leasing',
        '5531' => 'Auto and home supply stores (no longer valid MCC)',
        '5532' => 'Automotive tire stores',
        '5533' => 'Automotive parts and accessories stores',
        '5541' => 'Service stations',
        '5542' => 'Automated fuel dispensers',
        '5551' => 'Boat dealers',
        '5561' => 'Camper, recreational and utility trailer dealers',
        '5571' => 'Motorcycle shops and dealers',
        '5592' => 'Motor homes dealers',
        '5598' => 'Snowmobile dealers',
        '5599' => 'Miscellaneous automotive, aircraft, and farm equipment dealers (not elsewhere classified)',
        '5611' => 'Men’s and boy’s clothing and accessories stores',
        '5621' => 'Women’s ready-to-wear stores',
        '5631' => 'Women’s accessory and specialty shops',
        '5641' => 'Children’s and infant’s wear stores',
        '5651' => 'Family clothing stores',
        '5655' => 'Sports and riding apparel stores',
        '5661' => 'Shoe stores',
        '5681' => 'Furriers and fur shops',
        '5691' => 'Men’s and women’s clothing stores',
        '5697' => 'Tailors, seamstresses, mending, and alterations',
        '5698' => 'Wig and toupee stores',
        '5699' => 'Miscellaneous apparel and accessory shops',
        '5712' => 'Furniture, home furnishings, and equipment stores, excepting appliances',
        '5713' => 'Floor covering stores',
        '5714' => 'Drapery, window covering, and upholstery store',
        '5718' => 'Fireplace, fireplace screens, and accessories stores',
        '5719' => 'Miscellaneous home furnishing specialty stores',
        '5722' => 'Household appliance stores',
        '5732' => 'Electronics stores',
        '5733' => 'Music stores - musical instruments, pianos, and sheet music',
        '5734' => 'Computer software stores',
        '5735' => 'Record stores',
        '5811' => 'Caterers',
        '5812' => 'Eating places & restaurants',
        '5813' => 'Drinking places - bars, taverns, nightclubs, cocktail lounges, and discotheques',
        '5814' => 'Fast food restaurants',
        '5815' => 'Digital Goods: Books, Movies, Music',
        '5912' => 'Drug stores and pharmacies',
        '5921' => 'Package stores - beer, wine, and liquor',
        '5931' => 'Used merchandise and secondhand stores',
        '5932' => 'Antique shops - sales, repairs, and restoration services',
        '5933' => 'Pawn shops',
        '5935' => 'Wrecking and salvage yards',
        '5937' => 'Antique reproductions',
        '5940' => 'Bicycle shops - sales and service',
        '5941' => 'Sporting goods stores',
        '5942' => 'Book stores',
        '5943' => 'Stationery stores, office and school supply stores',
        '5944' => 'Jewelry stores, watches, clocks, and silverware stores',
        '5945' => 'Hobby, toy, and game shops',
        '5946' => 'Camera and photographic supply stores',
        '5947' => 'Gift, card, novelty and souvenir shops',
        '5948' => 'Luggage and leather goods stores',
        '5949' => 'Sewing needlework, fabric, and piece goods stores',
        '5950' => 'Glassware / crystal stores',
        '5960' => 'Direct marketing - insurance services',
        '5962' => 'Direct marketing - travel-related arrangement services (high risk MCC)',
        '5963' => 'Door-to-door sales',
        '5964' => 'Direct marketing - catalog merchant',
        '5965' => 'Direct marketing - combination catalog and retail merchant',
        '5966' => 'Direct marketing - outbound telemarketing merchant (high risk MCC)',
        '5967' => 'Direct marketing - inbound tele-services merchant (high risk MCC)',
        '5968' => 'Direct marketing - continuity/subscription merch.',
        '5969' => 'Direct marketing - other direct marketers (not elsewhere classified)',
        '5970' => 'Artists supply and craft shops',
        '5971' => 'Art dealers and galleries',
        '5972' => 'Stamp and coin stores',
        '5973' => 'Religious goods stores',
        '5975' => 'Hearing aids - sales, service, and supply',
        '5976' => 'Orthopedic goods - prosthetic devices',
        '5977' => 'Cosmetic stores',
        '5978' => 'Typewriters - sales, rentals, & service',
        '5983' => 'Fuel dealers - fuel oil, wood, coal, liquefied petroleum',
        '5992' => 'Florists',
        '5993' => 'Cigar stores and stands',
        '5994' => 'News dealers and newsstands',
        '5995' => 'Pet shops, pet foods and supplies stores',
        '5996' => 'Swimming pools - sales and service',
        '5997' => 'Electric razor stores',
        '5998' => 'Tent and awning shops',
        '5999' => 'Miscellaneous and specialty retail shops',
        '6010' => 'Financial institutions - manual cash disbursements',
        '6011' => 'Financial institutions - automated cash disbursements',
        '6012' => 'Financial institutions merchandise and services',
        '6051' => 'Non-financial institutions - foreign currency, money orders, and travelers cheques',
        '6211' => 'Security brokers / dealers',
        '6300' => 'Insurance sales, underwriting, and premiums',
        '6513' => 'Real estate agents and managers - rentals',
        '7011' => 'Lodging - hotels, motels, resorts, and central reservation services (not elsewhere classified)',
        '7012' => 'Timeshares',
        '7032' => 'Sporting and recreational camps',
        '7033' => 'Trailer parks and campgrounds',
        '7210' => 'Laundry, cleaning, and garment services',
        '7211' => 'Laundries - family and commercial',
        '7216' => 'Dry cleaners',
        '7217' => 'Carpet and upholstery cleaning',
        '7221' => 'Photographic studios',
        '7230' => 'Beauty and barber shops',
        '7251' => 'Shoe repair shops, shoe shine parlors, and hat cleaning shops',
        '7261' => 'Funeral service and crematories',
        '7273' => 'Dating and escort services',
        '7276' => 'Tax preparation service',
        '7277' => 'Counseling services - debt, marriage, and personal',
        '7278' => 'Buying and shopping services and clubs',
        '7296' => 'Clothing rental - costumes, uniforms, and formal wear',
        '7297' => 'Massage parlors',
        '7298' => 'Health and beauty spas',
        '7299' => 'Miscellaneous personal services (not elsewhere classified)',
        '7311' => 'Advertising services',
        '7321' => 'Consumer credit reporting agencies',
        '7333' => 'Commercial photography, art, and graphics',
        '7338' => 'Quick copy, reproduction, and blueprinting services',
        '7339' => 'Stenographic and secretarial support',
        '7342' => 'Exterminating and disinfecting services',
        '7349' => 'Cleaning, maintenance, and janitorial services',
        '7361' => 'Employment agencies and temporary help services',
        '7372' => 'Computer programming, data processing, and integrated systems design services',
        '7375' => 'Information retrieval services',
        '7379' => 'Computer maintenance, repair, and services (not elsewhere classified)',
        '7392' => 'Management, consulting, and public relations services',
        '7393' => 'Detective agencies, protective services, and security services',
        '7394' => 'Equipment, tool, furniture, and appliance rental and leasing',
        '7395' => 'Photofinishing laboratories and photo developing',
        '7399' => 'Business services (not elsewhere classified)',
        '7512' => 'Automobile rental agency',
        '7513' => 'Truck and utility trailer rentals',
        '7519' => 'Motor home and recreational vehicle rentals',
        '7523' => 'Parking lots and garages',
        '7531' => 'Automotive body repair shops',
        '7534' => 'Tire retreading and repair shops',
        '7535' => 'Automotive paint shops',
        '7538' => 'Automotive service shops (non-dealer)',
        '7542' => 'Car washes',
        '7549' => 'Towing services',
        '7622' => 'Electronics repair shops',
        '7623' => 'Air conditioning and refrigeration repair shops',
        '7629' => 'Electrical and small appliance repair shops',
        '7631' => 'Watch, clock and jewelry repair',
        '7641' => 'Furniture - reupholstery, repair, and refinishing',
        '7692' => 'Welding services',
        '7699' => 'Miscellaneous repair shops and related services',
        '7829' => 'Motion picture and video tape production and distribution',
        '7832' => 'Motion picture theaters',
        '7841' => 'Video tape rental stores',
        '7911' => 'Dance halls, studios, and schools',
        '7922' => 'Theatrical producers and ticket agencies',
        '7929' => 'Bands, orchestras, and miscellaneous entertainers (not elsewhere classified)',
        '7932' => 'Billiard and pool establishments',
        '7933' => 'Bowling alleys',
        '7941' => 'Commercial sports, professional sports clubs, athletic fields, and sports promoters',
        '7991' => 'Tourist attractions and exhibits',
        '7992' => 'Public golf courses',
        '7993' => 'Video amusement game supplies',
        '7994' => 'Video game arcades / establishments',
        '7995' => 'Betting, including lottery tickets, casino gaming chips, off-track betting and wagers at race tracks',
        '7996' => 'Amusement parks, circuses, carnivals, and fortune tellers',
        '7997' => 'Membership clubs, country clubs, and private golf courses',
        '7998' => 'Aquariums, seaquariums, dolphinariums',
        '7999' => 'Recreation services (not elsewhere classified)',
        '8011' => 'Doctors and physicians (not elsewhere classified)',
        '8021' => 'Dentists and orthodontists',
        '8031' => 'Osteopaths',
        '8041' => 'Chiropractors',
        '8042' => 'Optometrists and ophthalmologists',
        '8043' => 'Opticians, optical goods, and eyeglasses',
        '8049' => 'Podiatrists and chiropodists',
        '8050' => 'Nursing and personal care facilities',
        '8062' => 'Hospitals',
        '8071' => 'Medical and dental laboratories',
        '8099' => 'Medical services and health practitioners (not elsewhere classified)',
        '8111' => 'Legal services and attorneys',
        '8211' => 'Elementary and secondary schools',
        '8220' => 'Colleges, universities, professional schools, and junior colleges',
        '8241' => 'Correspondence schools',
        '8244' => 'Business and secretarial schools',
        '8249' => 'Vocational and trade schools',
        '8299' => 'Schools and educational services (not elsewhere classified)',
        '8351' => 'Child care services',
        '8398' => 'Charitable and social service organizations',
        '8641' => 'Civic, social, and fraternal associations',
        '8651' => 'Political organizations',
        '8661' => 'Religious organizations',
        '8675' => 'Automobile associations',
        '8699' => 'Membership organizations (not elsewhere classified)',
        '8734' => 'Testing laboratories (non-medical testing)',
        '8911' => 'Architectural, engineering, and surveying services',
        '8931' => 'Accounting, auditing, and bookkeeping services',
        '8999' => 'Professional sevices (not elsewhere classified)',
        '9211' => 'Court costs, including alimony and child support',
        '9222' => 'Fines',
        '9223' => 'Bail and bond payments',
        '9311' => 'Tax payments',
        '9399' => 'Government services (not elsewhere classified)',
        '9402' => 'Postal services - government only',
        '9405' => 'US federal government agencies or departments'
    ];

    /**
     * CURRENCY (53)
     */
    const ID_TRANSACTION_CURRENCY = '53';
    const CURRENCY_HKD = 'HKD';
    const CURRENCY_HKD_NUMERIC = '344';
    const CURRENCY_IDR = 'IDR';
    const CURRENCY_IDR_NUMERIC = '360';
    const CURRENCY_INR = 'INR';
    const CURRENCY_INR_NUMERIC = '356';
    const CURRENCY_MYR = 'MYR';
    const CURRENCY_MYR_NUMERIC = '458';
    const CURRENCY_SGD = 'SGD';
    const CURRENCY_SGD_NUMERIC = '702';
    const CURRENCY_THB = 'THB';
    const CURRENCY_THB_NUMERIC = '764';
    /**
     * @var string[] ISO4217
     */
    protected $currency_codes = [
        //self::CURRENCY_HKD_NUMERIC => self::CURRENCY_HKD,
        //self::CURRENCY_IDR_NUMERIC => self::CURRENCY_IDR,
        //self::CURRENCY_INR_NUMERIC => self::CURRENCY_INR,
        //self::CURRENCY_MYR_NUMERIC => self::CURRENCY_MYR,
        self::CURRENCY_SGD_NUMERIC => self::CURRENCY_SGD,
        self::CURRENCY_THB_NUMERIC => self::CURRENCY_THB
    ];

    /**
     * TRANSACTION AMOUNT (54)
     */
    const ID_TRANSACTION_AMOUNT = '54';

    /**
     * TIP OR CONVENIENCE FEE (55-57)
     */
    const ID_TIP_OR_CONVENIENCE_FEE_INDICATOR = '55';
    const ID_VALUE_OF_FEE_FIXED = '56';
    const ID_VALUE_OF_FEE_PERCENTAGE = '57';
    const FEE_INDICATOR_TIP = '01';
    const FEE_INDICATOR_TIP_VALUE = 'TIP';
    const FEE_INDICATOR_CONVENIENCE_FEE_FIXED = '02';
    const FEE_INDICATOR_CONVENIENCE_FEE_FIXED_VALUE = 'CONVENIENCE_FEE_FIXED';
    const FEE_INDICATOR_CONVENIENCE_FEE_PERCENTAGE = '03';
    const FEE_INDICATOR_CONVENIENCE_FEE_PERCENTAGE_VALUE = 'CONVENIENCE_FEE_PERCENTAGE';
    protected $tip_or_convenience_fee_indicators = [
        self::FEE_INDICATOR_TIP => self::FEE_INDICATOR_TIP_VALUE,
        self::FEE_INDICATOR_CONVENIENCE_FEE_FIXED => self::FEE_INDICATOR_CONVENIENCE_FEE_FIXED_VALUE,
        self::FEE_INDICATOR_CONVENIENCE_FEE_PERCENTAGE => self::FEE_INDICATOR_CONVENIENCE_FEE_PERCENTAGE_VALUE
    ];

    /**
     * COUNTRY (58)
     */
    const ID_COUNTRY_CODE = '58';
    const COUNTRY_HK = 'HK';
    const COUNTRY_ID = 'ID';
    const COUNTRY_IN = 'IN';
    const COUNTRY_MY = 'MY';
    const COUNTRY_SG = 'SG';
    const COUNTRY_TH = 'TH';
    /**
     * @var string[] ISO3166
     */
    protected $country_codes = [
        //self::COUNTRY_HK,
        //self::COUNTRY_ID,
        //self::COUNTRY_IN,
        //self::COUNTRY_MY,
        self::COUNTRY_SG,
        self::COUNTRY_TH
    ];

    /**
     * MERCHANT NAME (59)
     */
    const ID_MERCHANT_NAME = '59';

    /**
     * CITY (60)
     */
    const ID_MERCHANT_CITY = '60';
    const MERCHANT_CITY_SG = 'SINGAPORE';
    const MERCHANT_CITY_BKK = 'BANGKOK';
    const MERCHANT_CITY_JKT = 'JAKARTA';
    const MERCHANT_CITY_KL = 'KUALA LUMPUR';
    const MERCHANT_CITY_HK = 'HONG KONG';

    /**
     * POSTAL CODE (61)
     */
    const ID_MERCHANT_POSTAL_CODE = '61';

    /**
     * ADDITIONAL DATA (62)
     */
    const ID_ADDITIONAL_DATA_FIELDS = '62';
    const ID_ADDITIONAL_DATA_BILL_NUMBER = '01';
    const ID_ADDITIONAL_DATA_BILL_NUMBER_KEY = 'bill_number';
    const ID_ADDITIONAL_DATA_MOBILE_NUMBER = '02';
    const ID_ADDITIONAL_DATA_MOBILE_NUMBER_KEY = 'mobile_number';
    const ID_ADDITIONAL_DATA_STORE_LABEL = '03';
    const ID_ADDITIONAL_DATA_STORE_LABEL_KEY = 'store_label';
    const ID_ADDITIONAL_DATA_LOYALTY_NUMBER = '04';
    const ID_ADDITIONAL_DATA_LOYALTY_NUMBER_KEY = 'loyalty_number';
    const ID_ADDITIONAL_DATA_REFERENCE_LABEL = '05';
    const ID_ADDITIONAL_DATA_REFERENCE_LABEL_KEY = 'reference_label';
    const ID_ADDITIONAL_DATA_CUSTOMER_LABEL = '06';
    const ID_ADDITIONAL_DATA_CUSTOMER_LABEL_KEY = 'customer_label';
    const ID_ADDITIONAL_DATA_TERMINAL_LABEL = '07';
    const ID_ADDITIONAL_DATA_TERMINAL_LABEL_KEY = 'terminal_label';
    const ID_ADDITIONAL_DATA_PURPOSE_OF_TRANSACTION = '08';
    const ID_ADDITIONAL_DATA_PURPOSE_OF_TRANSACTION_KEY = 'purpose_of_transaction';
    const ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST = '09';
    const ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST_KEY = 'additional_customer_data_request';
    const ID_ADDITIONAL_DATA_CUSTOMER_DATA_REQUEST_MOBILE_ID = 'M';
    const ID_ADDITIONAL_DATA_CUSTOMER_DATA_REQUEST_MOBILE_LABEL = 'MOBILE';
    const ID_ADDITIONAL_DATA_CUSTOMER_DATA_REQUEST_ADDRESS_ID = 'A';
    const ID_ADDITIONAL_DATA_CUSTOMER_DATA_REQUEST_ADDRESS_LABEL = 'ADDRESS';
    const ID_ADDITIONAL_DATA_CUSTOMER_DATA_REQUEST_EMAIL_ID = 'E';
    const ID_ADDITIONAL_DATA_CUSTOMER_DATA_REQUEST_EMAIL_LABEL = 'EMAIL';
    const ID_ADDITIONAL_DATA_MERCHANT_TAX_ID = '10';
    const ID_ADDITIONAL_DATA_MERCHANT_TAX_ID_KEY = 'merchant_tax_id';
    const ID_ADDITIONAL_DATA_MERCHANT_CHANNEL = '11';
    const ID_ADDITIONAL_DATA_MERCHANT_CHANNEL_KEY = 'merchant_channel';
    const MERCHANT_CHANNEL_CHAR_MEDIA_KEY = 'media';
    const MERCHANT_CHANNEL_CHAR_LOCATION_KEY = 'transaction_location';
    const MERCHANT_CHANNEL_CHAR_PRESENCE_KEY = 'merchant_presence';
    protected $merchant_channel_medias = [
        '0' => 'Print - Merchant sticker',
        '1' => 'Print - Bill/Invoice',
        '2' => 'Print - Magazine/Poster',
        '3' => 'Print - Other',
        '4' => 'Screen/Electronic - Merchant POS/POI',
        '5' => 'Screen/Electronic - Website',
        '6' => 'Screen/Electronic - App',
        '7' => 'Screen/Electronic - Other',
    ];
    protected $merchant_channel_locations = [
        '0' => 'At Merchant premises/registered address',
        '1' => 'Not at Merchant premises/registered address',
        '2' => 'Remote Commerce',
        '3' => 'Other',
    ];
    protected $merchant_channel_presences = [
        '0' => 'Attended POI',
        '1' => 'Unattended',
        '2' => 'Semi-attended (self-checkout)',
        '3' => 'Other',
    ];

    /**
     * CRC (63)
     */
    const ID_CRC = '63';
    const CRC_LENGTH = '04';

    /**
     * MERCHANT INFORMATION - LANGUAGE TEMPLATE (64)
     */
    const ID_MERCHANT_INFORMATION_LANGUAGE_TEMPLATE = '64';

    /**
     * Integers
     */
    const POS_ZERO = 0;
    const POS_ONE = 1;
    const POS_TWO = 2;
    const POS_FOUR = 4;
    const POS_SIX = 6;
    const POS_EIGHT = 8;
    const POS_TEN = 10;
    const POS_TWELVE = 12;
    const POS_MINUS_FOUR = -4;
    const LENGTH_ONE = 1;
    const LENGTH_TWO = 2;
    const LENGTH_THREE = 3;
    const LENGTH_FOUR = 4;
    const LENGTH_TEN = 10;
    const LENGTH_TWENTY = 20;
    const LENGTH_TWENTY_FIVE = 25;

    /**
     * Others
     */
    const TIMEZONE_SINGAPORE = 'Asia/Singapore';
    const FORMAT_DATE = 'Y-m-d';

    /* | --------------------------------------------------------------------------------------------------------
       | SINGAPORE
       | -------------------------------------------------------------------------------------------------------- */

    /* | --------------------------------------------------------------------------------------------------------
       | PAYNOW (PREFERABLY 26)
       | -------------------------------------------------------------------------------------------------------- */
    const PAYNOW_CHANNEL = 'SG.PAYNOW';
    const PAYNOW_ID_CHANNEL = '00';
    const PAYNOW_ID_PROXY_TYPE = '01';
    const PAYNOW_ID_PROXY_VALUE = '02';
    const PAYNOW_ID_AMOUNT_EDITABLE = '03';
    const PAYNOW_ID_EXPIRY_DATE = '04';
    const PAYNOW_PROXY_MOBILE = '0';
    const PAYNOW_PROXY_UEN = '2';
    const PAYNOW_AMOUNT_EDITABLE_TRUE = '1';
    const PAYNOW_AMOUNT_EDITABLE_FALSE = '0';
    const PAYNOW_DEFAULT_EXPIRY_DATE = '20991231';
    protected $paynow_keys = [
        '00' => 'reverse_domain',
        '01' => 'proxy_type',
        '02' => 'proxy_value',
        '03' => 'amount_editable',
        '04' => 'expiry_date'
    ];
    protected $paynow_proxy_type = [
        '0' => 'MOBILE',
        '2' => 'UEN'
    ];
    protected $paynow_amount_editable = [
        '1' => TRUE,
        '0' => FALSE
    ];

    /* | --------------------------------------------------------------------------------------------------------
       | FAVEPAY (ANY)
       | -------------------------------------------------------------------------------------------------------- */
    const FAVE_CHANNEL = 'COM.MYFAVE';
    const FAVE_CHANNEL_NAME = 'FavePay';
    const FAVE_URL = 'https://myfave.com/qr/';
    const FAVE_ID_REVERSE_DOMAIN = '00';
    const FAVE_ID_URL = '01';
    protected $favepay_keys = [
        '00' => 'reverse_domain',
        '01' => 'url'
    ];

    /* | --------------------------------------------------------------------------------------------------------
       | SGQR (51 FIXED)
       | -------------------------------------------------------------------------------------------------------- */
    const SGQR_CHANNEL = 'SG.SGQR';
    const SGQR_ID_REVERSE_DOMAIN = '00';
    const SGQR_ID_IDENTIFICATION_NUMBER = '01';
    const SGQR_ID_VERSION = '02';
    const SGQR_ID_POSTAL_CODE = '03';
    const SGQR_ID_LEVEL = '04';
    const SGQR_ID_UNIT_NUMBER = '05';
    const SGQR_ID_MISC = '06';
    const SGQR_ID_VERSION_DATE = '07';
    protected $sgqr_keys = [
        '00' => 'reverse_domain',
        '01' => 'sgqr_id_number',
        '02' => 'version',
        '03' => 'postal_code',
        '04' => 'level',
        '05' => 'unit_number',
        '06' => 'miscellaneous',
        '07' => 'new_version_date'
    ];

    /* | --------------------------------------------------------------------------------------------------------
       | THAILAND
       | -------------------------------------------------------------------------------------------------------- */

    /* | --------------------------------------------------------------------------------------------------------
       | PROMPTPAY CREDIT TRANSFER (29)
       | -------------------------------------------------------------------------------------------------------- */
    const PROMPTPAY_CHANNEL = 'A000000677010111';
    const PROMPTPAY_CHANNEL_NAME = 'TH.PROMPTPAY';
    const PROMPTPAY_ID = '29';
    const PROMPTPAY_ID_APP_ID = '00';
    const PROMPTPAY_ID_MOBILE = '01';
    const PROMPTPAY_ID_TAX_ID = '02';
    const PROMPTPAY_ID_EWALLET_ID = '03';
    const PROMPTPAY_ID_BANK_ACCT_NO = '04';
    const PROMPTPAY_PROXY_MOBILE = 'MOBILE';
    const PROMPTPAY_PROXY_TAX_ID = 'TAX_ID';
    const PROMPTPAY_PROXY_EWALLET_ID = 'EWALLET_ID';
    const PROMPTPAY_PROXY_BANK_ACCT_NO = 'BANK_ACCOUNT_NO';
    protected $promptpay_keys = [
        '00' => 'guid',
        '96' => 'mobile_number',
        '97' => 'proxy_type',
        '98' => 'proxy_value',
        '99' => 'channel_name'
    ];

    /* | --------------------------------------------------------------------------------------------------------
       | PROMPTPAY BILL PAYMENT (30)
       | -------------------------------------------------------------------------------------------------------- */
    const PROMPTPAY_BILL_CHANNEL = 'A000000677010112';
    const PROMPTPAY_BILL_CHANNEL_NAME = 'TH.PROMPTPAY.BILL';
    const PROMPTPAY_BILL_ID = '30';
    const PROMPTPAY_BILL_APP_ID = '00';
    const PROMPTPAY_BILL_BILLER_ID = '01';
    const PROMPTPAY_BILL_REF_1 = '02';
    const PROMPTPAY_BILL_REF_2 = '03';
    protected $promptpay_bill_keys = [
        '00' => 'guid',
        '01' => 'biller_id',
        '02' => 'reference_no_1',
        '03' => 'reference_no_2'
    ];

    /* | --------------------------------------------------------------------------------------------------------
       | INDONESIA
       | -------------------------------------------------------------------------------------------------------- */

    /* | --------------------------------------------------------------------------------------------------------
       | TELKOM.ID
       | -------------------------------------------------------------------------------------------------------- */
//    const TELKOM_CHANNEL = 'ID.CO.TELKOM.WWW';
//    protected $telkom_keys = [
//        '00' => 'reverse_domain',
//        '01' => '01',
//        '02' => '02',
//        '03' => '03'
//    ];

    /* | --------------------------------------------------------------------------------------------------------
       | QRIS (51)
       | -------------------------------------------------------------------------------------------------------- */
//    const QRIS_CHANNEL = 'ID.CO.QRIS.WWW';
//    protected $qris_keys = [
//        '00' => 'reverse_domain',
//        '02' => 'nmid', // National Merchant ID
//        '03' => '03'
//    ];

    /* | --------------------------------------------------------------------------------------------------------
       | OTHERS
       | -------------------------------------------------------------------------------------------------------- */

    /* | --------------------------------------------------------------------------------------------------------
       | DASH
       | -------------------------------------------------------------------------------------------------------- */
//    const DASH_CHANNEL = 'SG.COM.DASH.WWW';
//    const DASH_CHANNEL_NAME = 'Singtel Dash';
//    protected $dash_keys = [
//        '00' => 'channel',
//        '01' => 'merchant_account'
//    ];

    /* | --------------------------------------------------------------------------------------------------------
       | LIQUIDPAY
       | -------------------------------------------------------------------------------------------------------- */
//    const LIQUIDPAY_CHANNEL = 'A0000007620001';
//    const LIQUIDPAY_CHANNEL_NAME = 'LiquidPay';
//    const LIQUIDPAY_REVERSE_URL = 'COM.LQDPALLIANCE.WWW';
//    protected $liquidpay_keys = [
//        '00' => 'app_id',
//        '01' => 'reverse_url',
//        '02' => 'payee_id',
//        '03' => 'service_code'
//    ];

    /* | --------------------------------------------------------------------------------------------------------
       | EZ-LINK
       | -------------------------------------------------------------------------------------------------------- */
//    const EZLINK_CHANNEL = 'SG.COM.EZLINK';
//    const EZLINK_CHANNEL_NAME = 'EZ-Link';
//    protected $ezlink_keys = [
//        '00' => 'channel',
//        '01' => 'merchant_id',
//        '02' => 'sgqr_indicator',
//        '03' => 'offline_usage',
//        '04' => 'verification_code'
//    ];

    /* | --------------------------------------------------------------------------------------------------------
       | GRABPAY
       | -------------------------------------------------------------------------------------------------------- */
//    const GRAB_CHANNEL = 'COM.GRAB';
//    const GRAB_CHANNEL_NAME = 'GrabPay';
//    protected $grab_keys = [
//        '00' => 'channel',
//        '01' => 'merchant_id'
//    ];

    /* | --------------------------------------------------------------------------------------------------------
       | DBS PAYLAH!
       | -------------------------------------------------------------------------------------------------------- */
//    const PAYLAH_CHANNEL = 'COM.DBS';
//    const PAYLAH_CHANNEL_NAME = 'DBS PayLah!';
//    protected $paylah_keys = [
//        '00' => 'channel',
//        '01' => 'qr_transaction_ref_id',
//        '02' => 'qr_id'
//    ];

    /* | --------------------------------------------------------------------------------------------------------
       | WECHAT PAY
       | -------------------------------------------------------------------------------------------------------- */
//    const WECHAT_CHANNEL = 'COM.QQ.WEIXIN.PAY';
//    const WECHAT_CHANNEL_NAME = 'WeChat Pay';
//    protected $wechat_keys = [
//        '00' => 'channel',
//        '01' => 'merchant_account',
//        '02' => 'terminal_id',
//        '03' => '??03??',
//        '04' => '??04??',
//        '99' => '??99??',
//    ];

    /* | --------------------------------------------------------------------------------------------------------
       | UOB
       | -------------------------------------------------------------------------------------------------------- */
//    const UOB_CHANNEL = 'SG.COM.UOB';
//    const UOB_CHANNEL_NAME = 'UOB';
//    protected $uob_keys = [
//        '00' => 'channel',
//        '01' => 'merchant_account'
//    ];

    /* | --------------------------------------------------------------------------------------------------------
       | SHOPEEPAY
       | -------------------------------------------------------------------------------------------------------- */
//    const AIRPAY_CHANNEL = 'SG.AIRPAY';
//    const AIRPAY_CHANNEL_NAME = 'ShopeePay/AirPay';
//    protected $airpay_keys = [
//        '00' => 'channel',
//        '01' => 'merchant_account_information'
//    ];

    /* | --------------------------------------------------------------------------------------------------------
       | ERRORS/WARNINGS
       | -------------------------------------------------------------------------------------------------------- */
    const MESSAGE_TYPE_ERROR = 'ERR';
    const MESSAGE_TYPE_WARNING = 'WRN';
    const ERROR_VALUE_PLACEHOLDER = '???';
    // ERROR CODES
    const ERROR_ID_NOT_FOUND = 'E00X';
    const ERROR_MESSAGE_TYPE_NOT_FOUND = 'E00Y';
    const ERROR_ID_PAYLOAD_FORMAT_INDICATOR_INVALID = 'E001';
    const ERROR_ID_TYPE_OF_INITIATION_INVALID = 'E002';
    const ERROR_ID_CURRENCY_NOT_SUPPORTED = 'E003';
    const ERROR_ID_AMOUNT_INVALID = 'E004';
    const ERROR_ID_FEE_INDICATOR_INVALID = 'E005';
    const ERROR_ID_FEE2_EXIST_BUT_INDICATOR_INVALID = 'E006';
    const ERROR_ID_FEE3_EXIST_BUT_INDICATOR_INVALID = 'E007';
    const ERROR_ID_CONVENIENT_FEE_INVALID = 'E008';
    const ERROR_ID_COUNTRY_CODE_INVALID = 'E009';
    const ERROR_ID_CRC_INVALID = 'E010';
    const ERROR_ID_AMOUNT_MISSING = 'E011';
    const ERROR_ID_ACCOUNT_OUT_OF_BOUND = 'E012';
    const ERROR_ID_PAYNOW_INVALID_PROXY_VALUE = 'E013';
    const ERROR_ID_PAYNOW_MISSING_PROXY_TYPE = 'E014';
    const ERROR_ID_PAYNOW_EDITABLE_FALSE_BUT_STATIC = 'E015';
    const ERROR_ID_PAYNOW_EXPIRED_QR = 'E016';
    const ERROR_ID_PAYNOW_EXPIRY_DATE_INVALID = 'E017';
    const ERROR_ID_PROMPTPAY_MISSING_PROXY = 'E018';
    const ERROR_ID_PROMPTPAY_INVALID_PROXY = 'E019';
    const ERROR_ID_MISSING_FIELD = 'E999';
    // WARNING CODES
    const WARNING_ID_MCC_INVALID = 'W001';
    const WARNING_ID_MCC_UNKNOWN = 'W002';
    const WARNING_ID_POINT_OF_INITIATION_STATIC_WITH_AMOUNT = 'W003';
    const WARNING_ID_LANGUAGE_TEMPLATE_NOT_SUPPORTED = 'W004';
    const WARNING_ID_ADDITIONAL_DATA_INVALID = 'W005';
    const WARNING_ID_INVALID_CUSTOMER_REQUEST_TYPE = 'W006';
    const WARNING_ID_INVALID_MERCHANT_CHANNEL = 'W007';
    protected $messages = [
        // ERROR DECODER
        self::ERROR_ID_NOT_FOUND => "Error ID not found.",
        self::ERROR_MESSAGE_TYPE_NOT_FOUND => "Message type was not found.",
        self::ERROR_ID_PAYLOAD_FORMAT_INDICATOR_INVALID => "Payload format indicator is invalid. Expected '01', found '???'.",
        self::ERROR_ID_TYPE_OF_INITIATION_INVALID => "Type of initiation is invalid. Expected '11' or '12', found '???'.",
        self::ERROR_ID_CURRENCY_NOT_SUPPORTED => "Currency is not supported. Found '???' as the currency code. Please check the latest release documentation for supported currencies.",
        self::ERROR_ID_AMOUNT_INVALID => "Transaction amount is invalid. Expected positive floating point number, found '???'.",
        self::ERROR_ID_FEE_INDICATOR_INVALID => "Tip or convenience fee indicator is invalid. Expected '01', '02', or '03', found '???'.",
        self::ERROR_ID_FEE2_EXIST_BUT_INDICATOR_INVALID => "Convenience fee (fixed) was set but the indicator is invalid. Expected '02', found '???'.",
        self::ERROR_ID_FEE3_EXIST_BUT_INDICATOR_INVALID => "Convenience fee (percentage) was set but the indicator is invalid. Expected '03', found '???'.",
        self::ERROR_ID_CONVENIENT_FEE_INVALID => "Convenience fee is invalid. Expected a fixed or percentage value, found '???'.",
        self::ERROR_ID_COUNTRY_CODE_INVALID => "Country code is not supported. Currently, this class only supports SG, TH, MY, ID, found '???'.",
        self::ERROR_ID_CRC_INVALID => "CRC found in the QR Code is incorrect. Expected '???1', found '???2'.",
        self::ERROR_ID_AMOUNT_MISSING => "The type of initiation of this QR Code requires the transaction amount but such amount does not exist.",
        self::ERROR_ID_ACCOUNT_OUT_OF_BOUND => "ID is out-of-bound. Expected '02' to '51', found '???'.",
        self::ERROR_ID_PAYNOW_INVALID_PROXY_VALUE => "Proxy value is invalid. Expected the value of type ???1, found '???2'.",
        self::ERROR_ID_PAYNOW_MISSING_PROXY_TYPE => "Proxy type is missing.",
        self::ERROR_ID_PAYNOW_EDITABLE_FALSE_BUT_STATIC => "PayNow transaction value is set to not editable but the point of initiation is static.",
        self::ERROR_ID_PAYNOW_EXPIRED_QR => "This QR code is already expired. The expiry date was ???.",
        self::ERROR_ID_PAYNOW_EXPIRY_DATE_INVALID => "The expiry date of this QR code is invalid. Expected the date in 'yyyymmdd' format, found '???'.",
        self::ERROR_ID_PROMPTPAY_MISSING_PROXY => "The proxy value (mobile number, tax ID, or eWallet ID) is missing.",
        self::ERROR_ID_PROMPTPAY_INVALID_PROXY => "The proxy value is invalid. Expected a mobile phone number or tax ID, found '???'.",
        // ERROR GENERATOR
        self::ERROR_ID_MISSING_FIELD => "The field ID ??? has never been set.",
        // WARNING
        self::WARNING_ID_MCC_INVALID => "Merchant category code is invalid. Expected 4-digit string, found '???'.",
        self::WARNING_ID_MCC_UNKNOWN => "Merchant category code is unknown or does not exist in the system. Found '???'.",
        self::WARNING_ID_POINT_OF_INITIATION_STATIC_WITH_AMOUNT => "Point of initiation was set to STATIC (01) but found that transaction amount is set. Point of initiation was updated to DYNAMIC (02).",
        self::WARNING_ID_LANGUAGE_TEMPLATE_NOT_SUPPORTED => "Merchant information language template (64) is currently not supported. Please check documentation for newer releases.",
        self::WARNING_ID_ADDITIONAL_DATA_INVALID => "Additional data field (ID ???1) is invalid. Found '???2'.",
        self::WARNING_ID_INVALID_CUSTOMER_REQUEST_TYPE => "Customer data request type is invalid. Expected either 'A', 'E', or 'M', found '???'.",
        self::WARNING_ID_INVALID_MERCHANT_CHANNEL => "Merchant channel value contains invalid character in (???1) position, found '???2'.",
    ];

    /* | --------------------------------------------------------------------------------------------------------
       | VALIDATOR / SANITIZER
       | -------------------------------------------------------------------------------------------------------- */
    const MODE_VALIDATOR = 'V';
    const MODE_SANITIZER = 'S';

    /* | --------------------------------------------------------------------------------------------------------
       | PUBLIC PROPERTIES
       | -------------------------------------------------------------------------------------------------------- */
    public $mode;
    public $qr_string;
    public $payload_format_indicator;
    public $point_of_initiation;
    public $accounts = [];
    public $merchant_category_code;
    public $transaction_currency;
    public $transaction_amount;
    public $tip_or_convenience_fee_indicator;
    public $convenience_fee_fixed;
    public $convenience_fee_percentage;
    public $country_code;
    public $merchant_name;
    public $merchant_city;
    public $merchant_postal_code;
    public $additional_fields = [];
    public $crc;
    public $errors = [];
    public $warnings = [];

    /**
     * EmvMerchant constructor.
     */
    public function __construct()
    {
    }

    /* | --------------------------------------------------------------------------------------------------------
       | phpCrc16 v1.1 -- CRC16/CCITT implementation
       |
       | By Matteo Beccati <matteo@beccati.com>
       |
       | Original code by:
       | Ashley Roll
       | Digital Nemesis Pty Ltd
       | www.digitalnemesis.com
       | ash@digitalnemesis.com
       |
       | Test Vector: "123456789" (character string, no quotes)
       | Generated CRC: 0x29B1
       |
       | -------------------------------------------------------------------------------------------------------- */
    /**
     * Returns CRC16 of a string as int value
     * @param string $str The string to digest
     * @return string
     */
    protected function CRC16($str)
    {
        static $CRC16_Lookup = array(
            0x0000, 0x1021, 0x2042, 0x3063, 0x4084, 0x50A5, 0x60C6, 0x70E7,
            0x8108, 0x9129, 0xA14A, 0xB16B, 0xC18C, 0xD1AD, 0xE1CE, 0xF1EF,
            0x1231, 0x0210, 0x3273, 0x2252, 0x52B5, 0x4294, 0x72F7, 0x62D6,
            0x9339, 0x8318, 0xB37B, 0xA35A, 0xD3BD, 0xC39C, 0xF3FF, 0xE3DE,
            0x2462, 0x3443, 0x0420, 0x1401, 0x64E6, 0x74C7, 0x44A4, 0x5485,
            0xA56A, 0xB54B, 0x8528, 0x9509, 0xE5EE, 0xF5CF, 0xC5AC, 0xD58D,
            0x3653, 0x2672, 0x1611, 0x0630, 0x76D7, 0x66F6, 0x5695, 0x46B4,
            0xB75B, 0xA77A, 0x9719, 0x8738, 0xF7DF, 0xE7FE, 0xD79D, 0xC7BC,
            0x48C4, 0x58E5, 0x6886, 0x78A7, 0x0840, 0x1861, 0x2802, 0x3823,
            0xC9CC, 0xD9ED, 0xE98E, 0xF9AF, 0x8948, 0x9969, 0xA90A, 0xB92B,
            0x5AF5, 0x4AD4, 0x7AB7, 0x6A96, 0x1A71, 0x0A50, 0x3A33, 0x2A12,
            0xDBFD, 0xCBDC, 0xFBBF, 0xEB9E, 0x9B79, 0x8B58, 0xBB3B, 0xAB1A,
            0x6CA6, 0x7C87, 0x4CE4, 0x5CC5, 0x2C22, 0x3C03, 0x0C60, 0x1C41,
            0xEDAE, 0xFD8F, 0xCDEC, 0xDDCD, 0xAD2A, 0xBD0B, 0x8D68, 0x9D49,
            0x7E97, 0x6EB6, 0x5ED5, 0x4EF4, 0x3E13, 0x2E32, 0x1E51, 0x0E70,
            0xFF9F, 0xEFBE, 0xDFDD, 0xCFFC, 0xBF1B, 0xAF3A, 0x9F59, 0x8F78,
            0x9188, 0x81A9, 0xB1CA, 0xA1EB, 0xD10C, 0xC12D, 0xF14E, 0xE16F,
            0x1080, 0x00A1, 0x30C2, 0x20E3, 0x5004, 0x4025, 0x7046, 0x6067,
            0x83B9, 0x9398, 0xA3FB, 0xB3DA, 0xC33D, 0xD31C, 0xE37F, 0xF35E,
            0x02B1, 0x1290, 0x22F3, 0x32D2, 0x4235, 0x5214, 0x6277, 0x7256,
            0xB5EA, 0xA5CB, 0x95A8, 0x8589, 0xF56E, 0xE54F, 0xD52C, 0xC50D,
            0x34E2, 0x24C3, 0x14A0, 0x0481, 0x7466, 0x6447, 0x5424, 0x4405,
            0xA7DB, 0xB7FA, 0x8799, 0x97B8, 0xE75F, 0xF77E, 0xC71D, 0xD73C,
            0x26D3, 0x36F2, 0x0691, 0x16B0, 0x6657, 0x7676, 0x4615, 0x5634,
            0xD94C, 0xC96D, 0xF90E, 0xE92F, 0x99C8, 0x89E9, 0xB98A, 0xA9AB,
            0x5844, 0x4865, 0x7806, 0x6827, 0x18C0, 0x08E1, 0x3882, 0x28A3,
            0xCB7D, 0xDB5C, 0xEB3F, 0xFB1E, 0x8BF9, 0x9BD8, 0xABBB, 0xBB9A,
            0x4A75, 0x5A54, 0x6A37, 0x7A16, 0x0AF1, 0x1AD0, 0x2AB3, 0x3A92,
            0xFD2E, 0xED0F, 0xDD6C, 0xCD4D, 0xBDAA, 0xAD8B, 0x9DE8, 0x8DC9,
            0x7C26, 0x6C07, 0x5C64, 0x4C45, 0x3CA2, 0x2C83, 0x1CE0, 0x0CC1,
            0xEF1F, 0xFF3E, 0xCF5D, 0xDF7C, 0xAF9B, 0xBFBA, 0x8FD9, 0x9FF8,
            0x6E17, 0x7E36, 0x4E55, 0x5E74, 0x2E93, 0x3EB2, 0x0ED1, 0x1EF0
        );
        $crc16 = 0xFFFF; // the CRC
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++)
        {
            $t = ($crc16 >> 8) ^ ord($str[$i]); // High byte Xor Message Byte to get index
            $crc16 = (($crc16 << 8) & 0xffff) ^ $CRC16_Lookup[$t]; // Update the CRC from table
        }
        // crc16 now contains the CRC value
        return $crc16;
    }

    /**
     * Returns CRC16 of a string as hexadecimal string
     * @param string $str The string to digest
     * @return string
     */
    protected function CRC16HexDigest($str)
    {
        return sprintf('%04X', $this->CRC16($str));
    }

    /* | --------------------------------------------------------------------------------------------------------
       | ERRORS
       | -------------------------------------------------------------------------------------------------------- */
    /**
     * Add error or warning message in class public property
     * @param string|int $field_id Field ID
     * @param string $message_type Type of the message, MESSAGE_TYPE_ERROR or MESSAGE_TYPE_WARNING
     * @param string $message_id The message ID as defined in the class
     * @param string|array $params The string or array of the values to be passed to the message
     */
    protected function add_message($field_id, $message_type, $message_id, $params = '')
    {
        if (isset($this->messages[$message_id]))
        {
            $message = $this->messages[$message_id];
            if ( ! empty($params))
            {
                if (is_array($params))
                {
                    $intCount = count($params);
                    $search_array = [];
                    for ($i = 1; $i <= $intCount; $i++)
                    {
                        $search_array[] = self::ERROR_VALUE_PLACEHOLDER . $i;
                    }
                    $message = str_replace($search_array, $params, $message);
                } else
                {
                    $message = str_replace(self::ERROR_VALUE_PLACEHOLDER, $params, $message);
                }
            }
            $array = [
                'field_id' => intval($field_id),
                'code' => $message_id,
                'message' => $message
            ];
            switch ($message_type)
            {
                case self::MESSAGE_TYPE_ERROR:
                    $this->errors[] = $array;
                    break;
                case self::MESSAGE_TYPE_WARNING:
                    $this->warnings[] = $array;
                    break;
                default:
                    $this->warnings[] = $array;
                    $this->errors[] = [
                        'field_id' => intval($field_id),
                        'code' => self::ERROR_MESSAGE_TYPE_NOT_FOUND,
                        'message' => $this->messages[self::ERROR_MESSAGE_TYPE_NOT_FOUND]
                    ];
            }
        } else
        {
            $this->errors[] = [
                'field_id' => intval($field_id),
                'code' => self::ERROR_ID_NOT_FOUND,
                'message' => $this->messages[self::ERROR_ID_NOT_FOUND]
            ];
        }
    }

    /* | --------------------------------------------------------------------------------------------------------
       | VALIDATORS
       | -------------------------------------------------------------------------------------------------------- */
    /**
     * Validate or clean characters for those in ANS format
     * @param string $string The string to validate
     * @param string $mode Either sanitizer or validator
     * @return false|int|string
     */
    protected function validate_ans_charset($string, $mode)
    {
        switch ($mode)
        {
            case self::MODE_VALIDATOR:
                return preg_match('/[\x20-\x7E]+/', $string);
            case self::MODE_SANITIZER:
                return filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
            default:
                return FALSE;
        }
    }

    /**
     * Validate the character set and check length of the input string
     * @param string $string String to check
     * @param int $length Max length
     * @return bool
     */
    protected function validate_ans_charset_len($string, $length)
    {
        return (preg_match('/[\x20-\x7E]+/', $string) && strlen($string) <= $length);
    }

    /**
     * Validate and get transaction amount value
     * @param string $amount
     * @return false|float
     */
    protected function parse_money_amount($amount)
    {
        if (preg_match('/^(\d+|\d+\.|\d+\.\d+)$/', $amount))
        {
            return floatval($amount);
        } else
        {
            return FALSE;
        }
    }

    /**
     * Validate and get percentage amount from 00.01 to 99.99
     * @param string $amount
     * @return false|float
     */
    protected function parse_percentage_amount($amount)
    {
        if (preg_match('/^\d{1,2}(\.\d{0,2}){0,1}$/', $amount) && 0.00 < floatval(($amount)))
        {
            return floatval($amount);
        } else
        {
            return FALSE;
        }
    }

    /**
     * Parse date in yyyymmdd format to Y-m-d
     * @param string $string
     * @return false|string
     */
    protected function parse_date_yyyymmdd($string)
    {
        if (preg_match('/[2-9]\d{3}(0[1-9]|1[0-2])(0[1-9]|[1-2]\d|3[0-1])/', $string))
        {
            $year = substr($string, self::POS_ZERO, self::LENGTH_FOUR);
            $month = substr($string, self::POS_FOUR, self::LENGTH_TWO);
            $date = substr($string, self::POS_SIX, self::LENGTH_TWO);
            return "{$year}-{$month}-{$date}";
        } else
        {
            return FALSE;
        }
    }

    /**
     * Parse date in yyyy-mm-dd format to yyyymmdd
     * @param string $string
     * @param bool $check_future
     * @return bool|string
     */
    protected function format_date_with_dash($string, $check_future = TRUE)
    {
        if (preg_match('/[2-9]\d{3}\-(0[1-9]|1[0-2])\-(0[1-9]|[1-2]\d|3[0-1])/', $string))
        {
            if ($check_future)
            {
                $now = strtotime('now');
                $input = strtotime($string);
                return ($now < $input);
            } else
            {
                return str_replace('-', '', $string);
            }
        } else
        {
            return FALSE;
        }
    }
}