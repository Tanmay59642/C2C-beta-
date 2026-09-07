import json
import re

with open('scratch/dict_hi.json', 'r', encoding='utf-8') as f:
    hi = json.load(f)

with open('scratch/dict_mr.json', 'r', encoding='utf-8') as f:
    mr = json.load(f)

with open('scratch/index.html_strings.txt', 'r', encoding='utf-8') as f:
    idx_strs = [line.strip() for line in f if line.strip()]

with open('scratch/about.html_strings.txt', 'r', encoding='utf-8') as f:
    abt_strs = [line.strip() for line in f if line.strip()]

all_strs = set(idx_strs + abt_strs)

# Common word replacements for automatic phrase translation fallback
hi_subwords = {
    "District": "जिला",
    "Collector": "कलेक्टर",
    "Municipal": "नगर पालिका",
    "Corporation": "निगम",
    "University": "विश्वविद्यालय",
    "Universities": "विश्वविद्यालयों",
    "College": "कॉलेज",
    "Colleges": "कॉलेजों",
    "Student": "छात्र",
    "Students": "छात्रों",
    "Engineer": "इंजीनियर",
    "Engineers": "इंजीनियरों",
    "Faculty": "संकाय",
    "Lab": "प्रयोगशाला",
    "Labs": "प्रयोगशालाएं",
    "Research": "अनुसंधान",
    "Project": "परियोजना",
    "Projects": "परियोजनाओं",
    "Water": "जल",
    "Solar": "सौर",
    "Road": "सड़क",
    "Roads": "सड़कें",
    "Health": "स्वास्थ्य",
    "Healthcare": "स्वास्थ्य सेवा",
    "Rural": "ग्रामीण",
    "Urban": "शहरी",
    "System": "प्रणाली",
    "Systems": "प्रणालियां",
    "Infrastructure": "बुनियादी ढांचा",
    "Portal": "पोर्टल",
    "Dashboard": "डैशबोर्ड",
    "Telemetry": "टेलीमेट्री",
    "Sensor": "सेंसर",
    "Sensors": "सेंसर",
    "Escrow": "एस्क्रो",
    "Funding": "वित्तपोषण",
    "Fund": "फंड",
    "CSR": "सीएसआर",
    "Compliance": "अनुपालन",
    "Sovereign": "संप्रभु",
    "National": "राष्ट्रीय",
    "State": "राज्य",
    "Govt": "सरकार",
    "Government": "सरकार",
    "Ministry": "मंत्रालय",
    "Education": "शिक्षा",
    "AICTE": "एआईसीटीई",
    "SIH": "एसआईएच",
    "Hackathon": "हैकाथॉन",
    "Vector": "वेक्टर",
    "Vectors": "वैक्टर",
    "Priority": "प्राथमिकता",
    "Urgency": "तात्कालिकता",
    "Critical": "गंभीर",
    "High": "उच्च",
    "Medium": "मध्यम",
    "Low": "कम",
    "SLA": "एसएलए",
    "Hours": "घंटे",
    "Days": "दिन",
    "Status": "स्थिति",
    "Verified": "सत्यापित",
    "Pending": "लंबित",
    "Resolved": "हल किया गया",
    "Active": "सक्रिय",
    "Total": "कुल",
    "Impact": "प्रभाव",
    "Metrics": "मेट्रिक्स",
    "Social": "सामाजिक",
    "Return": "रिटर्न",
    "Investment": "निवेश",
    "SDG": "एसडीजी",
    "Pillar": "स्तंभ",
    "Pillars": "स्तंभ",
    "Vision": "विजन",
    "Mission": "मिशन",
    "Core": "मुख्य",
    "Team": "टीम",
    "Lead": "लीड",
    "Architect": "आर्किटेक्ट",
    "Engineer": "इंजीनियर",
    "Developer": "डेवलपर",
    "Specialist": "विशेषज्ञ",
    "Coordinator": "समन्वयक"
}

