<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class LegalPolicyContentSeeder extends Seeder
{
    public function run(): void
    {
        $policies = [
            'kvkk' => [
                'tr' => [
                    'title' => 'KVKK Aydınlatma Metni',
                    'slug' => 'kvkk',
                    'seo_title' => 'KVKK Aydınlatma Metni | Lunar Ambalaj',
                    'seo_desc' => 'Lunar Ambalaj KVKK aydınlatma metni: veri işleme amaçları, hukuki sebepler, aktarım ve başvuru hakları.',
                    'body' => <<<'TEXT'
1. Veri Sorumlusu
Lunar Ambalaj olarak, 6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") kapsamında veri sorumlusu sıfatıyla hareket ediyoruz.

2. İşlenen Veri Kategorileri
Teklif ve iletişim süreçlerinde ad-soyad, şirket, telefon, e-posta, mesaj içeriği, teslimat şehir bilgisi, talep edilen ürün kategorisi ve miktar bilgisi gibi veriler işlenebilir.
Site kullanımında çerez ve trafik kayıtları kapsamında IP, tarayıcı bilgisi, sayfa geçmişleri ve UTM parametreleri teknik olarak kaydedilebilir.

3. İşleme Amaçları
Veriler; teklif oluşturma, sipariş öncesi teknik değerlendirme, müşteri iletişim sürecini yürütme, talep ve şikayet yönetimi, operasyonel planlama, bilgi güvenliği ve mevzuata uyum amaçlarıyla işlenir.

4. Hukuki Sebepler
KVKK m.5/2 kapsaminda:
- Bir sözleşmenin kurulması veya ifasıyla doğrudan ilgili olması,
- Veri sorumlusunun hukuki yükümlülüğünü yerine getirmesi,
- Bir hakkın tesisi, kullanılması veya korunması,
- Temel hak ve özgürlüklere zarar vermemek kaydıyla meşru menfaat sebeplerine dayanılır.
Açık rıza gereken hallerde ayrıca açık rıza alınabilir.

5. Aktarım
Kişisel veriler, sadece gerekli olduğu ölçüde; barındırma ve e-posta altyapısı sağlayıcıları, bilgi teknolojileri destek hizmetleri, hukuki/mali danışmanlar ve yasal olarak yetkili kamu kurumları ile paylaşılabilir.
Veriler reklam amaçlı üçüncü taraflara satılmaz.

6. Toplama Yöntemi
Veriler; web formları, e-posta, telefon, WhatsApp iletişim kanalları ve çerezler aracılığıyla otomatik veya kısmen otomatik yollarla toplanır.

7. Saklama Süreleri
Veriler ilgili mevzuatın öngördüğü veya işleme amacının gerektirdiği süre boyunca saklanır; süre sonunda silinir, yok edilir veya anonim hale getirilir.

8. Başvuru Hakları
KVKK m.11 kapsamındaki haklarınız (veri işlenip işlenmediğini öğrenme, düzeltme, silme, itiraz vb.) için:
E-posta: info@lunarambalaj.com.tr
Adres: Yenidogan Mah. Bahcelievler Cad. No: 2 Kat: 2 D: 35 Plaza Kati, 34652 Sancaktepe / Istanbul

Yürürlük Tarihi: 01.01.2026
Son Güncelleme: 01.03.2026
TEXT,
                ],
                'en' => [
                    'title' => 'Personal Data Notice (KVKK)',
                    'slug' => 'kvkk',
                    'seo_title' => 'Personal Data Notice | Lunar Packaging',
                    'seo_desc' => 'Lunar Packaging personal data notice: processing purposes, legal bases, transfers and data subject rights.',
                    'body' => <<<'TEXT'
1. Data Controller
Lunar Packaging acts as the data controller under Turkish Personal Data Protection Law No. 6698 ("KVKK").

2. Categories of Data Processed
During quote and contact workflows, we may process name, company, phone, email, message content, delivery city, requested product category and quantity.
During website usage, technical records such as IP, browser details, visited pages and UTM parameters may be logged via cookies and analytics tools.

3. Purposes of Processing
Data is processed to prepare quotations, evaluate pre-order technical requirements, manage customer communication, handle requests/complaints, support operations, ensure information security and comply with legal obligations.

4. Legal Bases
Under Article 5/2 of KVKK, processing may rely on:
- Necessity for contract establishment/performance,
- Compliance with legal obligations,
- Establishment, exercise or protection of legal rights,
- Legitimate interests of the data controller, provided fundamental rights are not harmed.
Where required, explicit consent is requested.

5. Transfers
Data may be shared only as necessary with hosting and email infrastructure providers, IT support providers, legal/financial advisors and legally authorized public institutions.
Personal data is not sold to third parties for advertising purposes.

6. Collection Methods
Data is collected through website forms, email, phone, WhatsApp and cookies by automated or partially automated means.

7. Retention
Data is retained for the period required by applicable laws or by processing purposes, then deleted, destroyed or anonymized.

8. Data Subject Rights
For rights under KVKK Article 11 (access, correction, deletion, objection, etc.):
Email: info@lunarambalaj.com.tr
Address: Yenidogan Mah. Bahcelievler Cad. No: 2 Kat: 2 D: 35 Plaza Kati, 34652 Sancaktepe / Istanbul

Effective Date: 2026-01-01
Last Updated: 2026-03-01
TEXT,
                ],
                'ru' => [
                    'title' => 'Уведомление о персональных данных (KVKK)',
                    'slug' => 'kvkk',
                    'seo_title' => 'Уведомление о персональных данных | Lunar Packaging',
                    'seo_desc' => 'Уведомление Lunar Packaging: цели обработки, правовые основания, передача данных и права субъекта данных.',
                    'body' => <<<'TEXT'
1. Оператор данных
Lunar Packaging выступает оператором персональных данных в соответствии с Законом Турции №6698 (KVKK).

2. Категории обрабатываемых данных
При запросе коммерческого предложения и в контактных формах могут обрабатываться: имя, компания, телефон, e-mail, содержание сообщения, город поставки, категория продукции и объем.
При использовании сайта технически могут фиксироваться IP, данные браузера, посещенные страницы и UTM-параметры.

3. Цели обработки
Подготовка предложений, техническая оценка до заказа, коммуникация с клиентом, обработка запросов и претензий, операционное планирование, информационная безопасность и соблюдение законодательства.

4. Правовые основания
Обработка осуществляется на основаниях ст.5/2 KVKK:
- необходимость для заключения/исполнения договора,
- исполнение юридических обязанностей,
- установление/осуществление/защита прав,
- законные интересы оператора при соблюдении прав субъекта данных.
В случаях, когда это требуется, запрашивается явное согласие.

5. Передача данных
Данные могут передаваться только при необходимости поставщикам хостинга и e-mail инфраструктуры, IT-поддержке, юридическим/финансовым консультантам и уполномоченным госорганам.
Данные не продаются третьим лицам в рекламных целях.

6. Способы сбора
Данные собираются через формы сайта, e-mail, телефон, WhatsApp и cookie автоматизированными или частично автоматизированными способами.

7. Сроки хранения
Данные хранятся в течение сроков, предусмотренных законодательством или целями обработки, после чего удаляются, уничтожаются или обезличиваются.

8. Права субъекта данных
Для реализации прав по ст.11 KVKK (доступ, исправление, удаление, возражение и др.):
E-mail: info@lunarambalaj.com.tr
Адрес: Yenidogan Mah. Bahcelievler Cad. No: 2 Kat: 2 D: 35 Plaza Kati, 34652 Sancaktepe / Istanbul

Дата вступления: 2026-01-01
Последнее обновление: 2026-03-01
TEXT,
                ],
                'ar' => [
                    'title' => 'إشعار حماية البيانات (KVKK)',
                    'slug' => 'kvkk',
                    'seo_title' => 'إشعار حماية البيانات | Lunar Packaging',
                    'seo_desc' => 'إشعار حماية البيانات لدى Lunar Packaging: أغراض المعالجة والأساس القانوني وحقوق صاحب البيانات.',
                    'body' => <<<'TEXT'
1. مسؤول البيانات
تعمل Lunar Packaging بصفتها مسؤول البيانات وفق قانون حماية البيانات الشخصية التركي رقم 6698 (KVKK).

2. فئات البيانات المعالجة
في نماذج التواصل وطلب السعر قد تتم معالجة: الاسم، الشركة، الهاتف، البريد الإلكتروني، محتوى الرسالة، مدينة التسليم، فئة المنتج والكمية.
وأثناء استخدام الموقع قد يتم تسجيل بيانات تقنية مثل عنوان IP، نوع المتصفح، الصفحات التي تمت زيارتها ومعلمات UTM.

3. أغراض المعالجة
إعداد عروض الأسعار، التقييم الفني قبل الطلب، إدارة التواصل مع العملاء، معالجة الطلبات والشكاوى، التخطيط التشغيلي، أمن المعلومات والالتزام بالمتطلبات القانونية.

4. الأساس القانوني
تتم المعالجة وفق المادة 5/2 من KVKK بناء على:
- ضرورة المعالجة لإبرام/تنفيذ العقد،
- الوفاء بالالتزامات القانونية،
- إنشاء الحقوق أو ممارستها أو حمايتها،
- المصلحة المشروعة لمسؤول البيانات دون الإخلال بالحقوق الأساسية.
وعند الحاجة يتم الحصول على موافقة صريحة.

5. نقل البيانات
قد تتم مشاركة البيانات عند الضرورة فقط مع مزودي الاستضافة والبريد الإلكتروني، مزودي الدعم التقني، المستشارين القانونيين/الماليين والجهات الرسمية المخولة.
لا يتم بيع البيانات الشخصية لأطراف ثالثة لأغراض إعلانية.

6. طرق جمع البيانات
تُجمع البيانات عبر نماذج الموقع، البريد الإلكتروني، الهاتف، واتساب وملفات تعريف الارتباط بطرق آلية أو شبه آلية.

7. مدة الاحتفاظ
تُحتفظ البيانات للمدة التي يفرضها القانون أو التي تتطلبها أغراض المعالجة، ثم تُحذف أو تُتلف أو تُجهّل.

8. حقوق صاحب البيانات
لممارسة الحقوق المنصوص عليها في المادة 11 من KVKK (الاطلاع، التصحيح، الحذف، الاعتراض...):
البريد الإلكتروني: info@lunarambalaj.com.tr
العنوان: Yenidogan Mah. Bahcelievler Cad. No: 2 Kat: 2 D: 35 Plaza Kati, 34652 Sancaktepe / Istanbul

تاريخ النفاذ: 2026-01-01
آخر تحديث: 2026-03-01
TEXT,
                ],
            ],
            'cookie' => [
                'tr' => [
                    'title' => 'Çerez Politikası',
                    'slug' => 'cerez-politikasi',
                    'seo_title' => 'Çerez Politikası | Lunar Ambalaj',
                    'seo_desc' => 'Lunar Ambalaj çerez politikası: zorunlu, performans ve analiz çerezleri ile yönetim tercihleri.',
                    'body' => <<<'TEXT'
Bu Çerez Politikası, lunarambalaj.com.tr üzerinde kullanılan çerez türlerini ve tercih yönetimini açıklar.

1. Çerez Nedir?
Çerezler, site deneyimini iyileştirmek ve teknik çalışmayı sürdürmek için tarayıcınıza kaydedilen küçük veri dosyalarıdır.

2. Kullandığımız Çerezler
- Zorunlu Çerezler: Oturum yönetimi, güvenlik ve temel site fonksiyonları.
- Performans ve Analiz Çerezleri: Sayfa performansı, trafik ölçümü ve kullanıcı davranışı analizi.
- Pazarlama/Ölçüm Çerezleri: Kampanya performansı ölçümleri (GTM, pixel ve benzeri araçlar etkinse).

3. Çerezlerin Amaçları
- Siteyi güvenli ve stabil çalıştırmak,
- Form süreçlerini ve kullanıcı deneyimini iyileştirmek,
- Reklam ve kampanya performansını ölçmek.

4. Çerez Tercihleri
Tarayıcı ayarlarınızdan çerezleri silebilir, engelleyebilir veya sınırlandırabilirsiniz. Bazı çerezleri kapatmak site fonksiyonlarının bir kısmını etkileyebilir.

5. Üçüncü Taraf Araçlar
Site, analitik veya reklam ölçümü amacıyla üçüncü taraf servisler (örneğin Google ve Meta araçları) kullanabilir. Bu servisler kendi gizlilik politikalarına tabidir.

Son Güncelleme: 01.03.2026
TEXT,
                ],
                'en' => [
                    'title' => 'Cookie Policy',
                    'slug' => 'cookie-policy',
                    'seo_title' => 'Cookie Policy | Lunar Packaging',
                    'seo_desc' => 'Cookie policy for lunarambalaj.com.tr: essential, analytics and marketing cookies and preference management.',
                    'body' => <<<'TEXT'
This Cookie Policy explains cookie usage and preference controls on lunarambalaj.com.tr.

1. What Is a Cookie?
Cookies are small data files stored in your browser to ensure technical operation and improve user experience.

2. Types of Cookies We Use
- Essential Cookies: Session, security and core website functions.
- Performance & Analytics Cookies: Traffic analysis, performance and user behavior metrics.
- Marketing/Measurement Cookies: Campaign performance measurement (when GTM, pixel or similar tools are enabled).

3. Purposes
- To keep the website secure and functional,
- To improve forms and navigation experience,
- To measure campaign and advertising performance.

4. Managing Cookies
You may delete, block or restrict cookies through browser settings. Disabling certain cookies may affect some site features.

5. Third-Party Tools
The website may use third-party analytics/advertising tools (such as Google or Meta services). Such tools are subject to their own privacy terms.

Last Updated: 2026-03-01
TEXT,
                ],
                'ru' => [
                    'title' => 'Политика Cookie',
                    'slug' => 'cookie-policy',
                    'seo_title' => 'Политика Cookie | Lunar Packaging',
                    'seo_desc' => 'Политика Cookie сайта lunarambalaj.com.tr: обязательные, аналитические и маркетинговые cookie.',
                    'body' => <<<'TEXT'
Данная Политика Cookie описывает использование файлов cookie на lunarambalaj.com.tr и управление предпочтениями.

1. Что такое cookie?
Cookie — это небольшие файлы, сохраняемые в браузере для технической работы сайта и улучшения пользовательского опыта.

2. Какие cookie используются
- Обязательные: сессия, безопасность и базовые функции сайта.
- Аналитические: измерение трафика, производительности и поведения пользователей.
- Маркетинговые/измерительные: оценка эффективности кампаний (при включенных GTM, pixel и аналогах).

3. Цели использования
- Обеспечение безопасности и корректной работы сайта,
- Улучшение форм и пользовательского пути,
- Измерение рекламной и кампанийной эффективности.

4. Управление cookie
Вы можете удалить, ограничить или заблокировать cookie в настройках браузера. Отключение некоторых cookie может повлиять на функциональность сайта.

5. Сторонние сервисы
Сайт может использовать сторонние аналитические/рекламные сервисы (например, Google и Meta). Такие сервисы регулируются их собственными политиками.

Последнее обновление: 2026-03-01
TEXT,
                ],
                'ar' => [
                    'title' => 'سياسة ملفات الارتباط',
                    'slug' => 'cookie-policy',
                    'seo_title' => 'سياسة ملفات الارتباط | Lunar Packaging',
                    'seo_desc' => 'سياسة ملفات الارتباط لموقع lunarambalaj.com.tr: ملفات أساسية وتحليلية وتسويقية.',
                    'body' => <<<'TEXT'
توضح سياسة ملفات الارتباط هذه كيفية استخدام ملفات الارتباط في lunarambalaj.com.tr وكيفية إدارة التفضيلات.

1. ما هي ملفات الارتباط؟
هي ملفات بيانات صغيرة تُحفظ في المتصفح لضمان عمل الموقع تقنياً وتحسين تجربة الاستخدام.

2. أنواع الملفات المستخدمة
- ملفات أساسية: الجلسة، الأمان والوظائف الأساسية للموقع.
- ملفات الأداء والتحليل: قياس الزيارات والأداء وسلوك المستخدم.
- ملفات التسويق/القياس: قياس أداء الحملات (عند تفعيل GTM أو Pixel أو أدوات مشابهة).

3. أغراض الاستخدام
- ضمان أمان الموقع وعمله بشكل صحيح،
- تحسين النماذج وتجربة التصفح،
- قياس فعالية الإعلانات والحملات.

4. إدارة الملفات
يمكنك حذف ملفات الارتباط أو تقييدها أو حظرها من إعدادات المتصفح. قد يؤدي تعطيل بعض الملفات إلى التأثير على بعض وظائف الموقع.

5. أدوات الطرف الثالث
قد يستخدم الموقع أدوات تحليلية/إعلانية لطرف ثالث (مثل Google أو Meta)، وتخضع هذه الأدوات لسياساتها الخاصة.

آخر تحديث: 2026-03-01
TEXT,
                ],
            ],
            'privacy' => [
                'tr' => [
                    'title' => 'Gizlilik Politikası',
                    'slug' => 'gizlilik-politikasi',
                    'seo_title' => 'Gizlilik Politikası | Lunar Ambalaj',
                    'seo_desc' => 'Lunar Ambalaj gizlilik politikası: veri güvenliği, saklama, iletişim ve başvuru süreçleri.',
                    'body' => <<<'TEXT'
Lunar Ambalaj olarak gizlilik, bilgi güvenliği ve yasal uyum ilkelerine dayalı olarak hareket ediyoruz.

1. Kapsam
Bu politika, web sitesi üzerinden toplanan iletişim, teklif ve teknik ölçüm verilerinin gizlilik esaslarını açıklar.

2. Veri Güvenliği
Kişisel veriler; yetkisiz erişime, değişikliğe, ifşaya veya kayba karşı idari ve teknik tedbirlerle korunur.
Erişimler rol bazlı sınırlandırılır ve kayıtlar düzenli olarak izlenir.

3. Doğruluk ve Güncellik
Verilerin doğru ve güncel tutulması için makul tedbirler alınır. Kullanıcı, değişiklik taleplerini iletebilir.

4. Saklama ve İmha
Veriler, yasal zorunluluklar ve işleme amacı kapsamında saklanır; süre sonunda silme, yok etme veya anonimleştirme süreçleri uygulanır.

5. Üçüncü Taraf Bağlantılar
Sitede yer alabilecek harici bağlantıların gizlilik uygulamalarından Lunar Ambalaj sorumlu değildir; ilgili tarafın politikaları geçerlidir.

6. Çocukların Gizliliği
Site B2B kullanım amaçlıdır. 18 yaş altı kullanıcılardan bilerek veri toplanması hedeflenmez.

7. İletişim
Gizlilikle ilgili tüm talepleriniz için:
E-posta: info@lunarambalaj.com.tr
Adres: Yenidogan Mah. Bahcelievler Cad. No: 2 Kat: 2 D: 35 Plaza Kati, 34652 Sancaktepe / Istanbul

Son Güncelleme: 01.03.2026
TEXT,
                ],
                'en' => [
                    'title' => 'Privacy Policy',
                    'slug' => 'privacy-policy',
                    'seo_title' => 'Privacy Policy | Lunar Packaging',
                    'seo_desc' => 'Lunar Packaging privacy policy: data security, retention, external links and contact channels.',
                    'body' => <<<'TEXT'
Lunar Packaging operates under principles of privacy, information security and legal compliance.

1. Scope
This policy explains privacy principles for contact, quotation and technical measurement data collected through the website.

2. Data Security
Personal data is protected with administrative and technical controls against unauthorized access, alteration, disclosure or loss.
Access is role-based and operational logs are monitored.

3. Accuracy and Updates
Reasonable measures are taken to keep data accurate and up to date. Users may request corrections.

4. Retention and Disposal
Data is retained in line with legal obligations and processing purposes; then deleted, destroyed or anonymized.

5. External Links
Lunar Packaging is not responsible for privacy practices of external websites linked from this site.

6. Children’s Privacy
This website is designed for B2B use. It does not intentionally collect data from children under 18.

7. Contact
For all privacy requests:
Email: info@lunarambalaj.com.tr
Address: Yenidogan Mah. Bahcelievler Cad. No: 2 Kat: 2 D: 35 Plaza Kati, 34652 Sancaktepe / Istanbul

Last Updated: 2026-03-01
TEXT,
                ],
                'ru' => [
                    'title' => 'Политика конфиденциальности',
                    'slug' => 'privacy-policy',
                    'seo_title' => 'Политика конфиденциальности | Lunar Packaging',
                    'seo_desc' => 'Политика конфиденциальности Lunar Packaging: безопасность данных, хранение и каналы связи.',
                    'body' => <<<'TEXT'
Lunar Packaging придерживается принципов конфиденциальности, информационной безопасности и правового соответствия.

1. Область действия
Данная политика описывает принципы обработки контактных, коммерческих и технических данных, собираемых через сайт.

2. Безопасность данных
Персональные данные защищаются организационными и техническими мерами от несанкционированного доступа, изменения, раскрытия или утраты.
Доступ предоставляется по ролям, журналы действий контролируются.

3. Актуальность данных
Принимаются разумные меры для поддержания данных в актуальном состоянии. Пользователь может запросить исправление.

4. Хранение и удаление
Данные хранятся в пределах сроков, установленных законом и целями обработки, затем удаляются, уничтожаются или обезличиваются.

5. Внешние ссылки
Lunar Packaging не несет ответственности за практики конфиденциальности внешних сайтов, на которые ведут ссылки.

6. Конфиденциальность детей
Сайт предназначен для B2B-сегмента и не ориентирован на сбор данных лиц младше 18 лет.

7. Контакты
По вопросам конфиденциальности:
E-mail: info@lunarambalaj.com.tr
Адрес: Yenidogan Mah. Bahcelievler Cad. No: 2 Kat: 2 D: 35 Plaza Kati, 34652 Sancaktepe / Istanbul

Последнее обновление: 2026-03-01
TEXT,
                ],
                'ar' => [
                    'title' => 'سياسة الخصوصية',
                    'slug' => 'privacy-policy',
                    'seo_title' => 'سياسة الخصوصية | Lunar Packaging',
                    'seo_desc' => 'سياسة الخصوصية لدى Lunar Packaging: أمن البيانات والاحتفاظ بها وقنوات التواصل.',
                    'body' => <<<'TEXT'
تلتزم Lunar Packaging بمبادئ الخصوصية وأمن المعلومات والامتثال القانوني.

1. نطاق السياسة
توضح هذه السياسة مبادئ الخصوصية المتعلقة ببيانات التواصل وطلبات الأسعار والقياسات التقنية التي يتم جمعها عبر الموقع.

2. أمن البيانات
تتم حماية البيانات الشخصية بإجراءات إدارية وتقنية ضد الوصول غير المصرح به أو التعديل أو الإفصاح أو الفقدان.
ويتم تقييد الوصول حسب الأدوار مع مراقبة السجلات التشغيلية.

3. دقة البيانات وتحديثها
يتم اتخاذ إجراءات معقولة للحفاظ على دقة البيانات وتحديثها، ويمكن للمستخدم طلب التصحيح.

4. الاحتفاظ والإتلاف
تُحتفظ البيانات وفق المتطلبات القانونية وأغراض المعالجة، ثم تُحذف أو تُتلف أو تُجهّل.

5. الروابط الخارجية
لا تتحمل Lunar Packaging مسؤولية ممارسات الخصوصية في المواقع الخارجية المرتبطة من خلال هذا الموقع.

6. خصوصية الأطفال
الموقع مخصص لاستخدام B2B ولا يستهدف جمع بيانات من أشخاص دون 18 عاماً.

7. التواصل
لجميع طلبات الخصوصية:
البريد الإلكتروني: info@lunarambalaj.com.tr
العنوان: Yenidogan Mah. Bahcelievler Cad. No: 2 Kat: 2 D: 35 Plaza Kati, 34652 Sancaktepe / Istanbul

آخر تحديث: 2026-03-01
TEXT,
                ],
            ],
        ];

        foreach ($policies as $type => $translations) {
            $page = Page::query()->updateOrCreate(
                ['type' => $type],
                ['is_published' => true]
            );

            foreach ($translations as $lang => $content) {
                $page->translations()->updateOrCreate(
                    ['lang' => $lang],
                    [
                        'title' => $content['title'],
                        'slug' => $content['slug'],
                        'body' => $content['body'],
                        'seo_title' => mb_substr($content['seo_title'], 0, 60),
                        'seo_desc' => mb_substr($content['seo_desc'], 0, 160),
                    ]
                );
            }
        }
    }
}
