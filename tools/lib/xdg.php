<?php


class Xdg {


        
    /**
     * Read all desktop files in a folder
     *
     */
     
    static public function parse_desktop_folder($folder) {
        if(is_dir($folder)) {
            foreach(glob($folder . '/*.desktop') as $desktop_file) {
                //echo "$desktop_entry <br> -";
                $entry = self::parse_desktop_file($desktop_file);
                //$info = self::parse_desktop_categories($entry['Categories']);
                
                
                if(!@$entry["Comment"]) {
                    $entry["Comment"] = "No description";
                }
                $item = @array(
                    'name'      => $entry['Name'],
                    'comment'   => $entry['Comment'],
                    'icon'      => self::parse_icon($entry['Icon']),
                 
                    'category'  => strtolower($entry['Categories']['category']),
                    
                    'package'   => $entry['X-AppInstall-Package'],
                    'exec'      => $entry['Exec']
                );
                $json[] = $item;   
            }
            return $json;
        }
       
    }

    static public function parse_desktop_file($file) {
        // Read file
        $fast = false;
        if($fast) {
            $entry = @parse_ini_file($file);
        } else {
            $entry = array();
            $content = file_get_contents($file);
            $lines = explode("\n", $content);
            foreach($lines as $line) {
                @list($key,$value) = explode('=', $line);
                if($value && strpos('[', $key) === false) {
                    if($key == 'Categories') {
                        $value = self::parse_desktop_categories($value);   
                    }
                    $entry[$key] = $value;
                }
            }
        }
        return $entry;
    }
    
    static public function parse_icon($icon, $path= '../data/icons/') {
        
        //  return "applications-other.png";
        if(file_exists("$path$icon.png")) return "$icon.png";
        if(file_exists("$path$icon.svg")) return "$icon.svg";
        if(file_exists("$path$icon.gif")) return "$icon.gif";
        if(file_exists("$path$icon.jpg")) return "$icon.jpg";
        if(file_exists("$path$icon")) return "$icon";
        return "applications-other.png";
    }

    static private function parse_desktop_categories($categories) {
        $info = array();
        $categories = explode(';', trim($categories));
        $all = self::get_categories();
        foreach($categories as $cat) {
            if(in_array($cat, $all['categories'])) $info['category'] = $cat;
            if(in_array($cat, $all['subcategories'])) $info['subcategory'] = $cat;
            if(in_array($cat, $all['tech'])) $info['tech'] = $cat;   
        }
        return $info;
    }
    
    static private function parse_entry($entry) {
        $item = @array(
                    'name'      => $entry['Name'],
                    'comment'   => $entry['Comment'],
                    'icon'      => $entry['Icon'] . '.png',
                    'category'  => substr ($entry['Categories'], 0  , strpos($entry['Categories'], ";")),
                    'package'   => $entry['X-AppInstall-Package'],
                    'exec'      => $entry['Exec'],
                    'terminal'  => $entry['Terminal'],
                    'installed' => in_array($entry['X-AppInstall-Package'], $installed)
        );
        return $item;
    }
    
    
    static private function     get_categories() {

        /**
         * Main categories
         *
         * The list of Main Categories consist of those categories that every conforming desktop environment MUST support.
         *  By including one of these categories in an application's desktop entry file the application will be ensured that it will show up in a section of the application menu dedicated to this category. 
         */
         
        $categories = array (
            'AudioVideo', 
            'Development', 
            'Education', 
            'Game', 
            'Graphics', 
            'Network', 
            'Office', 
            'Settings', 
            'System', 
            'Utility'
        );
        
        /**
         * Additional categories
         *
         * The list of Additional Categories provides categories that can be used to provide more fine grained information about the application. 
         * Additional Categories should always be used in combination with one of the Main Categories.
         */
        
        $subcategories = array(
            'Building',
            'Debugger',
            'IDE',
            'GUIDesigner',
            'Profiling',
            'RevisionControl',
            'Translation',
            'Calendar',
            'ContactManagement',
            'Database',
            'Dictionary',
            'Chart',
            'Email',
            'Finance',
            'FlowChart',
            'PDA',
            'ProjectManagement',
            'Presentation',
            'Spreadsheet',
            'WordProcessor',
            '2DGraphics',
            'VectorGraphics',
            'RasterGraphics',
            '3DGraphics',
            'Scanning',
            'OCR',
            'Photography',
            'Publishing',
            'Viewer',
            'TextTools',
            'DesktopSettings',
            'HardwareSettings',
            'Printing',
            'PackageManager',
            'Dialup',
            'InstantMessaging',
            'Chat',
            'IRCClient',
            'FileTransfer',
            'HamRadio',
            'News',
            'P2P',
            'RemoteAccess',
            'Telephony',
            'TelephonyTools',
            'VideoConference',
            'WebBrowser',
            'WebDevelopment',
            'Midi',
            'Mixer',
            'Sequencer',
            'Tuner',
            'TV',
            'AudioVideoEditing',
            'Player',
            'Recorder',
            'DiscBurning',
            'ActionGame',
            'AdventureGame',
            'ArcadeGame',
            'BoardGame',
            'BlocksGame',
            'CardGame',
            'KidsGame',
            'LogicGame',
            'RolePlaying',
            'Simulation',
            'SportsGame',
            'StrategyGame',
            'Art',
            'Construction',
            'Music',
            'Languages',
            'Science',
            'ArtificialIntelligence',
            'Astronomy',
            'Biology',
            'Chemistry',
            'ComputerScience',
            'DataVisualization',
            'Economy',
            'Electricity',
            'Geography',
            'Geology',
            'Geoscience',
            'History',
            'ImageProcessing',
            'Literature',
            'Math',
            'NumericalAnalysis',
            'MedicalSoftware',
            'Physics',
            'Robotics',
            'Sports',
            'ParallelComputing',
            'Amusement',
            'Archiving',
            'Compression',
            'Electronics',
            'Emulator',
            'Engineering',
            'FileTools',
            'FileManager',
            'TerminalEmulator',
            'Filesystem',
            'Monitor',
            'Security',
            'Accessibility',
            'Calculator',
            'Clock',
            'TextEditor',
            'Documentation',
            'Core'
        );
        
        $tech = array(
            'GTK',
            'GNOME',
            'Qt',
            'KDE',
            'Motif',
            'Java'
        );
        
        return array(
            'categories' => $categories,
            'subcategories' => $subcategories,
            'tech' => $tech
           );
    
    }
}
