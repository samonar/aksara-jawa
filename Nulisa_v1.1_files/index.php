/*!
* trans.js
* https://bennylin.github.com/transliterasijawa
*
* Copyright 2013, Bennylin @bennylin
* Dual licensed under the MIT or GPL Version 2 licenses.
* Released under the MIT, BSD, and GPL Licenses.
*
* Date: 7 Mei 2013 - v 1.0
* Date: 25 Juli 2013 - v 1.1
*
*
* Derived from Hindi Transliteration by Markandey Singh @markandey
* http://www.purplegene.com/static/HindiTranslitration.html
*/

var vowelPrev = false;

/***************************
Function SuperTrim, findstr
trim string, menemukan karakter di dalam string
****************************/
function SuperTrim(str) {
    return str.replace(/^\s*|\s*$/g,'').replace(/\s+/g,' ');
}
function findstr(str,tofind){
    for (var i = 0; i < str.length; i++)
        if (str[i] == tofind)
            return true;
    return false;
}
/***************************
Function isDigit, isPunct, isVowel
cek apakah digit, tanda baca, atau huruf vokal (a, e/è/é, i, o, u, ě/ê, ô, ā/ī/ū/ō)
****************************/
function isDigit( /*char*/ a) {
    var str = "0123456789";
    return findstr(str,a);
}
function isPunct( /*char*/ a) {
    var str = ",.><?/+=-_}{[]*&^%$#@!~`\"\\|:;()";
    return findstr(str,a);
}
function isVowel( /*char*/ a) {
    var str = "AaEeÈèÉéIiOoUuÊêĚěXxôāīūō";
   return findstr(str,a);
}
function isConsonant( /*char*/ a) {
    var str = "BCDfGHJKLMNPRSTVWYZbcdfghjklmnpqrstvwxyz";//QXqx are special chars
   return findstr(str,a);
}
/***************************
Function isSpecial, isHR, isLW
cek apakah karakter spesial (bikonsonan/cakra-pengkal/layar-cecak-wignyan/panjingan)
****************************/
function isSpecial( /*char*/ a) {
    var str = "GgHhRrYy"; //untuk bikonsonan th, dh, ng (nga dan cecak), ny, -r- (cakra), -y- (pengkal)
   return findstr(str,a);
}
    
function isHR( /*char*/ a) {
    var str = "HhRr";//untuk layar dan wignyan
    return findstr(str,a);
}    

function isLW( /*char*/ a) {
    var str = "LlWw";//untuk panjingan ("ng" dapat diikuti "g", "r"/cakra, "y"/pengkal, dan "w" atau "l"/panjingan)
    return findstr(str,a);
}