mr_subwords = {
    "District": "जिल्हा",
    "Collector": "जिल्हाधिकारी",
    "Municipal": "नगरपालिका",
    "Corporation": "महानगरपालिका",
    "University": "विद्यापीठ",
    "Universities": "विद्यापीठे",
    "College": "कॉलेज",
    "Colleges": "कॉलेजेस",
    "Student": "विद्यार्थी",
    "Students": "विद्यार्थी",
    "Engineer": "अभियंता",
    "Engineers": "अभियंते",
    "Faculty": "प्राध्यापक",
    "Lab": "प्रयोगशाळा",
    "Labs": "प्रयोगशाळा",
    "Research": "संशोधन",
    "Project": "प्रकल्प",
    "Projects": "प्रकल्प",
    "Water": "पाणी",
    "Solar": "सौर",
    "Road": "रस्ता",
    "Roads": "रस्ते",
    "Health": "आरोग्य",
    "Healthcare": "आरोग्य सेवा",
    "Rural": "ग्रामीण",
    "Urban": "शहरी",
    "System": "प्रणाली",
    "Systems": "प्रणाली",
    "Infrastructure": "पायाभूत सुविधा",
    "Portal": "पोर्टल",
    "Dashboard": "डॅशबोर्ड",
    "Telemetry": "टेलिमेट्री",
    "Sensor": "सेन्सर",
    "Sensors": "सेन्सर्स",
    "Escrow": "एस्क्रॉ",
    "Funding": "निधी",
    "Fund": "निधी",
    "CSR": "CSR",
    "Compliance": "पालन",
    "Sovereign": "संप्रभु",
    "National": "राष्ट्रीय",
    "State": "राज्य",
    "Govt": "शासन",
    "Government": "शासन",
    "Ministry": "मंत्रालय",
    "Education": "शिक्षण",
    "AICTE": "AICTE",
    "SIH": "SIH",
    "Hackathon": "हॅकाथॉन",
    "Vector": "व्हेक्टर",
    "Vectors": "व्हेक्टर्स",
    "Priority": "प्राधान्य",
    "Urgency": "तातडी",
    "Critical": "अत्यंत तातडीचे",
    "High": "उच्च",
    "Medium": "मध्यम",
    "Low": "कमी",
    "SLA": "SLA",
    "Hours": "तास",
    "Days": "दिवस",
    "Status": "स्थिती",
    "Verified": "सत्यापित",
    "Pending": "प्रलंबित",
    "Resolved": "सोडवले",
    "Active": "सक्रिय",
    "Total": "एकूण",
    "Impact": "प्रभाव",
    "Metrics": "मेट्रिक्स",
    "Social": "सामाजिक",
    "Return": "परतावा",
    "Investment": "गुंतवणूक",
    "SDG": "SDG",
    "Pillar": "स्तंभ",
    "Pillars": "स्तंभ",
    "Vision": "ध्येय",
    "Mission": "उद्दिष्ट",
    "Core": "मुख्य",
    "Team": "टीम",
    "Lead": "प्रमुख",
    "Architect": "आर्किटेक्ट",
    "Engineer": "अभियंता",
    "Developer": "डेव्हलपर",
    "Specialist": "तज्ज्ञ",
    "Coordinator": "समन्वयक"
}

def translate_str(text, subdict):
    words = text.split(' ')
    res = []
    for w in words:
        clean_w = re.sub(r'[^\w]', '', w)
        if clean_w in subdict:
            res.append(w.replace(clean_w, subdict[clean_w]))
        else:
            res.append(w)
    return ' '.join(res)

added_count = 0
for s in sorted(all_strs, key=lambda x: -len(x)):
    if s not in hi:
        hi[s] = translate_str(s, hi_subwords)
        added_count += 1
    if s not in mr:
        mr[s] = translate_str(s, mr_subwords)

print(f"Auto-generated translations for {added_count} strings.")
print(f"Total HI keys: {len(hi)}, Total MR keys: {len(mr)}")

with open('scratch/dict_hi.json', 'w', encoding='utf-8') as f:
    json.dump(hi, f, ensure_ascii=False, indent=2)

with open('scratch/dict_mr.json', 'w', encoding='utf-8') as f:
    json.dump(mr, f, ensure_ascii=False, indent=2)
