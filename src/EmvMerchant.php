<?php

namespace EMVQR;

class EmvMerchant {

	const MODE_GENERATE = 'GENERATE';
	const MODE_DECODE = 'DECODE';

	const ID_PAYLOAD_FORMAT_INDICATOR = '00';
	const ID_POINT_OF_INITIATION = '01';
	const ID_ACCOUNT_PAYNOW = '26';
	const ID_ACCOUNT_PROMPTPAY = '29';
	const ID_ACCOUNT_SGQR = '51';
	const ID_MERCHANT_CATEGORY_CODE = '52';
	const ID_TRANSACTION_CURRENCY = '53';
	const ID_TRANSACTION_AMOUNT = '54';
	const ID_COUNTRY_CODE = '58';
	const ID_MERCHANT_NAME = '59';
	const ID_MERCHANT_CITY = '60';
	const ID_MERCHANT_POSTAL_CODE = '61';
	const ID_ADDITIONAL_DATA_FIELDS = '62';
	const ID_CRC = '63';

	const ID_ADDITIONAL_DATA_BILL_NUMBER = '01';
	const ID_ADDITIONAL_DATA_MOBILE_NUMBER = '02';
	const ID_ADDITIONAL_DATA_STORE_LABEL = '03';
	const ID_ADDITIONAL_DATA_LOYALTY_NUMBER = '04';
	const ID_ADDITIONAL_DATA_REFERENCE_LABEL = '05';
	const ID_ADDITIONAL_DATA_CUSTOMER_LABEL = '06';
	const ID_ADDITIONAL_DATA_TERMINAL_LABEL = '07';
	const ID_ADDITIONAL_DATA_PURPOSE_OF_TRANSACTION = '08';
	const ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST = '09';
	const ID_ADDITIONAL_DATA_MERCHANT_TAX_ID = '10';
	const ID_ADDITIONAL_DATA_MERCHANT_CHANNEL = '11';

	const PAYLOAD_FORMAT_INDICATOR_VALUE = '01';
	const POINT_OF_INITIATION_STATIC = '11';
	const POINT_OF_INITIATION_STATIC_VALUE = 'STATIC';
	const POINT_OF_INITIATION_DYNAMIC = '12';
	const POINT_OF_INITIATION_DYNAMIC_VALUE = 'DYNAMIC';

	/* | --------------------------------------------------------------------------------------------------------
	   | PAYNOW (26)
	   | -------------------------------------------------------------------------------------------------------- */
	const PAYNOW_CHANNEL = 'SG.PAYNOW';
	const PAYNOW_ID_CHANNEL = '00';
	const PAYNOW_ID_PAYLOAD_TYPE = '01';
	const PAYNOW_ID_PAYLOAD_VALUE = '02';
	const PAYNOW_ID_AMOUNT_EDITABLE = '03';
	const PAYNOW_ID_EXPIRY_DATE = '04';
	const PAYNOW_PAYLOAD_MOBILE = '0';
	const PAYNOW_PAYLOAD_UEN = '2';
	const PAYNOW_AMOUNT_EDITABLE_TRUE = '1';
	const PAYNOW_AMOUNT_EDITABLE_FALSE = '0';
	private $paynow_keys = [
		'00' => 'channel',
		'01' => 'payload_type',
		'02' => 'payload_value',
		'03' => 'amount_editable',
		'04' => 'expiry_date',
		'05' => '??'
	];
	private $paynow_payload_type = [
		'0' => 'mobile',
		'2' => 'uen'
	];
	private $paynow_amount_editable = [
		'1' => 'true',
		'0' => 'false'
	];

