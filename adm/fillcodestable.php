<?php

include "start.php";
$db->query("TRUNCATE TABLE events");
$db->query("TRUNCATE TABLE codes");
$db->query("TRUNCATE TABLE quests");

$qtexts = array(
"266" => array('Какой город является административным центром Приморского края?','Владивосток'),
"267" => array('В каком году была провозглашена амурская земская волость?','1878'),
"268" => array('Какой полуостров расположен на территории Дальнего Востока?','Чукотский'),
"269" => array('Какая из перечисленных территорий не входит в состав Дальневосточного федерального округа России?','Забайкальский край'),
"270" => array('Какой порт на Дальнем Востоке является крупнейшим по объему грузооборота?','Находка'),
"271" => array('Какая из  природных зон характерна для Дальнего Востока?','Лес'),
"272" => array('Какая из перечисленных культур не характерна для традиционного Дальнего Востока? <br> a) Чукотская <br> b) Якутская <br> c) Бурятская <br> d) Татарская','Татарская'),
"273" => array('Как называется национальный парк, расположенный на территории Приморского края?','Земля Леопарда'),
"274" => array('Какой город является крупнейшим на острове Сахалин?','Южно-Сахалинск'),
"275" => array('Какое животное является символом Дальнего Востока?','Тигр'),
"276" => array('Какой регион на Дальнем Востоке России славится производством нефти и газа?','Сахалин'),
"277" => array('Какой национальный парк на Дальнем Востоке России является домом для амурского тигра?','Земля Леопарда'),
"278" => array('Как называется крупнейшее озеро на Дальнем Востоке России?','Байкальское'),
"279" => array('В каком году была основана Владивостокская крепость?','1860'),
"280" => array('Как называется самая дальняя точка на востоке России?','Кипучий'),
"281" => array('Какой крупный город на Дальнем Востоке называют "Воротами к Сибири"?','Благовещенск'),
"282" => array('Какая из перечисленных птиц является символом Дальнего Востока?','Журавль'),
"283" => array('Какое море омывает берега Дальнего Востока?','Японское'),
"284" => array('Сколько лет проводится краевая профильная смена «Пленэр»?','56'),
"285" => array('Как называют людей, которые профессионально пишут картины?','7 лет'),
"286" => array('Какая самая известная картина Леонардо Да Винчи?','Художники'),
"287" => array('Какая самая известная картина Винсента Ван Гога?','Мона Лиза'),
"288" => array('Какая художественная галерея является самой известной?','Звездная ночь'),
"289" => array('Кто из художников России знаменит своими морскими пейзажами и сражениями?','Лувр'),
"290" => array('Как называется картина с медведями Ивана Шишкина?','Иван Айвазовский'),
"291" => array('В какой российской галерее можно увидеть произведения иконостасов из Ростова Великого?','утро в сосновом лесу'),
"292" => array('Какая картина Васнецова посвящена событиям 1812 года?','Третьяковская галерея'),
"293" => array('Какой русский художник создал серию картин под названием "Русские богатыри"?','"Богатыри"'),
"294" => array('В каком музее Москвы можно увидеть картины русских художников XVIII-XX веков?','Виктор Васнецов'),
"295" => array('Какой художник написал знаменитую картину "Золотая осень"?','Русский музей'),
"296" => array('Как называется самая известная картина Константина Коровина, изображающая Московский Кремль?','Левитан'),
"297" => array('Какой русский художник создал серию картин о Петре I?','"Вид на Москву. Кремль"'),
"298" => array('В каком городе России находится Эрмитаж, одно из крупнейших мировых музеев?','Илья Репин'),
"299" => array('Какая картина Айвазовского изображает морской бой под Чесмой?','Санкт-Петербург'),
"300" => array('Как называется знаменитая картина Брюллова, изображающая последний день Помпеи?','"Бой у Чесмы"'),
"301" => array('Какой русский художник создал серию картин о жизни и быте русского народа?','"Последний день Помпеи"'),
"302" => array('Какой русский художник создал серию картин о казачьей жизни на Дону?','Василий Суриков'),
"303" => array('Кто написал картину "Тайная вечеря"?','Илья Репин'),
"304" => array('Какой художник создал серию картин "Сотворение Адама" и "Творение Солнца"?','Леонардо да Винчи'),
"305" => array('Какой художник известен своими работами в стиле поп-арт, включая картины с изображением банок супа и известных личностей?','Микеланджело'),
"306" => array('Какой художник создал серию картин с изображением часов, расплавленного времени и странных сюжетов?','Энди Уорхол'),
"307" => array('Что не входит в жанры фольклора','Сальвадор Дали'),
"308" => array('Как называется небольшой стишок, которым сопровождали действия ребенка','фильмы'),
"309" => array('Что относится к лирическому жанру фольклора','пестушка'),
"310" => array('«Легок на помине» это?','поговорка'),
"310" => array('Как переводится folk lore с английского','поговорка'),
"310" => array('Что такое зачин','Народная мудрость'),
"311" => array('Автором фольклора является','начало сказки'),
"312" => array('Сколько всего детей на краевой профильной смене «Пленэр»','народ'),
"313" => array('Сколько ступеней в российской системе образования?','Система образования в России состоит из следующих степеней: дошкольное образование (детский сад), школьное образование (начальная и средняя школа) и университетское образование'),
"314" => array('В каком возрасте ребёнок может пойти в первый класс?','Получение начального общего образования в образовательных организациях начинается по достижении детьми возраста шести лет и шести месяцев при отсутствии противопоказаний по состоянию здоровья, но не позже достижения ими возраста восьми лет'),
"315" => array('Какая система оценок во Франции?','двадцатибалльная система'),
"316" => array('В каком месяце начинается учебный год в Японии?','в апреле'),
"317" => array('Назовите первый российский университет.','Академический университет Петербургской академии наук'),
"318" => array('Какую программу подготовки необходимо пройти, чтобы работать помощником вожатого в КДЦ «Созвездие»?','Детское объединение «Я-Вожатый»'),
"319" => array('Какую награду получает вожатых, успешно отработавший 3 смены?','Звезда успеха'),
"320" => array('Сколько лет реализуется программа подготовки помощников вожатых?','18 лет'),
"321" => array('Какое образование необходимо иметь, чтобы работать вожатым?','Иметь высшее педагогическое образование/незаконченное высшее педагогическое образование, или сертификат о прохождении школы вожатого'),
"322" => array('Со скольки лет можно работать подменным вожатым?','с 18 лет'),
"323" => array('В какой из следующих стран отменили домашнее задание?','Израиль'),
"324" => array('ВПродолжите цитату «Ученье – свет, а неученье — …»','Тьма'),
"325" => array('В одной из этих стран оценки запрещены до 8 класса.','Норвегия'),
"326" => array('Как обычно говорят, когда не знают ответа на вопрос: Я что, обязан знать всё? Я не гений всё знать? Я что, Пушкин?','Я что, Пушкин?'),
"327" => array('Рассказ, посвященный учителям.','Уроки французского'),
"328" => array('Когда отмечается всемирный день учителя?','5 октября'),
"329" => array('Как японцы обращаются к своему учителю?','Сенсэй'),
"330" => array('Как называют педагогов по общественным дисциплинам?','Гуманитарии'),
"331" => array('Учитель учителей это?','Методист'),
);

$cid = 1;
$sucount = 0;

foreach ($CODES as $type => $cc) {
    for ($i = 0; $i < $cc['count']; $i++) {
        $code = substr(md5($cid . MD5SALT), 0, 16);
        $sucount += (int)$db->query("INSERT INTO codes (cid,code,type) VALUES ($cid,'$code',$type)");

        if ($type == CODE_BLUE) {
            $text = $db->real_escape_string($qtexts[$cid][0]);
            $answ = $db->real_escape_string($qtexts[$cid][1]);
            $db->query("INSERT INTO quests (cid,txt,answ) VALUES ($cid,'$text','$answ')");
            //echo "INSERT INTO quests (cid,txt,answ) VALUES ($cid,'$text','$answ')<br>";
        }
        $cid++;
        //break;
    }
}


echo $sucount . " кодов с вопросами загружено";