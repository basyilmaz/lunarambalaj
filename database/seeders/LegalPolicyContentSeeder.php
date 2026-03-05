<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class LegalPolicyContentSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->policies() as $key => $translations) {
            $page = Page::query()->where('key', $key)->orWhere('type', $key)->first() ?: new Page();
            $page->fill(['key' => $key, 'type' => $key, 'is_published' => true])->save();

            foreach ($translations as $lang => $content) {
                $page->translations()->updateOrCreate(
                    ['lang' => $lang],
                    [
                        'title' => $content['title'],
                        'slug' => $content['slug'],
                        'body' => $content['body'],
                        'seo_title' => mb_substr($content['seo_title'], 0, 60),
                        'seo_desc' => mb_substr($content['seo_desc'], 0, 160),
                    ],
                );
            }
        }
    }

    private function policies(): array
    {
        return [
            'kvkk' => [
                'tr' => [
                    'title' => 'KVKK Aydınlatma Metni',
                    'slug' => 'kvkk',
                    'seo_title' => 'KVKK Aydınlatma Metni | Lunar Ambalaj',
                    'seo_desc' => 'Kişisel veri kategorileri, işleme amaçları, aktarım, saklama ve haklar hakkında bilgilendirme.',
                    'body' => $this->kvkkBody('tr'),
                ],
                'en' => [
                    'title' => 'Privacy Notice (KVKK)',
                    'slug' => 'kvkk',
                    'seo_title' => 'Privacy Notice (KVKK) | Lunar Packaging',
                    'seo_desc' => 'Privacy Notice covering data categories, purposes, transfer, retention and user rights.',
                    'body' => $this->kvkkBody('en'),
                ],
                'ru' => [
                    'title' => 'Уведомление о защите данных (KVKK)',
                    'slug' => 'kvkk',
                    'seo_title' => 'Уведомление о защите данных (KVKK)',
                    'seo_desc' => 'Категории данных, цели обработки, передача, хранение и права субъекта данных.',
                    'body' => $this->kvkkBody('ru'),
                ],
                'ar' => [
                    'title' => 'إشعار حماية البيانات (KVKK)',
                    'slug' => 'kvkk',
                    'seo_title' => 'إشعار حماية البيانات (KVKK) | Lunar Ambalaj',
                    'seo_desc' => 'إشعار حماية البيانات: فئات البيانات وأغراض المعالجة والنقل والحفظ وحقوق صاحب البيانات.',
                    'body' => $this->kvkkBody('ar'),
                ],
            ],
            'privacy' => [
                'tr' => [
                    'title' => 'Gizlilik Politikası',
                    'slug' => 'gizlilik-politikasi',
                    'seo_title' => 'Gizlilik Politikası | Lunar Ambalaj',
                    'seo_desc' => 'Gizlilik politikası: veri güvenliği, kullanım amacı, aktarım yaklaşımı ve iletişim kanalı.',
                    'body' => $this->privacyBody('tr'),
                ],
                'en' => [
                    'title' => 'Privacy Policy',
                    'slug' => 'privacy-policy',
                    'seo_title' => 'Privacy Policy | Lunar Packaging',
                    'seo_desc' => 'Privacy Policy: data security, usage purposes, transfer approach and contact.',
                    'body' => $this->privacyBody('en'),
                ],
                'ru' => [
                    'title' => 'Политика конфиденциальности',
                    'slug' => 'privacy-policy',
                    'seo_title' => 'Политика конфиденциальности | Lunar',
                    'seo_desc' => 'Политика конфиденциальности: безопасность, цели обработки, передача, хранение и права.',
                    'body' => $this->privacyBody('ru'),
                ],
                'ar' => [
                    'title' => 'سياسة الخصوصية',
                    'slug' => 'privacy-policy',
                    'seo_title' => 'سياسة الخصوصية | Lunar Ambalaj',
                    'seo_desc' => 'سياسة الخصوصية: الأمان وأغراض المعالجة ونقل البيانات والاحتفاظ بها وحقوق المستخدم.',
                    'body' => $this->privacyBody('ar'),
                ],
            ],
            'cookie' => [
                'tr' => [
                    'title' => 'Çerez Politikası',
                    'slug' => 'cerez-politikasi',
                    'seo_title' => 'Çerez Politikası | Lunar Ambalaj',
                    'seo_desc' => 'Zorunlu, analitik, pazarlama ve üçüncü taraf çerezlerine ilişkin bilgilendirme.',
                    'body' => $this->cookieBody('tr'),
                ],
                'en' => [
                    'title' => 'Cookie Policy',
                    'slug' => 'cookie-policy',
                    'seo_title' => 'Cookie Policy | Lunar Packaging',
                    'seo_desc' => 'Cookie categories, usage purposes and browser-level cookie management.',
                    'body' => $this->cookieBody('en'),
                ],
                'ru' => [
                    'title' => 'Политика cookie',
                    'slug' => 'cookie-policy',
                    'seo_title' => 'Политика cookie | Lunar',
                    'seo_desc' => 'Типы cookie, цели использования и управление через настройки браузера.',
                    'body' => $this->cookieBody('ru'),
                ],
                'ar' => [
                    'title' => 'سياسة ملفات تعريف الارتباط',
                    'slug' => 'cookie-policy',
                    'seo_title' => 'سياسة ملفات تعريف الارتباط | Lunar',
                    'seo_desc' => 'أنواع ملفات تعريف الارتباط وأغراضها وإدارتها من خلال إعدادات المتصفح.',
                    'body' => $this->cookieBody('ar'),
                ],
            ],
            'distance_sales' => [
                'tr' => [
                    'title' => 'Mesafeli Satış Sözleşmesi',
                    'slug' => 'mesafeli-satis-sozlesmesi',
                    'seo_title' => 'Mesafeli Satış Sözleşmesi | Lunar Ambalaj',
                    'seo_desc' => 'Teklif ve sipariş süreci, teslimat yaklaşımı ve özel üretim istisnası hakkında bilgilendirme.',
                    'body' => $this->distanceSalesBody('tr'),
                ],
                'en' => [
                    'title' => 'Distance Sales Contract',
                    'slug' => 'distance-sales-contract',
                    'seo_title' => 'Distance Sales Contract | Lunar Packaging',
                    'seo_desc' => 'Informational text for quotation-based sales and custom production withdrawal notes.',
                    'body' => $this->distanceSalesBody('en'),
                ],
                'ru' => [
                    'title' => 'Договор дистанционной продажи',
                    'slug' => 'distance-sales-contract',
                    'seo_title' => 'Договор дистанционной продажи | Lunar',
                    'seo_desc' => 'Информационный договор о порядке заказа, доставке и индивидуальном производстве.',
                    'body' => $this->distanceSalesBody('ru'),
                ],
                'ar' => [
                    'title' => 'عقد البيع عن بُعد',
                    'slug' => 'distance-sales-contract',
                    'seo_title' => 'عقد البيع عن بُعد | Lunar Ambalaj',
                    'seo_desc' => 'نص معلوماتي عن آلية الطلب والتسليم وبند الإنتاج المخصص في البيع عن بُعد.',
                    'body' => $this->distanceSalesBody('ar'),
                ],
            ],
            'terms' => [
                'tr' => [
                    'title' => 'Kullanım Şartları',
                    'slug' => 'kullanim-sartlari',
                    'seo_title' => 'Kullanım Şartları | Lunar Ambalaj',
                    'seo_desc' => 'Site kullanım şartları, fikri mülkiyet hükümleri ve sorumluluk sınırları.',
                    'body' => $this->termsBody('tr'),
                ],
                'en' => [
                    'title' => 'Terms of Use',
                    'slug' => 'terms-of-use',
                    'seo_title' => 'Terms of Use | Lunar Packaging',
                    'seo_desc' => 'Website terms of use, intellectual property rules and liability boundaries.',
                    'body' => $this->termsBody('en'),
                ],
                'ru' => [
                    'title' => 'Условия использования',
                    'slug' => 'terms-of-use',
                    'seo_title' => 'Условия использования | Lunar',
                    'seo_desc' => 'Условия использования сайта, интеллектуальная собственность и ограничения ответственности.',
                    'body' => $this->termsBody('ru'),
                ],
                'ar' => [
                    'title' => 'شروط الاستخدام',
                    'slug' => 'terms-of-use',
                    'seo_title' => 'شروط الاستخدام | Lunar Ambalaj',
                    'seo_desc' => 'شروط استخدام الموقع بما يشمل الملكية الفكرية وحدود المسؤولية.',
                    'body' => $this->termsBody('ar'),
                ],
            ],
        ];
    }

    private function kvkkBody(string $lang): string
    {
        return match ($lang) {
            'tr' => '<p>Bu metin, web sitesi ve iletişim kanalları üzerinden toplanan kişisel verilerin işlenmesine ilişkin bilgilendirme sağlar.</p><h2>1. Veri Sorumlusu</h2><p><strong>Lunar Ambalaj - Tulgahan Yılkın</strong></p><h2>2. Kişisel Veri Kategorileri</h2><ul><li>ad soyad</li><li>telefon</li><li>e-posta</li><li>şirket adı</li><li>mesaj / teklif detayları</li><li>IP adresi</li><li>cihaz bilgileri</li><li>çerez verileri</li></ul><h2>3. İşleme Amaçları</h2><ul><li>teklif taleplerinin yönetimi</li><li>müşteri iletişimi</li><li>hizmet süreçleri</li><li>web güvenliği</li><li>hukuki yükümlülükler</li><li>analitik ve hizmet geliştirme</li><li>açık rıza varsa pazarlama</li></ul><h2>4. Veri Aktarımı</h2><p>Veriler gerekli ölçüde hosting sağlayıcıları, e-posta sağlayıcıları ve yasal yetkili kurumlarla paylaşılabilir.</p><h2>5. Saklama</h2><p>Kişisel veriler yalnızca işleme amacı ve yasal yükümlülüklerin gerektirdiği süre boyunca saklanır. Sabit bir süre garantisi verilmez.</p><h2>6. Haklar</h2><p>KVKK madde 11’e benzer haklar kapsamında erişim, düzeltme, silme ve itiraz talepleri iletilebilir.</p><h2>7. İletişim</h2><p><a href="mailto:info@lunarambalaj.com.tr">info@lunarambalaj.com.tr</a></p>',
            'ru' => '<p>Настоящий текст информирует о порядке обработки персональных данных через сайт и каналы связи.</p><h2>1. Оператор данных</h2><p><strong>Lunar Ambalaj - Tulgahan Yılkın</strong></p><h2>2. Категории данных</h2><ul><li>имя и фамилия</li><li>телефон</li><li>e-mail</li><li>название компании</li><li>сообщение / детали запроса</li><li>IP-адрес</li><li>информация об устройстве</li><li>данные cookie</li></ul><h2>3. Цели обработки</h2><ul><li>подготовка коммерческих предложений</li><li>коммуникация с клиентами</li><li>сервисные процессы</li><li>безопасность сайта</li><li>юридические обязанности</li><li>аналитика и улучшение сервиса</li><li>маркетинг только при согласии</li></ul><h2>4. Передача данных</h2><p>Данные могут передаваться хостинг-провайдерам, почтовым сервисам и уполномоченным государственным органам в необходимом объеме.</p><h2>5. Хранение</h2><p>Данные хранятся только столько, сколько требуется для целей обработки и соблюдения закона. Фиксированный срок не гарантируется.</p><h2>6. Права</h2><p>Пользователь может запросить доступ, исправление, удаление и возражение в рамках прав, аналогичных статье 11 KVKK.</p><h2>7. Контакт</h2><p><a href="mailto:info@lunarambalaj.com.tr">info@lunarambalaj.com.tr</a></p>',
            'ar' => '<p>يوضح هذا النص كيفية معالجة البيانات الشخصية عبر الموقع وقنوات التواصل.</p><h2>1. مسؤول البيانات</h2><p><strong>Lunar Ambalaj - Tulgahan Yılkın</strong></p><h2>2. فئات البيانات</h2><ul><li>الاسم واللقب</li><li>الهاتف</li><li>البريد الإلكتروني</li><li>اسم الشركة</li><li>تفاصيل الرسالة / العرض</li><li>عنوان IP</li><li>معلومات الجهاز</li><li>بيانات ملفات تعريف الارتباط</li></ul><h2>3. أغراض المعالجة</h2><ul><li>إدارة طلبات عروض الأسعار</li><li>التواصل مع العملاء</li><li>تنفيذ عمليات الخدمة</li><li>حماية أمن الموقع</li><li>الالتزامات القانونية</li><li>التحليلات وتحسين الخدمة</li><li>التسويق فقط عند وجود موافقة</li></ul><h2>4. نقل البيانات</h2><p>قد تُنقل البيانات بالقدر اللازم إلى مزودي الاستضافة والبريد الإلكتروني والجهات العامة المخولة قانونًا.</p><h2>5. الاحتفاظ</h2><p>تُحفظ البيانات فقط للمدة اللازمة لأغراض المعالجة والالتزامات القانونية، دون ضمان مدة ثابتة لكل حالة.</p><h2>6. الحقوق</h2><p>يمكن لصاحب البيانات طلب الوصول والتصحيح والحذف والاعتراض وفق حقوق مماثلة للمادة 11 من KVKK.</p><h2>7. التواصل</h2><p><a href="mailto:info@lunarambalaj.com.tr">info@lunarambalaj.com.tr</a></p>',
            default => '<p>This notice explains how personal data is processed through website forms and communication channels.</p><h2>1. Data Controller</h2><p><strong>Lunar Ambalaj - Tulgahan Yılkın</strong></p><h2>2. Personal Data Categories</h2><ul><li>name surname</li><li>phone</li><li>email</li><li>company name</li><li>message / quote details</li><li>IP address</li><li>device information</li><li>cookie data</li></ul><h2>3. Processing Purposes</h2><ul><li>quotation requests</li><li>customer communication</li><li>service processes</li><li>website security</li><li>legal obligations</li><li>analytics and service improvement</li><li>marketing only if consent exists</li></ul><h2>4. Data Transfer</h2><p>Data may be transferred, where necessary, to hosting providers, email providers and legally authorized public institutions.</p><h2>5. Retention</h2><p>Personal data is stored only as long as required by legal obligations and processing purposes. No exact period is guaranteed.</p><h2>6. User Rights</h2><p>Users may request access, correction, deletion and objection under rights similar to KVKK Article 11.</p><h2>7. Contact</h2><p><a href="mailto:info@lunarambalaj.com.tr">info@lunarambalaj.com.tr</a></p>',
        };
    }

    private function privacyBody(string $lang): string
    {
        return match ($lang) {
            'tr' => '<p>Bu gizlilik politikası, kişisel verilerin işlenmesi ve korunmasına dair genel çerçeveyi sunar.</p>' . $this->kvkkBody('tr'),
            'ru' => '<p>Настоящая политика конфиденциальности описывает принципы защиты и обработки персональных данных.</p>' . $this->kvkkBody('ru'),
            'ar' => '<p>توضح سياسة الخصوصية هذه المبادئ العامة لحماية البيانات الشخصية ومعالجتها.</p>' . $this->kvkkBody('ar'),
            default => '<p>This privacy policy describes the principles for processing and protecting personal data.</p>' . $this->kvkkBody('en'),
        };
    }

    private function cookieBody(string $lang): string
    {
        return match ($lang) {
            'tr' => '<p>Bu politika, sitede kullanılan çerez türlerini ve yönetim yöntemlerini açıklar.</p><h2>1. Çerez Türleri</h2><ul><li>zorunlu çerezler</li><li>analitik çerezler</li><li>pazarlama çerezleri</li><li>üçüncü taraf çerezleri</li></ul><h2>2. Kullanım Amaçları</h2><p>Site güvenliği, performans ölçümü ve kullanıcı deneyimi iyileştirmesi.</p><h2>3. Çerez Yönetimi</h2><p>Çerez tercihleri tarayıcı ayarlarından yönetilebilir.</p>',
            'ru' => '<p>Данная политика объясняет используемые cookie и способы управления ими.</p><h2>1. Типы cookie</h2><ul><li>обязательные</li><li>аналитические</li><li>маркетинговые</li><li>сторонние</li></ul><h2>2. Цели использования</h2><p>Безопасность сайта, аналитика производительности и улучшение пользовательского опыта.</p><h2>3. Управление cookie</h2><p>Настройки cookie можно изменить в браузере.</p>',
            'ar' => '<p>توضح هذه السياسة أنواع ملفات تعريف الارتباط المستخدمة وطرق إدارتها.</p><h2>1. أنواع الملفات</h2><ul><li>ملفات أساسية</li><li>ملفات تحليلية</li><li>ملفات تسويقية</li><li>ملفات طرف ثالث</li></ul><h2>2. أغراض الاستخدام</h2><p>أمن الموقع، قياس الأداء، وتحسين تجربة المستخدم.</p><h2>3. الإدارة</h2><p>يمكن إدارة ملفات تعريف الارتباط عبر إعدادات المتصفح.</p>',
            default => '<p>This policy explains cookie types used on the website and how to manage preferences.</p><h2>1. Cookie Types</h2><ul><li>essential cookies</li><li>analytics cookies</li><li>marketing cookies</li><li>third-party cookies</li></ul><h2>2. Purposes</h2><p>Website security, performance analytics and user experience improvement.</p><h2>3. Management</h2><p>Cookie preferences can be managed via browser settings.</p>',
        };
    }

    private function distanceSalesBody(string $lang): string
    {
        return match ($lang) {
            'tr' => '<p><strong>Web sitemiz üzerinden doğrudan online satış yapılmamaktadır. Bu sözleşme bilgilendirme amaçlıdır.</strong></p><h2>1. Sipariş Süreci</h2><p>İşlem akışı teklif onayı, üretim planı ve yazılı mutabakat ile ilerler.</p><h2>2. Teslimat</h2><p>Teslimat koşulları ürün, adet ve planlama durumuna göre değişebilir.</p><h2>3. Özel Üretim İstisnası</h2><p>Özel baskı, markalama veya kişiselleştirilmiş teknik özellik içeren ürünler bazı durumlarda standart cayma hakkı kapsamı dışında değerlendirilebilir. Bu ifade mutlak hukuki garanti anlamına gelmez.</p><h2>4. İletişim</h2><p><a href="mailto:info@lunarambalaj.com.tr">info@lunarambalaj.com.tr</a></p>',
            'ru' => '<p><strong>Наш сайт не осуществляет прямые онлайн-продажи или платежи. Этот договор предоставлен только в информационных целях.</strong></p><h2>1. Процесс заказа</h2><p>Работа строится через запрос, согласование предложения и письменное подтверждение.</p><h2>2. Доставка</h2><p>Условия доставки зависят от типа товара, объема и производственного плана.</p><h2>3. Индивидуальное производство</h2><p>Товары с индивидуальной печатью или персональными характеристиками в отдельных случаях могут не подпадать под стандартные правила возврата. Это не является абсолютной юридической гарантией.</p><h2>4. Контакт</h2><p><a href="mailto:info@lunarambalaj.com.tr">info@lunarambalaj.com.tr</a></p>',
            'ar' => '<p><strong>لا تتم عبر موقعنا مبيعات أو مدفوعات مباشرة عبر الإنترنت. يُقدَّم هذا العقد لأغراض معلوماتية فقط.</strong></p><h2>1. آلية الطلب</h2><p>تتم العملية عبر طلب عرض ثم اعتماد الشروط كتابيًا قبل التنفيذ.</p><h2>2. التسليم</h2><p>قد تختلف شروط التسليم بحسب نوع المنتج والكمية وخطة الإنتاج.</p><h2>3. استثناء الإنتاج المخصص</h2><p>قد لا تندرج المنتجات ذات الطباعة الخاصة أو المواصفات الشخصية ضمن حقوق الانسحاب القياسية في بعض الحالات. لا يُعد ذلك ضمانًا قانونيًا مطلقًا.</p><h2>4. التواصل</h2><p><a href="mailto:info@lunarambalaj.com.tr">info@lunarambalaj.com.tr</a></p>',
            default => '<p><strong>Our website does not process direct online sales or payments. This contract is provided for informational purposes only.</strong></p><h2>1. Order Workflow</h2><p>The process proceeds through quotation approval, production planning and written confirmation.</p><h2>2. Delivery</h2><p>Delivery conditions may vary depending on product type, quantity and planning.</p><h2>3. Custom Production Exception</h2><p>Products with custom printing, branding or personalized specifications may not fall within standard withdrawal rights in some cases. This is not an absolute legal guarantee.</p><h2>4. Contact</h2><p><a href="mailto:info@lunarambalaj.com.tr">info@lunarambalaj.com.tr</a></p>',
        };
    }

    private function termsBody(string $lang): string
    {
        return match ($lang) {
            'tr' => '<p>Bu koşullar, web sitesinin kullanımına ilişkin genel çerçeveyi düzenler.</p><h2>1. Kullanım</h2><p>Siteyi kullanan kişiler bu koşulları kabul etmiş sayılır.</p><h2>2. Fikri Mülkiyet</h2><ul><li>Metin, görsel ve logolar koruma altındadır.</li><li>İzinsiz kopyalama ve ticari kullanım yapılamaz.</li></ul><h2>3. Sorumluluk</h2><p>Site içeriği bilgilendirme amaçlıdır; teknik kesinti ve üçüncü taraf bağlantılarından doğan dolaylı sonuçlar için sınırlı sorumluluk geçerli olabilir.</p><h2>4. İletişim</h2><p><a href="mailto:info@lunarambalaj.com.tr">info@lunarambalaj.com.tr</a></p>',
            'ru' => '<p>Настоящие условия регулируют общее использование сайта.</p><h2>1. Использование</h2><p>Пользователь, использующий сайт, принимает данные условия.</p><h2>2. Интеллектуальная собственность</h2><ul><li>Тексты, изображения и логотипы защищены законом.</li><li>Копирование и коммерческое использование без разрешения запрещены.</li></ul><h2>3. Ответственность</h2><p>Сайт носит информационный характер; ответственность за косвенные последствия технических сбоев или сторонних ссылок может быть ограничена.</p><h2>4. Контакт</h2><p><a href="mailto:info@lunarambalaj.com.tr">info@lunarambalaj.com.tr</a></p>',
            'ar' => '<p>تنظم هذه الشروط الإطار العام لاستخدام الموقع.</p><h2>1. الاستخدام</h2><p>يُعد استخدام الموقع موافقة على هذه الشروط.</p><h2>2. الملكية الفكرية</h2><ul><li>النصوص والصور والشعارات محمية قانونيًا.</li><li>يُمنع النسخ أو الاستخدام التجاري دون إذن.</li></ul><h2>3. المسؤولية</h2><p>المحتوى لغرض المعلومات العامة؛ وقد تكون المسؤولية محدودة عن النتائج غير المباشرة الناتجة عن الأعطال التقنية أو روابط الطرف الثالث.</p><h2>4. التواصل</h2><p><a href="mailto:info@lunarambalaj.com.tr">info@lunarambalaj.com.tr</a></p>',
            default => '<p>These terms define the general framework for website use.</p><h2>1. Use</h2><p>By using the website, users are deemed to accept these terms.</p><h2>2. Intellectual Property</h2><ul><li>Texts, visuals and logos are legally protected.</li><li>Unauthorized copying or commercial use is prohibited.</li></ul><h2>3. Liability</h2><p>Website content is informational; liability for indirect outcomes of technical interruptions or third-party links may be limited.</p><h2>4. Contact</h2><p><a href="mailto:info@lunarambalaj.com.tr">info@lunarambalaj.com.tr</a></p>',
        };
    }
}