	/* | --------------------------------------------------------------------------------------------------------
	   | PROMPTPAY (29)
	   | -------------------------------------------------------------------------------------------------------- */
	const PROMPTPAY_CHANNEL = 'A000000677010111';
	const PROMPTPAY_CHANNEL_NAME = 'TH.PROMPTPAY';
	const PROMPTPAY_ID_APP_ID = '00';
	const PROMPTPAY_ID_MOBILE = '01';
	const PROMPTPAY_ID_TAX_ID = '02';
	const PROMPTPAY_ID_EWALLET_ID = '03';
	const PROMPTPAY_PAYLOAD_MOBILE = 'mobile';
	const PROMPTPAY_PAYLOAD_TAX_ID = 'tax_id';
	const PROMPTPAY_PAYLOAD_EWALLET_ID = 'ewallet_Id';
	private $promptpay_keys = [
		'00' => 'app_id',
		'97' => 'payload_type',
		'98' => 'payload_value',
		'99' => 'channel_name'
	];

	/* | --------------------------------------------------------------------------------------------------------
	   | SGQR (51)
	   | -------------------------------------------------------------------------------------------------------- */
	const SGQR_CHANNEL = 'SG.SGQR';
	private $sgqr_keys = [
		'00' => 'channel',
		'01' => 'sgqr_id_number',
		'02' => 'version',
		'03' => 'postal_code',
		'04' => 'level',
		'05' => 'unit_number',
		'06' => 'miscellaneous',
		'07' => 'new_version_date'
	];

	/* | --------------------------------------------------------------------------------------------------------
	   | FAVEPAY
	   | -------------------------------------------------------------------------------------------------------- */
	const FAVE_CHANNEL = 'COM.MYFAVE';
	const FAVE_CHANNEL_NAME = 'FavePay';
	private $favepay_keys = [
		'00' => 'channel',
		'01' => 'url'
	];

	/* | --------------------------------------------------------------------------------------------------------
	   | DASH
	   | -------------------------------------------------------------------------------------------------------- */
	const DASH_CHANNEL = 'SG.COM.DASH.WWW';
	const DASH_CHANNEL_NAME = 'Singtel Dash';
	private $dash_keys = [
		'00' => 'channel',
		'01' => 'merchant_account'
	];

	/* | --------------------------------------------------------------------------------------------------------
	   | LIQUIDPAY
	   | -------------------------------------------------------------------------------------------------------- */
	const LIQUIDPAY_CHANNEL = 'A0000007620001';
	const LIQUIDPAY_CHANNEL_NAME = 'LiquidPay';
	const LIQUIDPAY_REVERSE_URL = 'COM.LQDPALLIANCE.WWW';
	private $liquidpay_keys = [
		'00' => 'app_id',
		'01' => 'reverse_url',
		'02' => 'payee_id',
		'03' => 'service_code'
	];

	/* | --------------------------------------------------------------------------------------------------------
	   | EZ-LINK
	   | -------------------------------------------------------------------------------------------------------- */
	const EZLINK_CHANNEL = 'SG.COM.EZLINK';
	const EZLINK_CHANNEL_NAME = 'EZ-Link';
	private $ezlink_keys = [
		'00' => 'channel',
		'01' => 'merchant_id',
		'02' => 'sgqr_indicator',
		'03' => 'offline_usage',
		'04' => 'verification_code'
	];

	/* | --------------------------------------------------------------------------------------------------------
	   | GRABPAY
	   | -------------------------------------------------------------------------------------------------------- */
	const GRAB_CHANNEL = 'COM.GRAB';
	const GRAB_CHANNEL_NAME = 'GrabPay';
	private $grab_keys = [
		'00' => 'channel',
		'01' => 'merchant_id'
	];

	/* | --------------------------------------------------------------------------------------------------------
	   | DBS PAYLAH!
	   | -------------------------------------------------------------------------------------------------------- */
	const PAYLAH_CHANNEL = 'COM.DBS';
	const PAYLAH_CHANNEL_NAME = 'DBS PayLah!';
	private $paylah_keys = [
		'00' => 'channel',
		'01' => 'qr_transaction_ref_id',
		'02' => 'qr_id'
	];

