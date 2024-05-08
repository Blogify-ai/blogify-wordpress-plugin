<?php
// بسم الله الرحمن الرحيم

namespace PixelShadow\Blogify\API;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class BlogList
{
	/** @var Blog[] */
	public array $data;
	public Pagination $pagination;
}

class Pagination
{
	public ?int $page;
	public ?int $limit;
	public ?int $totalPages;
	public ?int $totalResults;
}

class ContentDetails {
    public ?string $id;
    public ?string $title;
    public ?string $publishAt;
    public ?string $generationMode;
    public ?string $url;
    public ?string $bid;
    public ?string $uid;
    public ?string $status;
    public ?string $inputLanguage;
    public ?string $blogSize;
    public ?string $blogTone;
    public ?string $blogLanguage;
    public ?string $generationStatus;
    public ?string $zapierLink;
    public ?string $wordpressLink;
    public ?string $wordpressorgLink;
    public ?string $mediumLink;
    public ?string $bloggerLink;
    public ?string $wixLink;
    public ?string $mailchimpLink;
    public ?string $facebookLink;
    public ?string $twitterLink;
    public ?string $linkedinLink;
    public ?bool $affiliateCommissionOptIn;
    public ?array $platforms;
    public ?array $socials;
    public ?string $createdAt;
    public ?string $updatedAt;
  }
  
  class Blog extends ContentDetails {
    public ?string $content;
    public ?string $sourceType;
    public ?string $image;
    public ?array $contentImages;
    public ?string $bloggerPublishTime;
    public ?string $wordpressorgPublishTime;
    public ?string $mediumPublishTime;
    public ?string $wordpressPublishTime;
  }
  
  class BlogDetails extends ContentDetails {
    public ?string $prompt;
    public ?string $transcription;
    public ?array $keywords;
    public ?BlogOutline $blogOutline;
  }
  

  class BlogOutline
  {
      public ?string $title;
      public ?string $introduction;
      /** @var Section[]|null */
      public ?array $sections;
  }
  
class Section
{
	public ?string $heading;
	/** @var string[]|null */
	public ?array $bulletPoints;
}


/**
 * Fetches a list of blogs from the provided base URL using the access token.
 *
 * This function utilizes the `wp_remote_get` function to retrieve a list of blogs
 * from the specified base URL with the provided access token. It expects the base
 * URL to include the "/blogs" endpoint.
 *
 * @param string $baseUrl The base URL of the API endpoint (including "/blogs").
 * @param string $access_token The user's access token for authentication.
 *
 * @throws Exception If an error occurs during the API call.
 *
 * @return array An associative array containing the list of blogs retrieved from the API.
 *
 */
function get_blogs(string $baseUrl, string $access_token): BlogList
{
    $response = wp_remote_get(
        "$baseUrl/blogs",
        [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $access_token),
            ],
        ]
    );

    if (is_wp_error($response)) {
        throw new \Exception($response->get_error_message(), $response->get_error_code());
    }

    $body = wp_remote_retrieve_body($response);
    $json = json_decode($body);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception('Failed to decode response data');
    }

    return $json;
}

