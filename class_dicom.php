<?PHP
/*
Dean Vaughan 2013 <dean@deanvaughan.org>
http://www.deanvaughan.org/projects/class_dicom_php/

Updated to POO
Felipe Zura 2025 <felipe@zura.cl>
*/
class DicomHandler {
    private $dcmtk_path;
    private $is_windows;
    
    public function __construct() {
        $this->is_windows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $this->dcmtk_path = $this->is_windows ? 'C:\\dcmtk\\bin\\' : '/usr/bin/';
    }

    private function executeCommand($command) {
        return shell_exec($command . ' 2>&1');
    }

    public function loadTags($file) {
        $command = $this->dcmtk_path . "dcm2xml -Q "$file"";
        return $this->executeCommand($command);
    }

    public function modifyTag($file, $tag, $value) {
        $command = $this->dcmtk_path . "dcmodify -m "$tag=$value" "$file"";
        return $this->executeCommand($command);
    }

    public function convertToJpg($file, $output) {
        $command = $this->dcmtk_path . "dcmj2pnm "$file" "$output"";
        return $this->executeCommand($command);
    }

    public function compressDcm($file) {
        $output = str_replace('.dcm', '_compressed.dcm', $file);
        $command = $this->dcmtk_path . "dcmcjpeg "$file" "$output"";
        return $this->executeCommand($command);
    }

    public function sendDcm($file, $host, $port) {
        $command = $this->dcmtk_path . "storescu "$host" "$port" "$file"";
        return $this->executeCommand($command);
    }
}

?>