	/* | --------------------------------------------------------------------------------------------------------
	   | WECHAT PAY
	   | -------------------------------------------------------------------------------------------------------- */
	const WECHAT_CHANNEL = 'COM.QQ.WEIXIN.PAY';
	const WECHAT_CHANNEL_NAME = 'WeChat Pay';
	private $wechat_keys = [
		'00' => 'channel',
		'01' => 'merchant_account',
		'02' => 'terminal_id',
		'03' => '??03??',
		'04' => '??04??',
		'99' => '??99??',
	];

	/* | --------------------------------------------------------------------------------------------------------
	   | UOB
	   | -------------------------------------------------------------------------------------------------------- */
	const UOB_CHANNEL = 'SG.COM.UOB';
	const UOB_CHANNEL_NAME = 'UOB';
	private $uob_keys = [
		'00' => 'channel',
		'01' => 'merchant_account'
	];

	/* | --------------------------------------------------------------------------------------------------------
	   | SHOPEEPAY
	   | -------------------------------------------------------------------------------------------------------- */
	const AIRPAY_CHANNEL = 'SG.AIRPAY';
	const AIRPAY_CHANNEL_NAME = 'ShopeePay/AirPay';
	private $airpay_keys = [
		'00' => 'channel',
		'01' => 'merchant_account_information'
	];

