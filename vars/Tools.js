export function underlineWithUnderscores(text) {
    // Regular expression to match text surrounded by underscores
    const regex = /_([^_]+)_/g;

    // Replace the matched substrings with HTML <u> tags
    const result = text.replace(regex, '<u>$1</u>');

    return result;
}


export function convertISBN10to13(ISBN10) {
    const variantPart = '978'.concat(ISBN10.toString().slice(0, -1));
    let switcher = 1;
    let lastValue = 0;

    for (const char of variantPart) {
        lastValue += (switcher*char);
        if (switcher === 1) {
            switcher = 3;
        } else {
            switcher = 1;
        }
    }
    lastValue = lastValue % 10;
    if (lastValue === 0) {
        lastValue = 0;
    } else {
        lastValue = 10 - lastValue;
    }

    return variantPart.concat(lastValue);
}

export function generateSentenceWithElements(elements, start, separator, lastSeparator=null, end=null) {
    if (elements.length > 1) {
        for (const element of elements.slice(0, -1)) {
            start += (element + separator); 
        }

        start += (lastSeparator ? lastSeparator : separator) + elements.slice(-1) + (end ? end : "");

        return start;
    }
    return start + elements[0];
}

export const Languages = {
    'en': 'English',
    'fr': 'Français',
    'es': 'Español',
    'de': 'Deutsch',
    'it': 'Italiano',
    'ja': '日本語',
    'zh': '中文',
    'ar': 'العربية',
    'ru': 'Русский',
    'hi': 'हिन्दी',
    'pt': 'Português',
    'bn': 'বাংলা',
    'pa': 'ਪੰਜਾਬੀ',
    'ur': 'اردو',
    'sw': 'Kiswahili',
    'nl': 'Nederlands',
    'tr': 'Türkçe',
    'vi': 'Tiếng Việt',
    'ko': '한국어',
    'th': 'ไทย',
    'id': 'Bahasa Indonesia',
    'ms': 'Bahasa Melayu',
    'fil': 'Filipino',
    'pl': 'Polski',
    'uk': 'Українська',
    'cz': 'Čeština',
    'hu': 'Magyar',
    'ro': 'Română',
    'sv': 'Svenska',
    'no': 'Norsk',
    'fi': 'Suomi',
    'da': 'Dansk',
    'el': 'Ελληνικά',
    'bg': 'Български',
    'sr': 'Српски',
    'hr': 'Hrvatski',
    'sl': 'Slovenščina',
    'sk': 'Slovenčina',
    'lt': 'Lietuvių',
    'lv': 'Latviešu',
    'et': 'Eesti',
    'ka': 'ქართული',
    'hy': 'Հայերեն',
    'iw': 'עברית',
    'ar': 'العربية',
    'fa': 'فارسی',
    'ur': 'اردو',
    'ne': 'नेपाली',
    'mr': 'मराठी',
    'hi': 'हिन्दी',
    'bn': 'বাংলা',
    'si': 'සිංහල',
    'th': 'ไทย',
    'my': 'မြန်မာစာ',
    'km': 'ភាសាខ្មែរ',
    'lo': 'ພາສາລາວ',
    'vi': 'Tiếng Việt',
    'ms': 'Bahasa Malaysia',
    'tl': 'Filipino',
    'es': 'Español',
    'pt': 'Português',
    'fr': 'Français',
    'de': 'Deutsch',
    'it': 'Italiano',
    'nl': 'Nederlands',
    'sv': 'Svenska',
    'no': 'Norsk',
    'fi': 'Suomi',
    'da': 'Dansk',
    'is': 'Íslenska',
    'sq': 'Shqip',
    'mk': 'Македонски',
    'bs': 'Bosanski',
    'hr': 'Hrvatski',
    'sr': 'Српски',
    'sl': 'Slovenščina',
    'ro': 'Română',
    'hu': 'Magyar',
    'bg': 'Български',
    'el': 'Ελληνικά',
    'tr': 'Türkçe',
    'ka': 'ქართული',
    'hy': 'Հայերեն',
    'uz': 'Oʻzbekcha',
    'kk': 'Қазақша',
    'ky': 'Кыргызча',
    'tk': 'Türkmençe',
    'mn': 'Монгол',
    'ja': '日本語',
    'ko': '한국어',
    'zh': '中文',
    'th': 'ไทย',
    'vi': 'Tiếng Việt',
    'ms': 'Bahasa Malaysia',
    'id': 'Bahasa Indonesia',
    'tl': 'Filipino',
};

export const key = '';
