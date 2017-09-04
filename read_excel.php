<?php
$excel_path='';
if(array_key_exists('excel_path',$_GET)){
    $excel_path = $_GET['excel_path'];
} 
else{
    $excel_path = 'test/data/vedata_10_lines_data.xls';
    ?>
    <a href='?excel_path=test/data/vedata.xls'>Big file<a/><br/>
    <a href='?excel_path=test/data/vedata_10_lines_data.xls'>Small file<a/><br/>
    <?php
}

file_exists($excel_path) or die("Excel Path invalid '$excel_path' ");

require 'vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
//    $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::load($excel_path);
    $excelReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($excel_path);
    $excelObject = $excelReader->load($excel_path);
} catch (Exception $ex) {
    die('Error loading file"' . pathinfo($excel_path, PATHINFO_BASENAME) . '": ' . $ex->getMessage());
}

$sheet_header_map=array(
    // -- CELL: 0 = Dato : 
    // -- CELL: 1 = FRA NET (Taeller-stand) : 
    // -- CELL: 2 = TIL NET (Taeller-stand) : 
    // -- CELL: 3 = FRA NET (FORBRUG) kWh/døgn : 
    // -- CELL: 4 = TIL NET (OVERSKUDS-PRODUKTION) kWh/døgn :
);
$sheet_cols = array(
   "date",
   "power_from_grid_acc",
   "power_to_grid_acc",
   "power_from_grid_current_day",
   "power_to_grid_current_day"
);


$sheet = $excelObject->getSheet(0);
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();

for ($row = 2; $row <= $highestRow; $row++) {
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . 
            $row, NULL, TRUE, FALSE);
    foreach ($rowData as $k) {
        // var_export($sheet_cols);
        // echo "..<br>..";
        // var_export(($k));
        $new_ar = array_combine($sheet_cols,
            array_values($k)
        );
        $new_ar['date_as_text'] = '231221';
        echo 'JSON:<br />'.nl2br(json_encode($new_ar,));
        echo '<br/>K:'.$k[0]
            .nl2br(var_export($rowData))
          //  .implode('--',$rowData)
            .'<br />';
        foreach($k as $key=>$value){
            echo "<br> -- CELL: $key = $value : ";
        }
        echo '<hr />';
    }
}

//$spreadsheet = new Spreadsheet();
// $sheet = $spreadsheet->getActiveSheet();
// $sheet->setCellValue('A1', 'Hello World !');