	/**
	 * @var string[] Merchant category codes ISO18245;
	 * Src: https://docs.checkout.com/resources/codes/merchant-category-codes
	 */
	private $merchant_category_codes = [
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
	 * @var string[] ISO4217
	 */
	private $currency_codes = [
		'360' => 'IDR',
		'458' => 'MYR',
		'702' => 'SGD',
		'764' => 'THB'
	];

	/**
	 * @var string[] ISO3166
	 */
	private $country_codes = [
		'ID',
		'MY',
		'SG',
		'TH'
	];

	/* | --------------------------------------------------------------------------------------------------------
	   | PUBLIC PROPERTIES
	   | -------------------------------------------------------------------------------------------------------- */
	public $mode;
	public $qr_string;
	public $payload_format_indicator;
	public $point_of_initiation;
	public $accounts;
	public $merchant_category_code;
	public $transaction_currency;
	public $transaction_amount;
	public $country_code;
	public $merchant_name;
	public $merchant_city;
	public $merchant_postal_code;
	public $additional_fields;
	public $crc;
	public $error;

	/* | --------------------------------------------------------------------------------------------------------
	   | DECODE PART
	   | -------------------------------------------------------------------------------------------------------- */

	/**
	 * Read and decode the EMV QR string
	 * @param $string string Input string read from the QR Code
	 * @return EmvMerchant
	 */
	public function decode($string)
	{
		$this->mode = self::MODE_DECODE;
		$string = str_replace(chr(194) . chr(160), ' ', $string);
		$this->qr_string = $string;
		while ( ! empty($string))
		{
			$strId = substr($string, 0, 2);
			$intId = intval($strId);
			$intLength = intval(substr($string, 2, 2));
			$strValue = substr($string, 4, $intLength);
			switch ($strId)
			{
				case self::ID_PAYLOAD_FORMAT_INDICATOR:
					$this->process_payload_format_indicator($strValue);
					break;
				case self::ID_POINT_OF_INITIATION:
					$this->process_point_of_initiation($strValue);
					break;
				case self::ID_MERCHANT_CATEGORY_CODE:
					$this->process_merchant_category_code($strValue);
					break;
				case self::ID_TRANSACTION_CURRENCY:
					$this->process_currency($strValue);
					break;
				case self::ID_TRANSACTION_AMOUNT:
					$this->process_amount($strValue);
					break;
				case self::ID_COUNTRY_CODE:
					$this->process_country_code($strValue);
					break;
				case self::ID_MERCHANT_NAME:
					$this->merchant_name = $strValue;
					break;
				case self::ID_MERCHANT_CITY:
					$this->merchant_city = $strValue;
					break;
				case self::ID_MERCHANT_POSTAL_CODE:
					$this->merchant_postal_code = $strValue;
					break;
				case self::ID_ADDITIONAL_DATA_FIELDS:
					$this->process_additional_data($strValue);
					break;
				case self::ID_CRC:
					$this->process_crc($strValue);
					break;
				default:
					$this->process_accounts($intId, $strValue);
			}
			$string = substr($string, 4 + $intLength);
		}
		return $this;
	}

	/**
	 * Add error to the error field
	 * @param $field_id
	 * @param $strValue
	 */
	private function process_error($field_id, $strValue)
	{
		$this->error[$field_id] = "VALUE: $strValue";
	}

	/**
	 * Validate and assign payload format indicator to the class
	 * @param $strValue
	 */
	private function process_payload_format_indicator($strValue)
	{
		if (self::PAYLOAD_FORMAT_INDICATOR_VALUE == $strValue)
		{
			$this->payload_format_indicator = $strValue;
		} else
		{
			$this->process_error(self::ID_PAYLOAD_FORMAT_INDICATOR, $strValue);
		}
	}

	/**
	 * Validate and assign point of initiation to the class
	 * @param $strValue
	 */
	private function process_point_of_initiation($strValue)
	{
		switch ($strValue)
		{
			case self::POINT_OF_INITIATION_STATIC:
				$this->point_of_initiation = self::POINT_OF_INITIATION_STATIC_VALUE;
				break;
			case self::POINT_OF_INITIATION_DYNAMIC:
				$this->point_of_initiation = self::POINT_OF_INITIATION_DYNAMIC_VALUE;
				break;
			default:
				$this->process_error(self::ID_POINT_OF_INITIATION, $strValue);
		}
	}

	/**
	 * Assign merchant category code and its value (according to ISO18245) to the class
	 * @param $strValue
	 */
	private function process_merchant_category_code($strValue)
	{
		$this->merchant_category_code['code'] = $strValue;
		if (isset($this->merchant_category_codes[$strValue]))
		{
			$this->merchant_category_code['value'] = $this->merchant_category_codes[$strValue];
		}
	}

	/**
	 * Validate and assign currency to the class
	 * @param $strValue
	 */
	private function process_currency($strValue)
	{
		if (isset($this->currency_codes[$strValue]))
		{
			$this->transaction_currency = $this->currency_codes[$strValue];
		} else
		{
			$this->process_error(self::ID_TRANSACTION_CURRENCY, $strValue);
		}
	}

	/**
	 * Validate and assign ammount to the class
	 * @param $strValue
	 */
	private function process_amount($strValue)
	{
		$val = floatval($strValue);
		if (0.0 < $val)
		{
			$this->transaction_amount = number_format($val, 2, '.', '');
		} else
		{
			$this->process_error(self::ID_TRANSACTION_AMOUNT, $strValue);
		}
	}

	/**
	 * Validate and assign country code to the class
	 * @param $strValue
	 */
	private function process_country_code($strValue)
	{
		if (in_array($strValue, $this->country_codes))
		{
			$this->country_code = $strValue;
		} else
		{
			$this->process_error(self::ID_COUNTRY_CODE, $strValue);
		}
	}

	/**
	 * Process additional data fields
	 * @param $string
	 */
	private function process_additional_data($string)
	{
		while ( ! empty($string))
		{
			$strId = substr($string, 0, 2);
			$intLength = intval(substr($string, 2, 2));
			$strValue = substr($string, 4, $intLength);
			switch ($strId)
			{
				case self::ID_ADDITIONAL_DATA_BILL_NUMBER:
					$this->additional_fields['bill_number'] = $strValue;
					break;
				case self::ID_ADDITIONAL_DATA_MOBILE_NUMBER:
					$this->additional_fields['mobile_number'] = $strValue;
					break;
				case self::ID_ADDITIONAL_DATA_STORE_LABEL:
					$this->additional_fields['store_label'] = $strValue;
					break;
				case self::ID_ADDITIONAL_DATA_LOYALTY_NUMBER:
					$this->additional_fields['loyalty_number'] = $strValue;
					break;
				case self::ID_ADDITIONAL_DATA_REFERENCE_LABEL:
					$this->additional_fields['reference_label'] = $strValue;
					break;
				case self::ID_ADDITIONAL_DATA_CUSTOMER_LABEL:
					$this->additional_fields['customer_label'] = $strValue;
					break;
				case self::ID_ADDITIONAL_DATA_TERMINAL_LABEL:
					$this->additional_fields['terminal_label'] = $strValue;
					break;
				case self::ID_ADDITIONAL_DATA_PURPOSE_OF_TRANSACTION:
					$this->additional_fields['purpose_of_transaction'] = $strValue;
					break;
				case self::ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST:
					$this->additional_fields['customer_data_request'] = $strValue;
					break;
				case self::ID_ADDITIONAL_DATA_MERCHANT_TAX_ID:
					$this->additional_fields['merchant_tax_id'] = $strValue;
					break;
				case self::ID_ADDITIONAL_DATA_MERCHANT_CHANNEL:
					$this->additional_fields['merchant_channel'] = $strValue;
					break;
			}
			$string = substr($string, 4 + $intLength);
		}
	}

	/**
	 * Process and verify the CRC field
	 * @param $strValue
	 */
	private function process_crc($strValue)
	{
		$this->crc = $strValue;
		// @todo: verify CRC here:
	}

	/**
	 * Process account
	 * @param $intId
	 * @param $strValue
	 */
	private function process_accounts($intId, $strValue)
	{
		if (2 > $intId || 51 < $intId)
		{
			$this->process_error($intId, $strValue);
			return;
		}
		$account_raw = [];
		while ( ! empty($strValue))
		{
			$strId = substr($strValue, 0, 2);
			$intLength = intval(substr($strValue, 2, 2));
			$thisValue = substr($strValue, 4, $intLength);
			$account_raw[$strId] = $thisValue;
			$strValue = substr($strValue, 4 + $intLength);
		}
		$account_raw['00'] = strtoupper($account_raw['00']);
		switch ($account_raw['00'])
		{
			case self::PAYNOW_CHANNEL:
				$this->accounts[] = $this->process_paynow($account_raw, $intId);
				break;
			case self::PROMPTPAY_CHANNEL:
				$this->accounts[] = $this->process_promptpay($account_raw, $intId);
				break;
			case self::SGQR_CHANNEL:
				$this->accounts[] = $this->process_sgqr($account_raw, $intId);
				break;
			case self::FAVE_CHANNEL:
				$this->accounts[] = $this->process_favepay($account_raw, $intId);
				break;
			case self::DASH_CHANNEL:
				$this->accounts[] = $this->process_dash($account_raw, $intId);
				break;
			case self::LIQUIDPAY_CHANNEL:
				$this->accounts[] = $this->process_liquidpay($account_raw, $intId);
				break;
			case self::EZLINK_CHANNEL:
				$this->accounts[] = $this->process_ezlink($account_raw, $intId);
				break;
			case self::GRAB_CHANNEL:
				$this->accounts[] = $this->process_grab($account_raw, $intId);
				break;
			case self::PAYLAH_CHANNEL:
				$this->accounts[] = $this->process_paylah($account_raw, $intId);
				break;
			case self::WECHAT_CHANNEL:
				$this->accounts[] = $this->process_wechat($account_raw, $intId);
				break;
			case self::UOB_CHANNEL:
				$this->accounts[] = $this->process_uob($account_raw, $intId);
				break;
			case self::AIRPAY_CHANNEL:
				$this->accounts[] = $this->process_airpay($account_raw, $intId);
				break;
			default:
				$this->accounts[] = array_merge(['original_id' => $intId], $account_raw);
		}
	}

	/**
	 * Process PayNow account
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_paynow($account_raw, $intId)
	{
		// MOSTLY 26
		$account['original_id'] = $intId;
		foreach ($account_raw as $id => $val)
		{
			switch ($id)
			{
				case self::PAYNOW_ID_PAYLOAD_TYPE:
					$account[$this->paynow_keys[$id]] = $this->paynow_payload_type[$val];
					break;
				case self::PAYNOW_ID_AMOUNT_EDITABLE:
					$account[$this->paynow_keys[$id]] = $this->paynow_amount_editable[$val];
					break;
				default:
					$account[$this->paynow_keys[$id]] = $val;
			}
		}
		return $account;
	}

	/**
	 * Process PromptPay account
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_promptpay($account_raw, $intId)
	{
		// MOSTLY 29
		$account['original_id'] = $intId;
		$account[$this->promptpay_keys[99]] = self::PROMPTPAY_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			switch ($id)
			{
				case self::PROMPTPAY_ID_APP_ID:
					$account[$this->promptpay_keys[$id]] = $val;
					break;
				case self::PROMPTPAY_ID_MOBILE:
					$account[$this->promptpay_keys[97]] = self::PROMPTPAY_PAYLOAD_MOBILE;
					$account[$this->promptpay_keys[98]] = $val;
					break;
				case self::PROMPTPAY_ID_TAX_ID:
					$account[$this->promptpay_keys[97]] = self::PROMPTPAY_PAYLOAD_TAX_ID;
					$account[$this->promptpay_keys[98]] = $val;
					break;
				case self::PROMPTPAY_ID_EWALLET_ID:
					$account[$this->promptpay_keys[97]] = self::PROMPTPAY_PAYLOAD_EWALLET_ID;
					$account[$this->promptpay_keys[98]] = $val;
					break;
			}
		}
		return $account;
	}

	/**
	 * Process SGQR information - not an account but required
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_sgqr($account_raw, $intId)
	{
		// FIXED 51
		$account['original_id'] = $intId;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->sgqr_keys[$id]] = $val;
		}
		return $account;
	}

	/**
	 * Process FavePay
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_favepay($account_raw, $intId)
	{
		$account['original_id'] = $intId;
		$account['channel_name'] = self::FAVE_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->favepay_keys[$id]] = $val;
		}
		return $account;
	}

	/**
	 * Process Dash
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_dash($account_raw, $intId)
	{
		$account['original_id'] = $intId;
		$account['channel_name'] = self::DASH_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->dash_keys[$id]] = $val;
		}
		return $account;
	}

	/**
	 * Process LiquidPay
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_liquidpay($account_raw, $intId)
	{
		$account['original_id'] = $intId;
		$account['channel_name'] = self::LIQUIDPAY_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->liquidpay_keys[$id]] = $val;
		}
		return $account;
	}

	/**
	 * Process EZ-Link
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_ezlink($account_raw, $intId)
	{
		$account['original_id'] = $intId;
		$account['channel_name'] = self::EZLINK_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->ezlink_keys[$id]] = $val;
		}
		return $account;
	}

	/**
	 * Process GrabPay
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_grab($account_raw, $intId)
	{
		$account['original_id'] = $intId;
		$account['channel_name'] = self::GRAB_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->grab_keys[$id]] = $val;
		}
		return $account;
	}

	/**
	 * Process DBS PayLah!
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_paylah($account_raw, $intId)
	{
		$account['original_id'] = $intId;
		$account['channel_name'] = self::PAYLAH_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->paylah_keys[$id]] = $val;
		}
		return $account;
	}

	/**
	 * Process WeChat Pay
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_wechat($account_raw, $intId)
	{
		$account['original_id'] = $intId;
		$account['channel_name'] = self::WECHAT_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->wechat_keys[$id]] = $val;
		}
		return $account;
	}

	/**
	 * Process UOB
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_uob($account_raw, $intId)
	{
		$account['original_id'] = $intId;
		$account['channel_name'] = self::UOB_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->uob_keys[$id]] = $val;
		}
		return $account;
	}

	/**
	 * Process AirPay / ShopeePay
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_airpay($account_raw, $intId)
	{
		$account['original_id'] = $intId;
		$account['channel_name'] = self::AIRPAY_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->airpay_keys[$id]] = $val;
		}
		return $account;
	}

}