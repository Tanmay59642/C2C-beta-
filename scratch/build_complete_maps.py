import json
import os

with open('scratch/dict_hi.json', 'r', encoding='utf-8') as f:
    hi = json.load(f)

with open('scratch/dict_mr.json', 'r', encoding='utf-8') as f:
    mr = json.load(f)

additional_translations = {
    # Team Members & Bios (about.html)
    "Parnavi Janbhor": ("पर्णवी जाणभोर", "पर्णवी जाणभोर"),
    "Tanmay Shirgudi": ("तन्मय शिरगुडी", "तन्मय शिरगुडी"),
    "Shlok Jadhav": ("श्लोक जाधव", "श्लोक जाधव"),
    "Shrutesh Gaikwad": ("श्रुतेश गायकवाड", "श्रुतेश गायकवाड"),
    "Kashaf Khan": ("कशफ खान", "कशफ खान"),
    "Aqsa Haji": ("अक्सा हाजी", "अक्सा हाजी"),
    "contact no: 9022634336": ("संपर्क क्र: 9022634336", "संपर्क क्र: 9022634336"),
    "contact no: 8850604104": ("संपर्क क्र: 8850604104", "संपर्क क्र: 8850604104"),
    "contact no: 9137025807": ("संपर्क क्र: 9137025807", "संपर्क क्र: 9137025807"),
    "contact no: 8828359404": ("संपर्क क्र: 8828359404", "संपर्क क्र: 8828359404"),
    "contact no: 8291079379": ("संपर्क क्र: 8291079379", "संपर्क क्र: 8291079379"),
    "contact no: 9137070110": ("संपर्क क्र: 9137070110", "संपर्क क्र: 9137070110"),
    "Project Lead & AI Specialist": ("प्रोजेक्ट लीड और एआई विशेषज्ञ", "प्रकल्प प्रमुख आणि AI तज्ज्ञ"),
    "Full-Stack Systems Architect": ("फुल-स्टैक सिस्टम आर्किटेक्ट", "फुल-स्टॅक सिस्टीम आर्किटेक्ट"),
    "Frontend & UX Lead": ("फ्रंटएंड और यूएक्स लीड", "फ्रंटएंड आणि UX प्रमुख"),
    "Backend & Escrow Engineer": ("बैकएंड और एस्क्रो इंजीनियर", "बॅकएंड आणि एस्क्रॉ अभियंता"),
    "GIS & Field Operations Lead": ("जीआईएस और फील्ड ऑपरेशंस लीड", "GIS आणि फील्ड ऑपरेशन्स प्रमुख"),
    "Public Policy & Outreach Coordinator": ("सार्वजनिक नीति और आउटरीच समन्वयक", "सार्वजनिक धोरण आणि संपर्क समन्वयक"),
    "Vidyalankar Institute of Technology": ("विद्यालंकार इंस्टीट्यूट ऑफ टेक्नोलॉजी", "विद्यालंकार तंत्रज्ञान संस्था"),

    # Timeline Callout Notes & Descriptions (about.html)
    "Initial NLP prototype tested with 12 engineering colleges in Mumbai Metropolitan Region. 84 field problems cataloged.": ("मुंबई महानगर क्षेत्र के 12 इंजीनियरिंग कॉलेजों में शुरुआती एनएलपी प्रोटोटाइप का परीक्षण किया गया। 84 जमीनी समस्याओं को सूचीबद्ध किया गया।", "मुंबई महानगर क्षेत्रातील 12 अभियांत्रिकी महाविद्यालयांमध्ये सुरुवातीच्या NLP प्रोटोटाइपची चाचणी. 84 समस्यांची नोंद."),
    "Onboarded 18 District Collectorates and established the automated Section 135 CSR escrow protocol with 6 corporate partners.": ("18 जिला कलेक्टरों को शामिल किया गया और 6 कॉर्पोरेट भागीदारों के साथ स्वचालित धारा 135 सीएसआर एस्क्रो प्रोटोकॉल स्थापित किया गया।", "18 जिल्हाधिकारी कार्यालये जोडली आणि 6 कॉर्पोरेट भागीदारांसह स्वयंचलित कलम 135 CSR एस्क्रॉ प्रोटोकॉल स्थापित केला."),
    "₹4.8 Crore in corporate funding locked into project escrows; 412 campuses linked across Maharashtra and Gujarat.": ("₹4.8 करोड़ का कॉर्पोरेट फंड प्रोजेक्ट एस्क्रो में लॉक किया गया; महाराष्ट्र और गुजरात में 412 कैंपस जोड़े गए।", "₹4.8 कोटी कॉर्पोरेट निधी प्रकल्प एस्क्रॉमध्ये जमा; महाराष्ट्र आणि गुजरातमध्ये 412 कॅम्पस जोडले."),
    "National SIH expansion targeting 1,200+ engineering institutions and integration with Ministry of Education digital portal.": ("1,200+ इंजीनियरिंग संस्थानों और शिक्षा मंत्रालय के डिजिटल पोर्टल के साथ एकीकरण को लक्षित करने वाला राष्ट्रीय एसआईएच विस्तार।", "1,200+ अभियांत्रिकी संस्था आणि शिक्षण मंत्रालयाच्या डिजिटल पोर्टलशी एकत्रीकरणाचे राष्ट्रीय SIH उद्दिष्ट."),

    # FAQs Questions & Answers (index.html & about.html)
    "How does Campus2Community verify the legitimacy of ground issues?": ("Campus2Community जमीनी मुद्दों की वैधता की पुष्टि कैसे करता है?", "Campus2Community समस्यांची तातडी आणि सत्यता कशी पडताळते?"),
    "How do student engineers receive academic credits for C2C projects?": ("छात्र इंजीनियरों को C2C परियोजनाओं के लिए अकादमिक क्रेडिट कैसे मिलते हैं?", "विद्यार्थी अभियंत्यांना C2C प्रकल्पांसाठी शैक्षणिक क्रेडिट्स कसे मिळतात?"),
    "Are CSR funds provided directly to students or through institutional escrows?": ("क्या सीएसआर फंड सीधे छात्रों को या संस्थागत एस्क्रो के माध्यम से दिए जाते हैं?", "CSR निधी थेट विद्यार्थ्यांना दिला जातो की संस्थागत एस्क्रॉद्वारे?"),
    "How quickly can a District Collector or Municipal Body onboard to C2C?": ("जिला कलेक्टर या नगर निकाय C2C में कितनी जल्दी शामिल हो सकते हैं?", "जिल्हाधिकारी किंवा नगरपालिका C2C प्लॅटफॉर्मवर किती लवकर नोंदणी करू शकतात?"),

    # 4 Platform Pillars (about.html & index.html)
    "1. Ground Distress Capture": ("1. ग्राउंड डिस्ट्रेस कैप्चर", "1. समस्या नोंदणी"),
    "2. AI Matching & Dispatch": ("2. एआई मैचिंग और डिस्पैच", "2. AI मॅचिंग आणि डिस्पॅच"),
    "3. Sovereign Escrow & Execution": ("3. संप्रभु एस्क्रो और निष्पादन", "3. एस्क्रॉ आणि अंमलबजावणी"),
    "4. Impact Measurement": ("4. प्रभाव मापन", "4. प्रभाव मोजणी"),

    # Grassroots Pipeline Filter Tags & Ticket Badges
    "ULB SLA: 48h": ("ULB SLA: 48 घंटे", "ULB SLA: 48तास"),
    "ULB SLA: 24h": ("ULB SLA: 24 घंटे", "ULB SLA: 24तास"),
    "ULB SLA: 72h": ("ULB SLA: 72 घंटे", "ULB SLA: 72तास"),
    "₹4.5L Escrow": ("₹4.5 लाख एस्क्रो", "₹4.5 लाख एस्क्रॉ"),
    "₹8.2L Escrow": ("₹8.2 लाख एस्क्रो", "₹8.2 लाख एस्क्रॉ"),
    "₹2.8L Escrow": ("₹2.8 लाख एस्क्रो", "₹2.8 लाख एस्क्रॉ"),
    "Verify & Allocate CSR Fund": ("सत्यापित करें और सीएसआर फंड आवंटित करें", "पडताळणी करा आणि CSR निधी वाटप करा"),
    "Bhiwandi Rural, District Thane": ("भिवंडी ग्रामीण, जिला ठाणे", "भिवंडी ग्रामीण, जिल्हा ठाणे"),
    "Palghar Coastal Belt": ("पालघर तटीय पट्टी", "पालघर किनारी पट्टी"),
    "Gadchiroli Tribal Block": ("गडचिरोली जनजातीय ब्लॉक", "गडचिरोली आदिवासी भाग"),

    # Sensor Telemetry Cards
    "FIELD SENSOR TELEMETRY": ("फील्ड सेंसर टेलीमेट्री", "क्षेत्र सेन्सर टेलिमेट्री"),
    "12 Nodes Transmitting": ("12 नोड्स प्रसारित हो रहे हैं", "12 नोड्स प्रेषित होत आहेत"),
    "TEAM MILESTONE VELOCITY": ("टीम मील का पत्थर वेग", "टीम टप्पा वेग"),
    "DISTRICT COLLECTOR SIGNOFF": ("जिला कलेक्टर हस्ताक्षर", "जिल्हाधिकारी स्वाक्षरी"),

    # SDG Titles
    "Water & Sanitation (SDG 6)": ("जल और स्वच्छता (SDG 6)", "पाणी व स्वच्छता (SDG 6)"),
    "Agritech & Soil Health (SDG 2)": ("एग्रीटेक और मृदा स्वास्थ्य (SDG 2)", "कृषी तंत्रज्ञान व मृदा (SDG 2)"),
    "Smart Infrastructure (SDG 11)": ("स्मार्ट इंफ्रास्ट्रक्चर (SDG 11)", "स्मार्ट पायाभूत सुविधा (SDG 11)"),
    "Rural Healthcare (SDG 3)": ("ग्रामीण स्वास्थ्य देखभाल (SDG 3)", "ग्रामीण आरोग्य (SDG 3)"),

    # Stakeholder Value Items
    "For Students & Innovators": ("छात्रों और नवप्रवर्तकों के लिए", "विद्यार्थी आणि नवसंशोधकांसाठी"),
    "For Universities & Research Labs": ("विश्वविद्यालयों और अनुसंधान प्रयोगशालाओं के लिए", "विद्यापीठे आणि संशोधन लॅब्ससाठी"),
    "For Municipal Bodies & District Collectors": ("नगर निकायों और जिला कलेक्टरों के लिए", "नगरपालिका आणि जिल्हाधिकाऱ्यांसाठी"),
    "For Corporate CSR & Industry ESCROW": ("कॉर्पोरेट सीएसआर और उद्योग एस्क्रो के लिए", "कॉर्पोरेट CSR आणि उद्योग एस्क्रॉसाठी"),

    # UI Inputs, Placeholders, Form Labels
    "Distress Category": ("संकट श्रेणी", "समस्या प्रकार"),
    "Location / Geo-Tag": ("स्थान / जियो-टैग", "स्थान / जिओ-टॅग"),
    "Estimated Affected Population": ("अनुमानित प्रभावित जनसंख्या", "अंदाजित बाधित लोकसंख्या"),
    "Problem Description & Evidence": ("समस्या विवरण और साक्ष्य", "समस्या वर्णन आणि पुरावे"),
    "Attach Photo / Sensor Data": ("फोटो / सेंसर डेटा संलग्न करें", "फोटो / सेन्सर डेटा जोडा"),
    "Select District...": ("जिला चुनें...", "जिल्हा निवडा..."),
    "Enter issue details...": ("समस्या का विवरण दर्ज करें...", "समस्या तपशील प्रविष्ट करा..."),
    "e.g. 5,000 residents": ("उदा. 5,000 निवासी", "उदा. 5,000 रहिवासी"),
    "Select Priority Level": ("प्राथमिकता स्तर चुनें", "प्राधान्य पातळी निवडा"),

    # General Words & Short Phrases
    "Learn More": ("और जानें", "अधिक जाणून घ्या"),
    "Read Documentation": ("दस्तावेज़ पढ़ें", "दस्तऐवज वाचा"),
    "Get Started": ("शुरू करें", "सुरू करा"),
    "Contact Support": ("सहायता से संपर्क करें", "सपोर्टशी संपर्क साधा"),
    "View Details": ("विवरण देखें", "तपशील पाहा"),
    "Download Certificate": ("प्रमाणपत्र डाउनलोड करें", "प्रमाणपत्र डाउनलोड करा"),
    "Verified Escrow": ("सत्यापित एस्क्रो", "सत्यापित एस्क्रॉ"),
    "AI Matched": ("एआई मिलान", "AI जुळणी"),
    "Pending Signoff": ("लंबित हस्ताक्षर", "प्रलंबित स्वाक्षरी"),
    "Active Pilot": ("सक्रिय पायलट", "सक्रिय पायलट"),
    "Phase 1: Foundation": ("चरण 1: नींव", "टप्पा 1: पाया"),
    "Phase 2: Scale": ("चरण 2: स्केल", "टप्पा 2: विस्तार"),
    "Phase 3: Integration": ("चरण 3: एकीकरण", "टप्पा 3: एकत्रीकरण"),
    "Phase 4: Sovereign Grid": ("चरण 4: संप्रभु ग्रिड", "टप्पा 4: संप्रभु ग्रिड"),
    "Q1 2025": ("Q1 2025", "Q1 2025"),
    "Q3 2025": ("Q3 2025", "Q3 2025"),
    "Q1 2026": ("Q1 2026", "Q1 2026"),
    "Q4 2026": ("Q4 2026", "Q4 2026")
}

for k, val in additional_translations.items():
    if isinstance(val, tuple):
        v_hi, v_mr = val
    else:
        v_hi, v_mr = val, val
    hi[k] = v_hi
    mr[k] = v_mr

# Write updated dictionaries back
with open('scratch/dict_hi.json', 'w', encoding='utf-8') as f:
    json.dump(hi, f, ensure_ascii=False, indent=2)

with open('scratch/dict_mr.json', 'w', encoding='utf-8') as f:
    json.dump(mr, f, ensure_ascii=False, indent=2)

print("Updated Dictionary sizes -> HI:", len(hi), "MR:", len(mr))