function isCJ( /*char*/ a) {
    var str = "CcJj";//untuk anuswara -nj- dan -nc-
    return findstr(str,a);
}
/***************************
Function GetMatra
apabila huruf vokal, return matra (sandhangan swara)
****************************/
function GetMatra(str) {
    var i = 0;
    if (str.length < 1) {
        return "꧀";
    }
    while (str[i] == 'h') {
        i++;
        if (i >= str.length) {
            break;
        }
    }
    if (i < str.length) {
        str = str.substring(i);
    }
    var matramap1={ "aa":'ꦴ', "e":'ꦺ', "è":'ꦺ', "é":'ꦺ', "i":'ꦶ', "ii":'ꦷ', "u":'ꦸ', "uu":'ꦹ', "o":'ꦺꦴ', "x":"ꦼ", "ě":"ꦼ", "ê":"ꦼ", "ô":"", "ā":"ꦴ", "ī":"ꦷ", "ū":"ꦹ", "ō":"ꦼꦴ",
     "A":'ꦄ', "E":'ꦌ', "È":'ꦌ', "É":'ꦌ', "I":'ꦆ', "U":'ꦈ', "O":'ꦎ', "X":"ꦄꦼ", "Ě":"ꦄꦼ", "Ê":"ꦄꦼ", 
     "eo":'ꦺꦎ', "èo":'ꦺꦎ', "éo":'ꦺꦎ', "ai":'ꦲꦶ', "au":'ꦲꦸ', "ae":'ꦲꦺ', "ao":'ꦲꦺꦴ' }
    var matramap2={ "aa":'ꦴ', "e":'ꦼ', "è":'ꦺ', "é":'ꦺ', "i":'ꦶ', "ii":'ꦷ', "u":'ꦸ', "uu":'ꦹ', "o":'ꦺꦴ', "x":"ꦼ", "ě":"ꦼ", "ê":"ꦼ", "ô":"", "ā":"ꦴ", "ī":"ꦷ", "ū":"ꦹ", "ō":"ꦼꦴ",
     "A":'ꦄ', "E":'ꦌ', "È":'ꦌ', "É":'ꦌ', "I":'ꦆ', "U":'ꦈ', "O":'ꦎ', "X":"ꦄꦼ", "Ě":"ꦄꦼ", "Ê":"ꦄꦼ", 
     "èo":'ꦺꦎ', "éo":'ꦺꦎ', "ai":'ꦲꦶ', "au":'ꦲꦸ', "ae":'ꦲꦼ', "ao":'ꦲꦺꦴ' }
    var matramap, mode;
    var modeTranslit = document.getElementsByName("mode");
    for(var rad in modeTranslit) {
      if(modeTranslit[rad].checked)
        mode = modeTranslit[rad].value;
    }
    if(mode == "kopas")
	matramap = matramap2;
    else //if(mode == "ketik")
	matramap = matramap1;

    if(matramap[str]!==undefined){
        return matramap[str];
    }
    return "";
}
/***************************
Function GetShift
apabila huruf bikonsonan, return karakter khusus
****************************/
function GetShift(str) {
    str = str.toLowerCase();
    if (str.indexOf("th") == 0) { //suku kata diawali 'th'
        if (str.indexOf("thr") == 0) { //cakra
        return {
            "CoreSound": "ꦛꦿ",
            "len": 3
        };
        } else if (str.indexOf("thl") == 0) { //thl
        return {
            "CoreSound": "ꦛ꧀ꦭ",
            "len": 3
        };
        } else {
        return {
            "CoreSound": "ꦛ",
            "len": 2
        };
        }
    } else if (str.indexOf("dh") == 0) { //suku kata diawali 'dh'
        if (str.indexOf("dhr") == 0) { //cakra
        return {
            "CoreSound": "ꦝꦿ",
            "len": 3
        };
        } else if (str.indexOf("dhl") == 0) { //dhl
        return {
            "CoreSound": "ꦝ꧀ꦭ",
            "len": 3
        };
        } else {
        return {
            "CoreSound": "ꦝ",
            "len": 2
        };
        }
    } else if (str.indexOf("hy") == 0) { //hyang
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "ꦲꦾ",
            "len": 2
        };
    } else if (str.indexOf("hh") == 0) { //hh
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "ꦃꦲ",
            "len": 2 
        };
    } else if (str.indexOf("rh") == 0) { //rh (kata berakhiran r diikuti kata berawalan h
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "ꦂꦲ",
            "len": 2 
        };
    } else if (str.indexOf("kh") == 0) { //kh (aksara murda)
        return {
            "CoreSound":  "ꦑ",
            "len": 2 
        };
    } else if (str.indexOf("gh") == 0) { //gh (aksara murda)
        return {
            "CoreSound":  "ꦓ",
            "len": 2 
        };
    } else if (str.indexOf("ch") == 0) { //ch (aksara murda)
        return {
            "CoreSound":  "ꦖ",
            "len": 2 
        };
    } else if (str.indexOf("jh") == 0) { //jh (aksara murda)
        return {
            "CoreSound":  "ꦙ",
            "len": 2 
        };
    } else if (str.indexOf("ṭh") == 0) { //ṭh (aksara murda)
        return {
            "CoreSound":  "ꦜ",
            "len": 2 
        };
    } else if (str.indexOf("ḍh") == 0) { //ḍh (aksara murda)
        return {
            "CoreSound":  "ꦞ",
            "len": 2 
        };
    } else if (str.indexOf("ph") == 0) { //ph (aksara murda)
        return {
            "CoreSound":  "ꦦ",
            "len": 2 
        };
    } else if (str.indexOf("bh") == 0) { //bh (aksara murda)
        return {
            "CoreSound":  "ꦨ",
            "len": 2 
        };
    } else if (str.indexOf("sh") == 0) { //sh (aksara murda)
        return {
            "CoreSound":  "ꦯ",
            "len": 2 
        };
    } else if (str.indexOf("h") == 1) { //h
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "꧀ꦲ",
            "len": 2
        };
    } else if (str.indexOf("h") > 1) { //suku kata memiliki konsonan 'h' yang tidak di awal suku
        var sound = "";
        var len = 0;
        var index = 0;
        for (index = 0; index < str.length; index++) {
            var c = str[index];
            if (!isVowel(c)) {
                sound = sound + ResolveCharacterSound(c);
                len++;
            }
            else {
                break;
            }
        }
        return {
            "CoreSound": sound,
            "len": len
        };
    }
 
    //nga
    if (str.indexOf("ng") == 0) { //suku kata diawali 'ng'
        if (str.indexOf("ngr") == 0) { //cakra
        return {
            "CoreSound": "ꦔꦿ",
            "len": 3
        };
        } else if (str.indexOf("ngy") == 0) { //pengkal
        return {
            "CoreSound": "ꦔꦾ",
            "len": 3
        };
        } else if (str.indexOf("nggr") == 0) { //nggronjal
        return {
            "CoreSound": "ꦔ꧀ꦒꦿ",
            "len": 4
        };
        } else if (str.indexOf("nggl") == 0) { //nggl-
        return {
            "CoreSound": "ꦔ꧀ꦒ꧀ꦭ",
            "len": 4
        };
        } else if (str.indexOf("nggw") == 0) { //nggw-, munggwing
        return {
            "CoreSound": "ꦔ꧀ꦒ꧀ꦮ",
            "len": 4
        };
        } else if (str.indexOf("nggy") == 0) { //nggy-, anggyat
        return {
            "CoreSound": "ꦔ꧀ꦒꦾ",
            "len": 4
        };
        } else if (str.indexOf("ngg") == 0) { //ngg
        return {
            "CoreSound": "ꦔ꧀ꦒ",
            "len": 3
        };/*
        } else if (str.indexOf("ngng") == 0) { //ngng
        return {
            "CoreSound": "ꦔ꧀ꦔ",
            "len": 4
        };*/
        } else if (str.indexOf("ngl") == 0) { //ngl, e.g. ngluwari
        return {
            "CoreSound": "ꦔ꧀ꦭ",
            "len": 3
        };
        } else if (str.indexOf("ngw") == 0) { //ngw, e.g. ngwiru
        return {
            "CoreSound": "ꦔ꧀ꦮ",
            "len": 3
        };
        } else {
        return {
            "CoreSound": "ꦁ",
            "len": 2
        };
        }
    } else if (str.indexOf("rg") == 0) { //'rg', e.g. amarga
        return {
            "CoreSound": "ꦂꦒ",
            "len": 2
        };
    } else if (str.indexOf("g") == 1) { //g
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "꧀ꦒ",
            "len": 2
        };
    } else if (str.indexOf("g") > 1) { //suku kata memiliki konsonan 'g' yang tidak di awal suku
        var sound = "";
        var len = 0;
        var index = 0;
        for (index = 0; index < str.length; index++) {
            var c = str[index];
            if (!isVowel(c)) {
                sound = sound + ResolveCharacterSound(c);
                len++;
            }
            else {
                break;
            }
        }
        return {
            "CoreSound": sound,
            "len": len
        };
    }
 
    //nya
    if (str.indexOf("ny") == 0) { //suku kata diawali 'ny'
        if (str.indexOf("nyr") == 0) { //cakra
        return {
            "CoreSound": "ꦚꦿ",
            "len": 3
        };
        } else if (str.indexOf("nyl") == 0) { //nyl, e.g. nylonong
        return {
            "CoreSound": "ꦚ꧀ꦭ",
            "len": 3
        };
        } else {
        return {
            "CoreSound": "ꦚ",
            "len": 2
        };
        }
    } else if (str.indexOf("ry") == 0) { //'ry', e.g. Suryati, Wiryadi
        return {
            "CoreSound": "ꦂꦪ",
            "len": 2
        };
    } else if (str.indexOf("y") == 1) { //pengkal
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "ꦾ",
            "len": 2
        };
    } else if (str.indexOf("y") > 1) { //suku kata memiliki konsonan 'y' yang tidak di awal suku
        var sound = "";
        var len = 0;
        var index = 0;
        for (index = 0; index < str.length; index++) {
            var c = str[index];
            if (!isVowel(c)) {
                sound += ResolveCharacterSound(c);
                len++;
            }
            else {
                break;
            }
        }
        return {
            "CoreSound": sound,
            "len": len
        };
    }

    if (str.indexOf("hr") == 0) { //hr-
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "ꦲꦿ",
            "len": 2
        };
    } else if (str.indexOf("rr") == 0) { //rr
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "ꦂꦫ",
            "len": 2 
        };
    } else if (str.indexOf("r") == 1) { //cakra
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "ꦿ",
            "len": 2
        };
    } else if (str.indexOf("r") > 1) { //suku kata memiliki konsonan 'r' yang tidak di awal suku
        var sound = "";
        var len = 0;
        var index = 0;
        for (index = 0; index < str.length; index++) {
            var c = str[index];
            if (!isVowel(c)) {
                sound += ResolveCharacterSound(c);
                len++;
            }
            else {
                break;
            }
        }
        return {
            "CoreSound": sound,
            "len": len
        };
    }

   //panjingan -l
   if (str.indexOf("ll") == 0) { //ll
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "ꦭ꧀ꦭ",
            "len": 2 
        };
    } else if (str.indexOf("rl") == 0) { //rl (kata berakhiran r diikuti kata berawalan l
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "ꦂꦭ",
            "len": 2 
        };
    } else if (str.indexOf("hl") == 0) { //hl 
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "ꦲ꧀ꦭ",
            "len": 2 
        };
    } else if (str.indexOf("l") == 1) { //l
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "꧀ꦭ",
            "len": 2
        };
    } else if (str.indexOf("l") > 1) { //suku kata memiliki konsonan 'l' yang tidak di awal suku//panjingan
        var sound = "";
        var len = 0;
        var index = 0;
        for (index = 0; index < str.length; index++) {
            var c = str[index];
            if (!isVowel(c)) {
                sound = sound + ResolveCharacterSound(c);
                len++;
            }
            else {
                break;
            }
        }
        return {
            "CoreSound": sound,
            "len": len
        };
    }

   //panjingan -w
   if (str.indexOf("rw") == 0) { //rw
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "ꦂꦮ",
            "len": 2 
        };
    } else if (str.indexOf("hw") == 0) { //hw 
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "ꦲ꧀ꦮ",
            "len": 2 
        };
    } else if (str.indexOf("w") == 1) { //w
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "꧀ꦮ",
            "len": 2
        };
    } else if (str.indexOf("w") > 1) { //suku kata memiliki konsonan 'w' yang tidak di awal suku//panjingan
        var sound = "";
        var len = 0;
        var index = 0;
        for (index = 0; index < str.length; index++) {
            var c = str[index];
            if (!isVowel(c)) {
                sound = sound + ResolveCharacterSound(c);
                len++;
            }
            else {
                break;
            }
        }
        return {
            "CoreSound": sound,
            "len": len
        };
    }

   if (str.indexOf("nc") == 0) { //nc
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "ꦚ꧀ꦕ",
            "len": 2 
        };
    } else if (str.indexOf("c") == 1) { //c
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "꧀ꦕ",
            "len": 2
        };
    } else if (str.indexOf("c") > 1) {
        var sound = "";
        var len = 0;
        var index = 0;
        for (index = 0; index < str.length; index++) {
            var c = str[index];
            if (!isVowel(c)) {
                sound = sound + ResolveCharacterSound(c);
                len++;
            }
            else {
                break;
            }
        }
        return {
            "CoreSound": sound,
            "len": len
        };
    }

   if (str.indexOf("nj") == 0) { //nj
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "ꦚ꧀ꦗ",
            "len": 2 
        };
    } else if (str.indexOf("rj") == 0) { //'rj'
        return {
            "CoreSound": "ꦂꦗ",
            "len": 2
        };
    } else if (str.indexOf("j") == 1) { //j
        return {
            "CoreSound": "" + GetCoreSound(str[0]).CoreSound + "꧀ꦗ",
            "len": 2
        };
    } else if (str.indexOf("j") > 1) {
        var sound = "";
        var len = 0;
        var index = 0;
        for (index = 0; index < str.length; index++) {
            var c = str[index];
            if (!isVowel(c)) {
                sound = sound + ResolveCharacterSound(c);
                len++;
            }
            else {
                break;
            }
        }
        return {
            "CoreSound": sound,
            "len": len
        };
    }

    return {
        "CoreSound": null,
        "len": 1
    };
}
/***************************
Function GetCoreSound, GetSpecialSound
return aksara nglegana maupun aksara istimewa (f/v/z/pangkon)
****************************/
function GetCoreSound(str) {
    var consonantMap1 = {
        "A":"ꦄ", //A
        "B":"ꦧ", //B
        "C":"ꦕ", //C
        "D":"ꦢ", //D
        "E":"ꦌ", //E
        "F":"ꦥ꦳", //F
        "G":"ꦒ", //G
        "H":"ꦲ", //H
        "I":"ꦆ", //I
        "J":"ꦗ", //J
        "K":"ꦏ", //K
        "L":"ꦭ", //L
        "M":"ꦩ", //M
        "N":"ꦤ", //N
        "O":"ꦎ", //O
        "P":"ꦥ", //P
        "Q":"꧀", //Q
        "R":"ꦂ", //R
        "S":"ꦱ", //S
        "T":"ꦠ", //T
        "U":"ꦈ", //U
        "V":"ꦮ꦳", //V
        "W":"ꦮ", //W
        "X":"ꦼ", //X
        "Y":"ꦪ", //Y
        "Z":"ꦗ꦳", //Z
        "a":"ꦲ", //a
        "b":"ꦧ", //b
        "c":"ꦕ", //c
        "d":"ꦢ", //d
        "e":"ꦲꦺ", //e
        "f":"ꦥ꦳", //f
        "g":"ꦒ", //g
        "h":"ꦃ", //h
        "i":"ꦲꦶ", //i
        "j":"ꦗ", //j
        "k":"ꦏ", //k
        "l":"ꦭ", //l
        "m":"ꦩ", //m
        "n":"ꦤ", //n
        "o":"ꦲꦺꦴ", //o
        "p":"ꦥ", //p
        "q":"꧀", //q
        "r":"ꦂ", //r
        "s":"ꦱ", //s
        "t":"ꦠ", //t
        "u":"ꦲꦸ", //u
        "v":"ꦮ꦳", //v
        "w":"ꦮ", //w
        "x":"ꦲꦼ", //x
        "y":"ꦪ", //y
        "z":"ꦗ꦳", //z
        "È":"ꦌ", //È
        "É":"ꦌ", //É
        "Ê":"ꦄꦼ", //Ê
        "Ě":"ꦄꦼ", //Ě
        "è":"ꦲꦺ", //è
        "é":"ꦲꦺ", //é
        "ê":"ꦲꦼ", //ê
        "ě":"ꦲꦼ", //ě
        "ô":"ꦲ", //ô
        "ñ":"ꦘ",
        "ṇ":"ꦟ",
        "ḍ":"ꦝ",
        "ṭ":"ꦛ",
        "ṣ":"ꦰ"
    }
    var consonantMap2 = {
        "A":"ꦄ", //A
        "B":"ꦨ", //B
        "C":"ꦖ", //C
        "D":"ꦣ", //D
        "E":"ꦌ", //E
        "F":"ꦦ꦳", //F
        "G":"ꦓ", //G
        "H":"ꦲ꦳", //H
        "I":"ꦆ", //I
        "J":"ꦙ", //J
        "K":"ꦑ", //K
        "L":"ꦭ", //L
        "M":"ꦩ", //M
        "N":"ꦟ", //N
        "O":"ꦎ", //O
        "P":"ꦦ", //P
        "Q":"꧀", //Q
        "R":"ꦬ", //R
        "S":"ꦯ", //S
        "T":"ꦡ", //T
        "U":"ꦈ", //U
        "V":"ꦮ꦳", //V
        "W":"ꦮ", //W
        "X":"ꦼ", //X
        "Y":"ꦪ", //Y
        "Z":"ꦗ꦳", //Z
        "a":"ꦄ", //a
        "b":"ꦧ", //b
        "c":"ꦕ", //c
        "d":"ꦢ", //d
        "e":"ꦌ", //e
        "f":"ꦥ꦳", //f
        "g":"ꦒ", //g
        "h":"ꦃ", //h
        "i":"ꦆ", //i
        "j":"ꦗ", //j
        "k":"ꦏ", //k
        "l":"ꦭ", //l
        "m":"ꦩ", //m
        "n":"ꦤ", //n
        "o":"ꦎ", //o
        "p":"ꦥ", //p
        "q":"꧀", //q
        "r":"ꦂ", //r
        "s":"ꦱ", //s
        "t":"ꦠ", //t
        "u":"ꦈ", //u
        "v":"ꦮ꦳", //v
        "w":"ꦮ", //w
        "x":"ꦼ", //x
        "y":"ꦪ", //y
        "z":"ꦗ꦳", //z
        "È":"ꦌ", //È
        "É":"ꦌ", //É
        "Ê":"ꦄꦼ", //Ê
        "Ě":"ꦄꦼ", //Ě
        "è":"ꦌ", //è
        "é":"ꦌ", //é
        "ê":"ꦄꦼ", //ê
        "ě":"ꦄꦼ", //ě
        "ṇ":"ꦟ",
        "ḍ":"ꦝ",
        "ṭ":"ꦛ",
        "ṣ":"ꦰ"
    }
    var consonantMap, murda;
    var modeMurda = document.getElementsByName("murda");
    for(var rad in modeMurda) {
      if(modeMurda[rad].checked)
        murda = modeMurda[rad].value;
    }
    if(murda == "pakai")
	consonantMap = consonantMap2;
    else //if(murda == "tidak")
	consonantMap = consonantMap1;

    var h_shift = GetShift(str);
    var core = str;

    if (h_shift["CoreSound"] == null) {

        if (consonantMap[str.charAt(0)]) core = consonantMap[str.charAt(0)];
        return {
            "CoreSound": core,
            "len": 1
        };
    } else {
        return h_shift;
    }
}
function GetSpecialSound(str) {
    specialsoundMap = { "f":"ꦥ꦳꧀", "v":"ꦮ꦳꧀", "z":"ꦗ꦳꧀", "ś":"ꦯ", "q":"꧀"/*pangkon*/ }
    if(specialsoundMap[str]!==undefined){
        return specialsoundMap[str];
    }
    return null;
}
/***************************
Function ResolveCharacterSound
return tanda baca, digit, vokal, maupun nglegana+pangkon
****************************/
function ResolveCharacterSound( /*char*/ c) {
    var str = "" + c;
    var len = 0;
    if (isDigit(c)) {
        return "" + ('꧇' + (c - '0'));
    } else if (isHR(str[0])) {
        return "" + GetCoreSound(str).CoreSound; //layar dan wignyan
    } else if (isCJ(str[1])) {
        return "" + GetCoreSound(str).CoreSound + "꧀"; //anuswara
    } else if (isConsonant(str[0])) {
        return "" + GetCoreSound(str).CoreSound + "꧀";
    } else { //if (isVowel(str[0])) {
        return "" + GetCoreSound(str).CoreSound;
    }
/**/
}
/***************************
Function GetSound
fungsi yang mentransliterasi masing-masing suku kata
****************************/
function GetSound(str) {
    var len = 0;
    str = SuperTrim(str);
    if (str == null || str == "") {
        return "";
    } 
        var SpecialSound = GetSpecialSound(str);
    
    if (SpecialSound != null && str.length == 1) {
        return SpecialSound;
    } 
    if (str.length == 1) {
        return ResolveCharacterSound(str[0]);
    } else {
        var core_sound = GetCoreSound(str);
        //return "1"+core_sound.CoreSound+"2";
        var matra = "";
        var konsonan = "";
            if (core_sound.len >= 1) {
                matra = GetMatra(str.substring(core_sound.len)); //aeiou (suku, wulu, pepet, taling, taling tarung, dll.)
                /*if () {

                } else {

                }*/
            } else {
                matra = ""; }

        if (str.indexOf("ngg") == 0) { //ngg-
            if (vowelPrev) konsonan = "ꦁꦒ";//<vowel>ngg-, e.g. tunggal
            else konsonan = "ꦔ꧀ꦒ";//<nonvowel>ngg-, i.e. nggambar
        } else if (core_sound.CoreSound == "ꦤꦚ꧀ꦕ") { // -nc-
            konsonan = "ꦚ꧀ꦕ";//-nyc-
        } else if (core_sound.CoreSound == "ꦤꦚ꧀ꦗ") { // -nj-
            konsonan = "ꦚ꧀ꦗ";//-nyj-
        } else if (findstr(core_sound.CoreSound,'ꦾ') && matra == "꧀") { // pengkal
            konsonan = core_sound.CoreSound; matra = "";//-y-
        } else if (findstr(core_sound.CoreSound,'ꦿ') && matra == "꧀") { // cakra
            konsonan = core_sound.CoreSound; matra = "";//-r-
        } else if (findstr(core_sound.CoreSound,'ꦿ') && matra == "ꦼ") { // cakra keret
            if ((str[0] == "n" && str[1] == "y") || ((str[0] == "t" || str[0] == "d") && str[1] == "h")) {
               konsonan = GetCoreSound(str[0]+str[1]).CoreSound + "ꦽ"; matra = "";//nyrê-, thrê-, dhrê-
            } else if (str[0] == "n" && str[1] == "g") {
               if (str[2] == "g") konsonan = "ꦔ꧀ꦒꦽ"; else konsonan = "ꦔꦽ"; matra = "";//nggrê-/ngrê-
            } else { konsonan = GetCoreSound(str[0]).CoreSound + "ꦽ"; matra = "";//-rê- 
            }
        } else if (findstr(core_sound.CoreSound, 'ꦭ') && matra == "ꦼ") { // nga lelet
            if ((str[0] == "n" && str[1] == "y") || ((str[0] == "t" || str[0] == "d") && str[1] == "h")) {
               konsonan = GetCoreSound(str[0]+str[1]).CoreSound + "꧀ꦭꦼ"; matra = "";//nylê-, thlê-, dhlê-
            } else if (str[0] == "n" && str[1] == "g") {
               if (str[2] == "g") konsonan = "ꦔ꧀ꦒ꧀ꦭꦼ"; else konsonan = "ꦔ꧀ꦭꦼ"; matra = "";//ngglê-/nglê-
            } else if (str[0] == "l") {
               konsonan = "ꦊ"; matra = "";//-lê- 
            } else { konsonan = GetCoreSound(str[0]).CoreSound + "꧀ꦭꦼ"; matra = "";//-lê- 
            }
        } else if (core_sound.CoreSound == 'ꦛꦿ' || core_sound.CoreSound == 'ꦝꦿ' || core_sound.CoreSound == 'ꦔꦿ' || core_sound.CoreSound == 'ꦚꦿ') { // i.e. nyruput
            konsonan = core_sound.CoreSound;
            if (matra == "꧀") matra = "";
        } else if (core_sound.CoreSound == "ꦭꦭ꧀ꦭ") { // -ll-
            konsonan = "ꦭ꧀ꦭ";//double -l-
        } else if (core_sound.CoreSound == "ꦂꦂꦫ") { // -rr-
            konsonan = "ꦂꦫ";//double -r-
        } else if (core_sound.CoreSound == "ꦂꦂꦲ") { // -rh-
            konsonan = "ꦂꦲ";//-rh-
        } else if (core_sound.CoreSound == "ꦂꦂꦭ") { // -rl-
            konsonan = "ꦂꦭ";//-rl-
        } else if (core_sound.CoreSound == "ꦂꦂꦮ") { // -rw-
            konsonan = "ꦂꦮ";//-rw-
        } else if (core_sound.CoreSound == "ꦃꦃꦲ") { // -hh-
            konsonan = "ꦃꦲ";//double -h-
        } else if (core_sound.CoreSound == "ꦃꦲꦾ") { // hyang
            konsonan = "ꦲꦾ";//hyang
        } else if (core_sound.CoreSound == "ꦃꦲꦿ") { // hr-
            if (matra == "ꦼ") konsonan = "ꦲꦽ";//hr-
            else konsonan = "ꦲꦿ";//hr-
        } else if (core_sound.CoreSound == 'ꦃ' && matra == "꧀") { // wignyan - 12 April
            konsonan = "ꦲ"; //ha
        } else if (core_sound.CoreSound == 'ꦃ' && matra != "꧀") { // wignyan
            konsonan = "ꦲ"; //ha
        } else if (core_sound.CoreSound == 'ꦂ' && matra == "ꦼ") { // pa cerek
            konsonan = "ꦉ"; matra = "";//rê
        } else if (core_sound.CoreSound == 'ꦂ' && matra != "꧀") { // layar
            konsonan = "ꦫ"; //ra
        } else if (core_sound.CoreSound == 'ꦁ' && matra != "꧀") { // cecak
            konsonan = "ꦔ"; //nga
        } else if (core_sound.CoreSound == 'ꦁ' && matra == "꧀") { // cecak
            konsonan = "ꦁ"; matra = "";//cecak
        } else {
            konsonan = core_sound.CoreSound; 
        }
        return "" + konsonan + matra;
    }
}
/***************************
Function DoTransliterate
fungsi utama yang dipanggil (main function)
****************************/
function DoTransliterate(str) {
    var i = 0;
    var ret = "";
    var pi = 0; //?offset
    var vowelFlag = false, angkaFlag = false, cecakFlag=false;
    var angka = {"0":'꧐',"1":'꧑',"2":'꧒',"3":'꧓',"4":'꧔',"5":'꧕',"6":'꧖',"7":'꧗',"8":'꧘',"9":'꧙'}
    str = SuperTrim(str);
    while (i < str.length) {
        if ((isSpecial(str[i]) || isLW(str[i]) || isCJ(str[i])) && !vowelFlag) {
            //i++;
        } else if ((str[i] == 'h' && vowelFlag) || (!isVowel(str[i]) && i > 0) || (str[i] == ' ') || isPunct(str[i]) || isDigit(str[i]) || ((i - pi) > 5)) {
            if (!isDigit(str[i]) && angkaFlag) { //turn off the flag
                ret+="꧇​";// with zws
                angkaFlag = false;
            }
            if (pi < i) {
                if (cecakFlag && GetSound(str.substring(pi, i)) == "ꦁ") {
                    cecakFlag = false; 
                    ret += "ꦔ꧀ꦔ"; 
                } else if (!cecakFlag && GetSound(str.substring(pi, i)) == "ꦁ") { 
                    cecakFlag = true; 
                    ret += "ꦁ"; 
                } else {
                    cecakFlag = false; 
                    ret += GetSound(str.substring(pi, i)); 
                }
            }
            if (str[i] == ' ') { 
                    var spasi, modeSpasi;
                    var pakaiSpasi = document.getElementsByName("spasi");
                    for(var rad in pakaiSpasi) {
                      if(pakaiSpasi[rad].checked)
                        modeSpasi = pakaiSpasi[rad].value;
                    }
                    if(modeSpasi == "without") {
                	spasi = ''; }
                    else { //if(mode == "with")
                	spasi = '​'; // zero-width space 
                	//spasi = ' '; }//hair space http://en.wikipedia.org/wiki/Space_(punctuation)#Spaces_in_Unicode
                    }
                ret += spasi; }
            if (isPunct(str[i])) {
             if (str[i] == '.') {
                ret += "꧉​"; //titik+zero-width space
                pi = i + 1; 
             } else if (str[i] == ',') {
                ret += "꧈​"; //koma+zero-width space
                pi = i + 1; 
             } else if (str[i] == '|') {
                ret += "꧋"; pi = i + 1;
             } else if (str[i] == '(') {
                ret += "꧌"; pi = i + 1;
             } else if (str[i] == ')') {
                ret += "꧍​"; pi = i + 1;// with zws
             } else if (str[i] == '-') {//tanda hubung
                ret += "​"; pi = i + 1;
             } else if (str[i] == '?' || str[i] == '!' || str[i] == '"' || str[i] == "'") {//tanda tanya/seru/petik
                ret += "​"; //zero-width space
                pi = i + 1;
             } else {
                ret += str[i]; pi = i + 1;
             }
            } else if (isDigit(str[i])) {
                if (!angkaFlag) ret+="꧇";
                ret += angka[str[i]];
                angkaFlag = true;
                pi = i + 1;
            } else {
                pi = i; }
            vowelFlag = false;
        } else if (isVowel(str[i]) && str[i] != 'h') {
            if (!isDigit(str[i]) && angkaFlag) { //turn off the flag
                ret+="꧇​"; //with zws
                angkaFlag = false;
            }
            vowelFlag = true; }
        if (pi > 0 && isVowel(str[pi-1])) {//<vowel>ngg
            vowelPrev = true;
        }
        else vowelPrev = false;
        i++;
    } //endwhile
    if (pi < i) {
        ret += GetSound(str.substring(pi, i)); }
    return SuperTrim(ret);
}