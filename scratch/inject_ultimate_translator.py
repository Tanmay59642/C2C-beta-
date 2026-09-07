import json
import re

with open('scratch/dict_hi.json', 'r', encoding='utf-8') as f:
    hi_phrases = json.load(f)

with open('scratch/dict_mr.json', 'r', encoding='utf-8') as f:
    mr_phrases = json.load(f)

with open('scratch/word_dict_hi.json', 'r', encoding='utf-8') as f:
    hi_words = json.load(f)

with open('scratch/word_dict_mr.json', 'r', encoding='utf-8') as f:
    mr_words = json.load(f)

# Login-specific phrase additions
login_phrases_hi = {
    "Sign In to Sovereign Mesh": "संप्रभु मेश में साइन इन करें",
    "Sovereign Identity Authentication": "संप्रभु पहचान प्रमाणीकरण",
    "Access your personalized dashboard based on your registered ecosystem persona.": "अपने पंजीकृत पारिस्थितिकी तंत्र व्यक्तित्व के आधार पर अपने व्यक्तिगत डैशबोर्ड तक पहुंचें।",
    "Student Innovator Mode: Login with university email or PRN.": "छात्र नवप्रवर्तक मोड: विश्वविद्यालय ईमेल या पीआरएन के साथ लॉगिन करें।",
    "Institutional Email / User ID": "संस्थागत ईमेल / यूजर आईडी",
    "Security Password": "सुरक्षा पासवर्ड",
    "Remember Sovereign Session": "संप्रभु सत्र याद रखें",
    "Forgot Key?": "कुंजी भूल गए?",
    "Or Sign In With": "या इसके साथ साइन इन करें",
    "Don't have an institutional account?": "क्या आपके पास संस्थागत खाता नहीं है?"
}

login_phrases_mr = {
    "Sign In to Sovereign Mesh": "संप्रभु जाळ्यात साइन इन करा",
    "Sovereign Identity Authentication": "संप्रभु ओळख प्रमाणन",
    "Access your personalized dashboard based on your registered ecosystem persona.": "तुमच्या नोंदणीकृत भूमिकेनुसार तुमच्या वैयक्तिक डॅशबोर्डवर प्रवेश करा.",
    "Student Innovator Mode: Login with university email or PRN.": "विद्यार्थी नवसंशोधक मोड: विद्यापीठ ईमेल किंवा PRN सह लॉगिन करा.",
    "Institutional Email / User ID": "संस्थात्मक ईमेल / युझर आयडी",
    "Security Password": "सुरक्षा पासवर्ड",
    "Remember Sovereign Session": "संप्रभु सत्र लक्षात ठेवा",
    "Forgot Key?": "पासवर्ड विसरलात?",
    "Or Sign In With": "किंवा यासह साइन इन करा",
    "Don't have an institutional account?": "संस्थात्मक खाते नाही?"
}

for k, v in login_phrases_hi.items():
    hi_phrases[k] = v

for k, v in login_phrases_mr.items():
    mr_phrases[k] = v

dict_js_content = f"""  const c2cTextDictionary = {{
    "hi": {json.dumps(hi_phrases, ensure_ascii=False, indent=6)},
    "mr": {json.dumps(mr_phrases, ensure_ascii=False, indent=6)}
  }};

  const c2cWordDictionary = {{
    "hi": {json.dumps(hi_words, ensure_ascii=False, indent=6)},
    "mr": {json.dumps(mr_words, ensure_ascii=False, indent=6)}
  }};"""

