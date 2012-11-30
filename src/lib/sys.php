<?php


class Sys {
    
    /**
     * Returns an array with all installed packages
     */

    function installed() {
        $cmd = "dpkg --get-selections";
        $out = $ins = array();
        exec($cmd, $out);
        
        foreach($out as $line) {
            $pkg = substr($line, 0  , strpos($line, "\t"));
            $ins[]= $pkg;
        }
        return $ins;
    }
    
    function info($pkg) {
        $out = self::exec("apt-cache show $pkg", true);
        $info = array();
        $desc = '';
        foreach($out as $line) {
            if(substr($line, 0, 1) == ' ') {
                // It's a description
                if(substr($line, 1, 1) == '.') {
                    // it's a new line
                    $desc .= "\n";
                } else {
                    $desc .= $line . "\n";
                    
                }
            } else {
                @list($key, $value) = explode(':', $line, 2);
                $value = trim($value);
                if(substr($key, 0, 11) == 'Description' && $key != 'Description-md5') {
                    $key = 'Description';
                }
                $info[$key] = $value;
            }
        }
        return array (
            'package'       => $info['Package'],
            'description'   => $info['Description'],
            'details'       => $desc,
            'installedSize' => $info['Installed-Size'],
            'downloadSize'  => $info['Size'],
            'homepage'      => @$info['Homepage'],
            'bugs'  => @$info['Bugs'],
            'screenshots' => array(
                'http://placehold.it/120x90',
                'http://placehold.it/120x90',
                'http://placehold.it/120x90'  
            )
    
        
        
        );
    }
    
    
    /**
     * Returns package information
     */
     
     
     function exec($cmd, $as_array = false) {
        $out = array();
        exec($cmd, $out);
        if(!$as_array) {
            // transforms array to string
            $str = '';
            foreach($out as $line) {
                $str .= $line . "\n";
            }
            $out = $str;
        }
        return $out;
     }

}
