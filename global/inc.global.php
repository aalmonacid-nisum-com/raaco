<?

/**
* Funciones Globales
*
* Funciones de manejo comun
*
* @author  Luis E. Cruz Campos
* @version 0.1.1
*/

/*
 * Historial:
 *
 * 1.00.01 06.08.2001 (LCC) Se incluye funcion nvl.
 * 1.00.02 23.08.2001 (LCC) Se incluye funcion page2rows
 * 1.00.04 04.09.2001 (LCC) Se incluye funcion upload
 *
 */

/**
* Convierte una cadena en formato dd/mm/aaaa a formato de mysql
*
* @param string $date
*/
function date2sql( $date ) {
   list( $day, $month, $year) = split( '[/.-]', $date );
   return date( "Y-m-d", mktime( 0, 0, 0, $month, $day, $year ) );
}

/**
* Convierte una cadena en formato mysql a formato dd/mm/aaaa
*
* @param string $date
*/
function sql2date( $date ) {

   list( $year, $month, $day) = split( '[/.-]', $date );
   return date( "d/m/Y", mktime( 0, 0, 0, (int)$month, (int)$day, (int)$year));
}

/**
* Asigna un valor por defecto en caso de que $var sea vacia, sino devuelve el mismo valor
*
* @param string $var
* @param string $value
*/
function nvl( $var, $value ) {
   if (empty($var)) return $value;
   else return $var;
}

/**
* Convierte un valor en paginas a su valor en filas (filas comenzado desde 0)
*
* @param int $page
* @param int $maxrows
*/
function pages2rows ( $page, $maxrows ) {
    return ( $page - 1 ) * $maxrows;
}

/**
* Convierte un valor en filas a su valor en paginas
*
* @param int $rows
* @param int $maxrows
*/
function rows2pages ( $rows, $maxrows) {
    return ((int) ($rows/$maxrows)) + (1 and $rows%$maxrows);
}

/**
* Sube archivos borrando el anterior con algunas validaciones extras
*
* @param string $file
* @param string $filename
* @param string $oldfile
*/
function upload ($file, $filename, $oldfile="") {

    // Sube el archivo solo si no es el mismo
    if ($filename!=$oldfile) {

        // Borrar el anterior
        if (!empty($oldfile)) unlink($oldfile);

        // Finalmente subir el archivo
        if (!empty($filename) && is_uploaded_file($file)) {

            if (move_uploaded_file($file, $filename)) {

            };
        }

    }

}



?>
