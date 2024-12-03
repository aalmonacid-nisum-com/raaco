/**
* Funciones de Validacion
*
* @version 0.1.3
*/

/*
 * Historial:
 * LCC.14082002 Agregadas la funcion isMoney(valor, esFormatoIngles)
 * LCC.17042002 Agregadas las funciones isSelected, MonthAdd, Repeat, LPad.
 * LCC.28012002 Agregada funcion isTime.
 * LCC.14012002 Agregadas funciones DayAdd, Str2Date, DateCmp.
 * LCC.10012002 Agregadas funciones isDouble, isAlpha.
 * LCC.04122001 Agregadas las funciones RTrim, LTrim, Trim, isModule11 (Reemplaza a isRUT).
 * LCC.22112001 Correción funcion email, no acepta "." en el usuario.
 */

// Empty: devuelve verdadero si value es vacio
function Empty(value) {
    var pattern=new RegExp("^[ ]*$");
    return value.match(pattern) || value.length==0;
}

// isInteger: devuelve verdero si value es un entero
function isInteger(value) {
    var pattern=new RegExp("^[0-9]+$");
    return value.match(pattern);
}

// isDouble: devuelve verdero si value es un double
function isDouble(value) {
    var pattern=new RegExp("^[0-9]+(\\.[0-9]+){0,1}$");
    return value.match(pattern);
}

// isMoney: devuelve verdadero si un valor es tipo moneda
function isMoney(value, isEnglish) {

	if (isEnglish) {
	    var pattern=new RegExp("^[0-9]+(\\.[0-9]+){0,1}$");
	    return value.match(pattern);
	} else {
	    var pattern=new RegExp("^[0-9]+(,[0-9]+){0,1}$");
	    return value.match(pattern);
	}

}

// isMail: devuelve verdadero si value es una direccion de correo valida
function isMail(value) {
    var pattern=new RegExp("^([a-zA-Z0-9_\\-]+\\.{0,1})+@([a-zA-Z0-9_\\-]+\\.)+[a-zA-Z0-9_\\-]+$");
    return value.match(pattern);
}

// isDate: devuelve verdadero si value es una fecha valida en formato dd/mm/aaaa
function isDate(value) {
    var pattern1=new RegExp("^(0[0-9]|[1-2][0-9]|30|31)/(0[13-9]|1[0-2])/[1-9][0-9][0-9][0-9]");
    var pattern2=new RegExp("^(0[0-9]|[1-2][0-9])/(0[0-9]|1[0-2])/[1-9][0-9][0-9][0-9]");

    if (value.match(pattern1) || value.match(pattern2)) {
        if (parseInt(value.substr(6,4))%4!=0 && parseInt(value.substr(3,2))==2 && parseInt(value.substr(0,2))==29) {
            return false;
        } else return true;
    } else return false;
}

// isAlpha: devuelve verdadero si la cadena contiene solo caracteres alfabeticos o espacios
function isAlpha(value) {
	var pattern=new RegExp("^[a-zA-Z\\s]+$");
    return value.match(pattern);
}

// isModule11: devuelve verdadero si value es valido para el modulo 11
function isModule11(value) {
    var pattern=new RegExp("^(([0-9]{1,2}\\.[0-9]{3}\\.[0-9]{3})|([0-9]{7,8}))\\-([0-9K])$", "i");
    var pattern_point=new RegExp("\\.", "g");
    var pattern_dv=new RegExp("([0-9]+)\\-([0-9K])", "i");

    value=Trim(value);
    if (value.match(pattern)) {
        value=value.replace(pattern_point, "");
        if (value.match(pattern_dv)) {
            number=new String(RegExp.$1);
            dv=new String(RegExp.$2);
            sum = 0;
            mul = 2;
            for (i = number.length - 1 ; i >= 0; i--) {
                sum += number.charAt(i) * mul;
                mul == 7 ? mul = 2:mul++;
            }
            rest = sum % 11;
            if (rest == 1) dvr = 'K';
            else if (rest == 0) dvr = '0';
            else  {
                dvr = 11-rest;
            }
            return dvr==dv.toUpperCase();

        }
    }
}

// LTrim: Quita espacios en blanco a la izquerda de una cadena
function LTrim(value) {
    var pattern=new RegExp("^\\s+", "g")
    return value.replace(pattern, "");
}

// RTrim: Quita espacios en blanco a la derecha de una cadena
function RTrim(value) {
    var pattern=new RegExp("\\s+$", "g")
    return value.replace(pattern, "");
}

