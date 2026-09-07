import json
import re

with open('scratch/dict_hi.json', 'r', encoding='utf-8') as f:
    hi = json.load(f)

with open('scratch/dict_mr.json', 'r', encoding='utf-8') as f:
    mr = json.load(f)

dict_js_content = f"""  const c2cTextDictionary = {{
    "hi": {json.dumps(hi, ensure_ascii=False, indent=6)},
    "mr": {json.dumps(mr, ensure_ascii=False, indent=6)}
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

      // 2. Universal Node & Attribute Walker with Length-Sorted Keys
      const dict = typeof c2cTextDictionary !== 'undefined' ? (c2cTextDictionary[langKey] || {}) : {};
      const sortedKeys = Object.keys(dict).sort((a, b) => b.length - a.length);

      const translateString = (orig) => {
        let val = orig;
        for (const enKey of sortedKeys) {
          if (!enKey) continue;
          const transVal = dict[enKey];
          if (val.includes(enKey)) {
            val = val.replace(new RegExp(enKey.replace(/[-\\/\\\\^$*+?.()|[\\]{}]/g, '\\\\$&'), 'g'), transVal);
          }
        }
        return val;
      };

      const walkNode = (node) => {
        if (node.nodeType === Node.TEXT_NODE) {
          const trimmed = node.nodeValue.trim();
          if (!trimmed) return;
          
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

    # Replace c2cTextDictionary = { ... };
    dict_pattern = re.compile(r'const c2cTextDictionary = \{.*?\};\n', re.DOTALL)
    if dict_pattern.search(content):
        content = dict_pattern.sub(dict_js_content + '\n', content)

    # Replace function setLanguage(langKey) { ... }
    set_lang_pattern = re.compile(r'function setLanguage\(langKey\) \{.*?\n    \}', re.DOTALL)
    if set_lang_pattern.search(content):
        content = set_lang_pattern.sub(set_lang_js_content, content)

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"Updated {filepath}")

process_file('index.html')
process_file('about.html')