/*
class TextPrompt
{
    public function __construct(public string $prompt)
    {
        assert(!empty($prompt));
    }
}

class MediaURLPrompt
{
    public function __construct(public string $url)
    {
        assert(!empty($url));
    }
}

class WebURLPrompt
{
    public function __construct(public string $webLink)
    {
        assert(!empty($webLink));
    }
}

trait EnumValues
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

trait EnumCreator
{
    public static function new (string $value): self
    {
        return self::from($value);
    }
}

enum BlogSize: string {
    use EnumValues, EnumCreator;

    case Small = 'Small';
    case Medium = 'Medium';
    case Large = 'Large';
}

enum BlogTone: string {
    use EnumValues, EnumCreator;

    case Engaging = 'Engaging';
    case Inspirational = 'Inspirational';
    case Informative = 'Informative';
    case Professional = 'Professional';
    case Conversational = 'Conversational';
    case Promotional = 'Promotional';
    case Storytelling = 'Storytelling';
    case Educational = 'Educational';
    case News = 'News';
    case Humorous = 'Humorous';
    case Casual = 'Casual';
    case Review = 'Review';
    case HowTo = 'How-to';
}

enum InputLanguage: string {
    use EnumValues, EnumCreator;

    case GlobalEnglish = 'Global English';
    case USEnglish = 'US English';
    case UKEnglish = 'UK English';
    case AustraliaEnglish = 'Australia English';
    case IndiaEnglish = 'India English';
    case NewZealandEnglish = 'New Zealand English';
    case Afrikaans = 'Afrikaans';
    case Arabic = 'Arabic';
    case Armenian = 'Armenian';
    case Azerbaijani = 'Azerbaijani';
    case Belarusian = 'Belarusian';
    case Bosnian = 'Bosnian';
    case BrazilPortuguese = 'Brazil Portuguese';
    case Bulgarian = 'Bulgarian';
    case Catalan = 'Catalan';
    case ChinaChinese = 'China Chinese';
    case Chinese = 'Chinese';
    case Croatian = 'Croatian';
    case Czech = 'Czech';
    case Danish = 'Danish';
    case Estonian = 'Estonian';
    case Finnish = 'Finnish';
    case Flemish = 'Flemish';
    case French = 'French';
    case FrenchCanada = 'French Canada';
    case German = 'German';
    case Greek = 'Greek';
    case Hebrew = 'Hebrew';
    case Hindi = 'Hindi';
    case HindiLatin = 'Hindi Latin';
    case Hungarian = 'Hungarian';
    case Icelandic = 'Icelandic';
    case Indonesian = 'Indonesian';
    case Irish = 'Irish';
    case Italian = 'Italian';
    case Japanese = 'Japanese';
    case Kannada = 'Kannada';
    case Kazakh = 'Kazakh';
    case Korean = 'Korean';
    case LatinAmericanSpanish = 'Latin American Spanish';
    case Latvian = 'Latvian';
    case Lithuanian = 'Lithuanian';
    case Macedonian = 'Macedonian';
    case Malay = 'Malay';
    case Maltese = 'Maltese';
    case Marathi = 'Marathi';
    case Maori = 'Maori';
    case Nepali = 'Nepali';
    case Norwegian = 'Norwegian';
    case Persian = 'Persian';
    case Polish = 'Polish';
    case Portuguese = 'Portuguese';
    case Romanian = 'Romanian';
    case Russian = 'Russian';
    case Serbian = 'Serbian';
    case Slovak = 'Slovak';
    case Slovenian = 'Slovenian';
    case Spanish = 'Spanish';
    case Swahili = 'Swahili';
    case Swedish = 'Swedish';
    case TaiwanChinese = 'Taiwan Chinese';
    case Tamil = 'Tamil';
    case Thai = 'Thai';
    case Turkish = 'Turkish';
    case Ukrainian = 'Ukrainian';
    case Urdu = 'Urdu';
    case Vietnamese = 'Vietnamese';
    case Welsh = 'Welsh';
}

enum BlogLanguage: string {
    use EnumValues, EnumCreator;

    case Afrikaans = 'afrikaans';
    case Albanian = 'albanian';
    case Amharic = 'amharic';
    case Arabic = 'arabic';
    case Armenian = 'armenian';
    case Assamese = 'assamese';
    case Awadhi = 'awadhi';
    case Azerbaijani = 'azerbaijani';
    case Bashkir = 'bashkir';
    case Basque = 'basque';
    case Belarusian = 'belarusian';
    case Bengali = 'bengali';
    case Bhojpuri = 'bhojpuri';
    case Bosnian = 'bosnian';
    case BrazilianPortuguese = 'brazilian-portuguese';
    case Bulgarian = 'bulgarian';
    case Burmese = 'burmese';
    case Cantonese = 'cantonese';
    case Catalan = 'catalan';
    case Cebuano = 'cebuano';
    case Chhattisgarhi = 'chhattisgarhi';
    case Chichewa = 'chichewa';
    case Chinese = 'chinese';
    case ChineseTraditional = 'chinese traditional';
    case Corsican = 'corsican';
    case Croatian = 'croatian';
    case Czech = 'czech';
    case Danish = 'danish';
    case Dogri = 'dogri';
    case Dutch = 'dutch';
    case English = 'english';
    case Esperanto = 'esperanto';
    case Estonian = 'estonian';
    case Faroese = 'faroese';
    case Fijian = 'fijian';
    case Filipino = 'filipino';
    case Finnish = 'finnish';
    case Flemish = 'flemish';
    case French = 'french';
    case FrenchCanada = 'french canada';
    case Frisian = 'frisian';
    case Galician = 'galician';
    case Georgian = 'georgian';
    case German = 'german';
    case GermanInformalDuForm = 'german informal du-form';
    case Greek = 'greek';
    case Guarani = 'guarani';
    case Gujarati = 'gujarati';
    case HaitianCreole = 'haitian creole';
    case Haryanvi = 'haryanvi';
    case Hausa = 'hausa';
    case Hawaiian = 'hawaiian';
    case Hebrew = 'hebrew';
    case Hindi = 'hindi';
    case HindiLatin = 'hindi latin';
    case Hungarian = 'hungarian';
    case Icelandic = 'icelandic';
    case Igbo = 'igbo';
    case Indonesian = 'indonesian';
    case Irish = 'irish';
    case Italian = 'italian';
    case Japanese = 'japanese';
    case Javanese = 'javanese';
    case Kannada = 'kannada';
    case Kashmiri = 'kashmiri';
    case Kazakh = 'kazakh';
    case Khmer = 'khmer';
    case Kinyarwanda = 'kinyarwanda';
    case Kirundi = 'kirundi';
    case Konkani = 'konkani';
    case Korean = 'korean';
    case Kurdish = 'kurdish';
    case Kyrgyz = 'kyrgyz';
    case Lao = 'lao';
    case LatinAmericanSpanish = 'latin american spanish';
    case Latvian = 'latvian';
    case Lithuanian = 'lithuanian';
    case Luxembourgish = 'luxembourgish';
    case Macedonian = 'macedonian';
    case Maithili = 'maithili';
    case Malagasy = 'malagasy';
    case Malay = 'malay';
    case Malayalam = 'malayalam';
    case Maltese = 'maltese';
    case Mandarin = 'mandarin';
    case Maori = 'maori';
    case Marathi = 'marathi';
    case Marwari = 'marwari';
    case MeiteilonManipuri = 'meiteilon manipuri';
    case MinNan = 'min-nan';
    case Mizo = 'mizo';
    case Moldovan = 'moldovan';
    case Mongolian = 'mongolian';
    case Montenegrin = 'montenegrin';
    case Nahuatl = 'nahuatl';
    case Navajo = 'navajo';
    case Ndebele = 'ndebele';
    case Nepali = 'nepali';
    case Norwegian = 'norwegian';
    case Odia = 'odia';
    case Oriya = 'oriya';
    case Pashto = 'pashto';
    case Persian = 'persian';
    case Polish = 'polish';
    case Portuguese = 'portuguese';
    case Punjabi = 'punjabi';
    case Quechua = 'quechua';
    case Rajasthani = 'rajasthani';
    case Romanian = 'romanian';
    case Russian = 'russian';
    case Samoan = 'samoan';
    case Sanskrit = 'sanskrit';
    case Santali = 'santali';
    case ScotsGaelic = 'scots gaelic';
    case Sepedi = 'sepedi';
    case Serbian = 'serbian';
    case Sesotho = 'sesotho';
    case Shona = 'shona';
    case Sicilian = 'sicilian';
    case Sindhi = 'sindhi';
    case Sinhala = 'sinhala';
    case Sinhalese = 'sinhalese';
    case Slovak = 'slovak';
    case Slovene = 'slovene';
    case Slovenian = 'slovenian';
    case Somali = 'somali';
    case Spanish = 'spanish';
    case Sundanese = 'sundanese';
    case Swahili = 'swahili';
    case Swedish = 'swedish';
    case Tagalog = 'tagalog';
    case Tajik = 'tajik';
    case Tamil = 'tamil';
    case Tatar = 'tatar';
    case Telugu = 'telugu';
    case Thai = 'thai';
    case Tibetan = 'tibetan';
    case Tsonga = 'tsonga';
    case Turkish = 'turkish';
    case Turkmen = 'turkmen';
    case Twi = 'twi';
    case Uighur = 'uighur';
    case Ukrainian = 'ukrainian';
    case Urdu = 'urdu';
    case Uzbek = 'uzbek';
    case Valencian = 'valencian';
    case Venda = 'venda';
    case Vietnamese = 'vietnamese';
    case Welsh = 'welsh';
    case Wolof = 'wolof';
    case Wu = 'wu';
    case Xhosa = 'xhosa';
    case Xitsonga = 'xitsonga';
    case Yiddish = 'yiddish';
    case Yoruba = 'yoruba';
    case Zhuang = 'zhuang';
    case Zulu = 'zulu';
}

enum GrammaticalPerson: string {
    use EnumValues, EnumCreator;

    case First = 'First Person';
    case Second = 'Second Person';
    case Third = 'Third Person';
}

class BlogCreateModel
{

    public function __construct(public TextPrompt | MediaURLPrompt | WebURLPrompt $prompt,
        public BlogSize $blogSize,
        public BlogTone $blogTone,
        public InputLanguage $inputLanguage,
        public BlogLanguage $blogLanguage,
        public GrammaticalPerson $writerPointOfView,
    ) {}

    public function toJSON(): string
    {
        return json_encode(
            [
                 ...(array) $this->prompt,
                'blogSize' => $this->blogSize->value,
                'blogTone' => $this->blogTone->value,
                'inputLanguage' => $this->inputLanguage->value,
                'blogLanguage' => $this->blogLanguage->value,
                'writerPointOfView' => $this->writerPointOfView->value,
            ]
        );

    }
}

function create_blog(string $baseUrl, string $access_token, BlogCreateModel $model): string
{
    $response = wp_remote_post(
        $baseUrl . '/blogs',
        array(
            'body' => $model->toJSON(),
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $access_token),
                'Content-Type' => 'application/json',
            ],
        )
    );

    if (is_wp_error($response)) {
        throw new \Exception($response->get_error_message(), $response->get_error_code);
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception('Failed to decode response data');
    }

    return $data['_id'] ?? throw new \Exception('Blog ID not found in response: ' . $data['message']);

}
*/

function get_blog_details(string $baseUrl, string $access_token, string $blogId): BlogDetails
{
    $response = wp_remote_get(
        $baseUrl . "/blogs/" . $blogId,
        [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $access_token),
            ],
        ]
    );

    if (is_wp_error($response)) {
        throw new \Exception($response->get_error_message(), $response->get_error_code());
    }

    $body = wp_remote_retrieve_body($response);
    $json = json_decode($body);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception('Failed to decode response data');
    }

    return $json;
}    