// Trim: Quita espacios en blanco a la derecha y a la izquierda de una cadena
function Trim(value) {
    return RTrim(LTrim(value));
}

// Str2Date: Convierte un valor string en formato dd/mm/aaaa a fecha
function Str2Date(sDate) {

	if (!isDate(sDate)) return;
	sDate = new String(sDate);

	aDate = new Array();
	aDate = sDate.split('/');

	return new Date(aDate[2], aDate[1]-1, aDate[0], 0, 0,0 );

}

// DateCmp(A, B): Compara dos fechas devuelve 0=iguales, 1=A>B, -1=B>A
function DateCmp(dDateA, dDateB) {

	if (dDateA.getTime() == dDateB.getTime()) return 0;
	if (dDateA.getTime() > dDateB.getTime()) return 1;
	else return -1;

}

// DayAdd(dDate, iDays): Suma (o resta) dias a una fecha dada
function DayAdd(dDate, iDays) {
	dDate.setTime(dDate.getTime()+iDays*24*60*60*1000);
	return dDate;
}

// isTime: devuelve verdadero si value es una hora valida en formato hh:mm:ss
function isTime(value) {
    var pattern=new RegExp("^[ 0-2][0-9]:[0-5][0-9]$");
    return value.match(pattern);
}

// isSelected: devuelve verdadero si un select tiene elementos seleccionados (multiselect) o 
// si es la opcion seleccionada no es la primera (select normal)
function isSelected(obj) {
	var i, sw;
	if (obj.multiple) {
		sw=0;
		for (i=0;i<obj.length;i++) {
			if (obj.options[i].selected) sw=1;
		}
		return sw==1;
	} else {
		return !(obj.options.selectedIndex == 0);		
	}

}

// MonthAdd: nos permite sumar meses a una fecha 
// dDat es la fecha y iMonths la cantidad de meses a sumar
function MonthAdd(dDat, iMonths) {
	var dDate=new Date(dDat);
	var month=dDate.getMonth()+iMonths;
	if (month>11) {
		dDate.setYear(dDate.getYear()+parseInt((month+1)/12));
		month=(month+1)%12-1;
	}
	dDate.setMonth(month);
	return dDate;	  						   	     			   			
}

// Repeat: devuelve la cadena sValue repetida iLenght veces.   
function Repeat(sValue, iLenght) {
	var sText='';   			
	for(i=1;i<=iLenght;i++) {
		sText=sText+sValue;
	}   			   
	return sText;
}

// LPad, rellena por la izquierda svalue con el caracter sChar hasta un largo iLenght.
function LPad(sValue, sChar, iLenght) {
	sValue=String(sValue);
	sValue=Repeat(sChar, iLenght-sValue.length)+sValue;
	return sValue;   			   			   			
}




function dateDiff(p_Interval, p_Date1, p_Date2){
	if(!isDate(p_Date1)){return "invalid date: '" + p_Date1 + "'";}
	if(!isDate(p_Date2)){return "invalid date: '" + p_Date2 + "'";}
	var dt1 = new Date(p_Date1);
	var dt2 = new Date(p_Date2);

	// get ms between dates (UTC) and make into "difference" date
	var iDiffMS = dt2.valueOf() - dt1.valueOf();
	var dtDiff = new Date(iDiffMS);

	// calc various diffs
	var nYears  = dt2.getUTCFullYear() - dt1.getUTCFullYear();
	var nMonths = dt2.getUTCMonth() - dt1.getUTCMonth() + (nYears!=0 ? nYears*12 : 0);
	var nQuarters = parseInt(nMonths/3);	//<<-- different than VBScript, which watches rollover not completion
	
	var nMilliseconds = iDiffMS;
	var nSeconds = parseInt(iDiffMS/1000);
	var nMinutes = parseInt(nSeconds/60);
	var nHours = parseInt(nMinutes/60);
	var nDays  = parseInt(nHours/24);
	var nWeeks = parseInt(nDays/7);


	// return requested difference
	var iDiff = 0;		
	switch(p_Interval.toLowerCase()){
		case "yyyy": return nYears;
		case "q": return nQuarters;
		case "m": return nMonths;
		case "y": 		// day of year
		case "d": return nDays;
		case "w": return nDays;
		case "ww":return nWeeks;		// week of year	// <-- inaccurate, WW should count calendar weeks (# of sundays) between
		case "h": return nHours;
		case "n": return nMinutes;
		case "s": return nSeconds;
		case "ms":return nMilliseconds;	// millisecond	// <-- extension for JS, NOT available in VBScript
		default: return "invalid interval: '" + p_Interval + "'";
	}
}