set_lang_js_content = """    function setLanguage(langKey) {
      localStorage.setItem('c2c-lang', langKey);
      
      const codeEl = document.getElementById('current-lang-code');
      if (codeEl) codeEl.innerText = langKey.toUpperCase();

      // 1. Translate data-i18n elements if present
      if (typeof c2cTranslations !== 'undefined' && c2cTranslations[langKey]) {
        const langMap = c2cTranslations[langKey];
        document.querySelectorAll('[data-i18n]').forEach(el => {
          const key = el.getAttribute('data-i18n');
          if (langMap[key]) {
            el.innerText = langMap[key];
          }
        });
      }

      // 2. Phrase & Word Replacement Engine using literal replaceAll
      const phraseDict = typeof c2cTextDictionary !== 'undefined' ? (c2cTextDictionary[langKey] || {}) : {};
      const wordDict = typeof c2cWordDictionary !== 'undefined' ? (c2cWordDictionary[langKey] || {}) : {};
      
      const sortedPhraseKeys = Object.keys(phraseDict).sort((a, b) => b.length - a.length);
      const sortedWordKeys = Object.keys(wordDict).sort((a, b) => b.length - a.length);

      const translateString = (orig) => {
        if (!orig) return orig;
        let val = orig;

        // Phase 1: Literal Phrase Replacement
        for (const enKey of sortedPhraseKeys) {
          if (!enKey) continue;
          if (val.includes(enKey)) {
            const transVal = phraseDict[enKey];
            val = val.replaceAll(enKey, transVal);
          }
        }

        // Phase 2: Fallback Word Replacement for any remaining English words
        if (langKey !== 'en' && /[A-Za-z]{2,}/.test(val)) {
          for (const enWord of sortedWordKeys) {
            if (!enWord || enWord.length < 2) continue;
            if (val.includes(enWord)) {
              const transWord = wordDict[enWord];
              val = val.replaceAll(enWord, transWord);
            }
          }
        }

        return val;
      };

      const walkNode = (node) => {
        if (node.nodeType === Node.TEXT_NODE) {
          const trimmed = node.nodeValue.trim();
          if (!trimmed) return;

          // Skip icon text nodes
          if (node.parentNode && node.parentNode.classList && (
            node.parentNode.classList.contains('material-symbols-outlined') ||
            node.parentNode.classList.contains('material-icons') ||
            node.parentNode.classList.contains('material-icons-outlined') ||
            node.parentNode.classList.contains('fa')
          )) {
            return;
          }
          
          if (!node._origText) {
            node._origText = node.nodeValue;
          }
          
          if (langKey === 'en') {
            node.nodeValue = node._origText;
          } else {
            node.nodeValue = translateString(node._origText);
          }
        } else if (node.nodeType === Node.ELEMENT_NODE) {
          if (['SCRIPT', 'STYLE', 'NOSCRIPT', 'SVG', 'CODE'].includes(node.tagName)) return;

          if (node.classList && (
            node.classList.contains('material-symbols-outlined') ||
            node.classList.contains('material-icons') ||
            node.classList.contains('material-icons-outlined') ||
            node.classList.contains('fa')
          )) {
            return;
          }
          
          ['placeholder', 'title', 'alt'].forEach(attr => {
            if (node.hasAttribute(attr)) {
              if (!node['orig_' + attr]) {
                node['orig_' + attr] = node.getAttribute(attr);
              }
              if (langKey === 'en') {
                node.setAttribute(attr, node['orig_' + attr]);
              } else {
                node.setAttribute(attr, translateString(node['orig_' + attr]));
              }
            }
          });

          if (['INPUT', 'BUTTON'].includes(node.tagName) && node.value) {
            if (!node._origValue) {
              node._origValue = node.value;
            }
            if (langKey === 'en') {
              node.value = node._origValue;
            } else {
              node.value = translateString(node._origValue);
            }
          }

          for (let child of node.childNodes) {
            walkNode(child);
          }
        }
      };

      walkNode(document.body);

      const dropdown = document.getElementById('lang-dropdown');
      if (dropdown && !dropdown.classList.contains('hidden')) {
        dropdown.classList.add('hidden');
      }
    }"""

def process_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Clean up old c2cWordDictionary if present
    content = re.sub(r'const c2cWordDictionary = \{.*?\};\n\n', '', content, flags=re.DOTALL)

    # Check if c2cTextDictionary is already in the file
    dict_pattern = re.compile(r'const c2cTextDictionary = \{.*?\};\n', re.DOTALL)
    if dict_pattern.search(content):
        content = dict_pattern.sub(dict_js_content + '\n', content)
    else:
        # Append before closing </script> or </body>
        content = content.replace('</script>', dict_js_content + '\n  </script>', 1)

    # Check setLanguage
    set_lang_pattern = re.compile(r'function setLanguage\(langKey\) \{.*?\n    \}', re.DOTALL)
    if set_lang_pattern.search(content):
        content = set_lang_pattern.sub(set_lang_js_content, content)
    else:
        content = content.replace('</script>', set_lang_js_content + '\n  </script>', 1)

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"Successfully updated {filepath}")

process_file('index.html')
process_file('about.html')
process_file('login.html')
