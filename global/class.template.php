<?php

 /**
* Clase Template
*
* Clase PHP para el manejo de templates
*
* @author  Luis E. Cruz Campos
* @version 0.1.1
*/

// LCC.10022003 agregada funcion save2file, para grabar un template como archivo

class template {

    /**
    * Archivo del Template
    * @access private
    */
    var $file_name = "";

    /**
    * Template
    * @access private
    */
    var $template = "";

    /**
    * Error
    * @access private
    */
    var $errorcode = 0;

    /**
    * Arreglo de lineas para secciones
    * @access private
    */
    var $lines = array();

    /**
    * Establece el un archivo para el template
    *
    * Lee el archivo $file_name y lo guarda para su uso
    *
    * @param string $file_name
    */
    function setFile($file_name) {

		$this->lines = array();
		$this->sections = array();
        $this->file_name = $file_name;

        if ($file_handle = fopen($this->file_name,"r")) {

            if ($this->template = fread ($file_handle, filesize($this->file_name))) {
            } else $this->errorcode=2;

            fclose ($file_handle);
        } else $this->errorcode = 1;


    }

    function setTemplate($template="") {
        $this->template=$template;
		$this->lines = array();
		$this->sections = array();
    }

    // Devuelve el codigo de error, 0 = No existen errores
    function errorcode() {
        return $this->errorcode;
    }

    /**
    * Asigna una variable de template
    *
    * Remplaza $tag en el template por $value
    *
    * @param string $tag
    * @param string $value
    */
    function assign($tag, $value) {
        $this->template = str_replace("{".$tag."}", $value, $this->template);
    }

    /**
    * Muestra el template
    *
    * Lee el archivo $file_name y lo guarda para su uso
    */
    function show() {
        $this->parse_sections();
        echo $this->template;
    }

    /**
    * Retorna el template
    *
    * Retorna el template
    * @return string
    */
    function get() {
        $this->parse_sections();
        return $this->template;
    }
    
    /**
    * Asigna una variable de template en una seccion en particular
    *
    * Remplaza $tag en el template por $value
    *       
    * @param string $section
    * @param string $tag
    * @param string $value
    */
    
    function assign2section($section, $tag, $value) {
        $this->sections[$section][$this->lines[$section]][$tag]= $value;
    } 
    
    /**
    * Crea una nueva linea en una seccion 
    *
    * @param string $section
    */

    function newline($section) {
        $this->lines[$section]++;
    }    
    
    /**
    *  
    *
    * @param string $section
    * @param string $section_string
    */

    function tplsection($section, $section_string) {

        foreach ($this->sections[$section] as $linea => $tags) {
            $temp=$section_string;
            foreach ($tags as $tag => $value) {
                $temp=str_replace("{".$tag."}", $value, $temp);
            }

            $texto.=$temp;
        }
        return $texto;
    }

    function parse_sections() {
        if (!is_array($this->sections)) return;
        foreach($this->sections as $key => $value) {
            $this->ReplaceSection($key,$this->tplsection($key, $this->SectionString($key)));
        }
    }

    // Rescata el string dentro de la seccion
    function SectionString($section) {
        $pattern="\\\{$section}(.+)\\\{/$section}";
        if (eregi($pattern, $this->template, $regs)) {
            return $regs[1];
        }
    }

    /** Reemplaza la seccin por su texto definitivo
    *
    * @param string $section
    * @param string $section_string
    */

    function ReplaceSection($section, $section_string) {
        $pattern="\\\{$section}(.+)\\\{/$section}";
        //echo $pattern;
        $this->template=eregi_replace($pattern, $section_string, $this->template);
    }

    /**
    * Borra la seccion
    *
    * @param string $section
    */
    function deleteSection($section) {
        $this->ReplaceSection($section, "");
    }

    /**
    * Remueve una seccion retorna la seccion removida
    *
    * @param string $section
    */
    function removeSection($section, $string="") {
	    $pattern='(\{'.$section.'})(.+)(\{/'.$section.'})';
        if (eregi($pattern, $this->template, $regs)) {
			$section_text=$regs[2];
		} else {
			$section_text="";
		}
        $this->ReplaceSection($section, $string);
		return $section_text;
    }

	/**
    * Remueve un tag desde un template
    *
    * @param string $file
    */
	function delete ($tag) {
		$this->assign($tag, '');
	}

    /**
    * Graba un template procesado en un archivo
    *
    * @param string $file
    */
	function save2file ($file) {
        if ($file_handle = fopen($file, "w")) {
	        $this->parse_sections();	    
            if (fputs($file_handle, $this->template)) {
	            fclose ($file_handle);				
				return true;
			} else {
				return false;
			}
        } else {
        	return false;
        }
	}
}

?>