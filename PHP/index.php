<?php




class View
{
 public function CreateForm()
 {   $t="<div align='center'>"; 
     $t.="<form action='' method='get' enctype='multipart/form-data'>";
     $t.="<input type='hidden' name='MAX_FILE_SIZE' value='100000'>";
     $t.="ENJOY CVS-file:<br> <input name='clent_side_file' type='file'><br><br>";
     $t.="<input type='submit' name='subm' value='Send_to_server'><br>";
     $t.="</form>";
     $t.="</div>";
  echo $t;   
     
 }
    
} // end of class View
class Controller
{
    
  
  
  public static function Analizator($file_name='')
  { $error=FALSE;
  // In future Napisat tak chtoby podxvatyvalo structuru file v nezavisemosti ot poriadka stolbcov
    
   
    $ext=explode(".",$file_name);
      
      if ( ($ext[1]=="xls") )
        {
            $error=FALSE;
        }
        else  { 
            $error=TRUE;
            echo "This isn't Excel File, please reload<br>";
        
        }
          
      if ($error==FALSE)
        {
          
          
          ////Excel import
          require_once 'reader\Excel\reader.php';
          $data = new Spreadsheet_Excel_Reader();
          $data->setOutputEncoding('CP1251');
          $data->read($file_name);
          error_reporting(E_ALL ^ E_NOTICE);
          ////Excel import end
          
          
          for ($i=1;$i<=11;$i++)
          {  if ($data->sheets[0]['cells'][1][$i]=='')
               {
                  $error=TRUE;
                  echo "<br>File Has Bad STRUCTURE for importing data<br>";
               }
          } // for
          
        } // error=False
        
        
      return $error;
      
  } //Controller___Analizatoe end
  
  
 public function LoadExcelFile()
   {  $uploaddir="Excelloads";
      $v=new View();
      
        
     
     if (  isset($_REQUEST['subm']) ) // Esli knopka najata
       { $v->CreateForm();
      /*    
         if ( move_uploaded_file($_REQUEST['clent_side_file']['tmp_name'], $uploaddir."\\".$_REQUEST['clent_side_file']['name']) )
                { echo "File was uploaded";}
            else 
             {
                echo "Error . File wasn't uppload.";
             }
         
       */
       
         if ($this->Analizator($_REQUEST['clent_side_file'])==FALSE ) // To est file Validnyj!!!
          {
           // echo "This is Excel File<br>";
          
        $m=new Model();
        
        $super_manufacturer=array();
        $super_product=array();
        $super_product_description[]=array();
        
        $super_manufacturer= $m->GetDbDataToArray("manufacturer","manufacturer_id");
        $super_product=$m->GetDbDataToArray("product","product_id");
        $super_product_description=$m->GetDbDataToArray("product_description","product_id");
        $super_excel= $m->ExcelSuperMassiv($_REQUEST['clent_side_file']);
            
            
            
   $lastarray['DataToProductTable']=array();   
            
            $product_id=-1;
            $manufacturer_id=-1;
            $np=-1;
            $nm=-1;
            $name="";
            $brand="";
            
            for ($i=1; $i<count($super_excel['fromExcel']);$i++)
               {  
                /*
                $name=$super_excel['fromExcel'][$i]['Name'];
                $np= $m->GetRecordNumber("product_description", "name", "product_id",$name);
                */
                $brand=$super_excel['fromExcel'][$i]['Brand'];
                $nm=$m->GetRecordNumber("manufacturer", "name", "manufacturer_id",  strtoupper($brand));
                 
               /*  Product budet vstavliatsia 
                if ($np==-1)
                     {  
                         $m->InsertNewProductDescr($name,"","","");
                         $product_id=$m->GetRecordNumber("product_description", "name", "product_id",$name);
                      
                     }
                  else  
                    {
                     $product_id=$np;
                    }
                  
                */
                  
                if ($nm==-1)
                     {  
                         $m->InsertNewManufacturer($brand);
                         $manufacturer_id=$m->GetRecordNumber("manufacturer", "name", "manufacturer_id",strtoupper($brand)); 
                         //// Nado delat slishkom mnogo raz vypolniestia- i zamedliaetsia programma
                      
                     }
                  else  
                    {
                     $manufacturer_id=$nm;
                    }
                  
                  
                   
                
                  $lastarray['DataToProductTable'][]=array
                         (
                      
                             'product_id'            	=>$product_id,
                             'model' 	             	=> "", //empty
                             'sku' 	             	=>$super_excel['fromExcel'][$i]['UPC Code'],
                             'upc' 	             	=>"", //empty
                             'location' 	             	=>"", //empty
                             'quantity' 	             	=>"1", 
                             'stock_status_id'    	=> "7",	
                             'image' 	           	 =>"data/picture_not_available.png",
                             'manufacturer_id'  	=> $manufacturer_id,	
                             'shipping' 	         =>"1",
                      
                             'price'                     =>$super_excel['fromExcel'][$i]['SRP Price'],
	      	             'original_price'      	 =>$super_excel['fromExcel'][$i]['Price'],
                             'points'                 	 =>"0",
                             'tax_class_id'       	=> "0",	
                             'date_available'    	=>date("Y-m-d"),	
                             'weight' 	           	=>$super_excel['fromExcel'][$i]['Weight'],
                             'weight_class_id'  	=>"5",	
                             'length' 	           	=>$super_excel['fromExcel'][$i]['Length'],
                             'width'                    =>$super_excel['fromExcel'][$i]['Width'],
                             'height' 	           	=>$super_excel['fromExcel'][$i]['Height'],
                             
                             'length_class_id'  	=> "3",	
                             'subtract'  	        =>"1",
                             'minimum' 	           	=>"1",
                             'sort_order'           	=>"0",	
                             'status' 	          	=>"0",
                             'date_added'               =>date("Y-m-d H:i:s"),	
                             'date_modified'   	        =>date("Y-m-d H:i:s"),	
                             'viewed' 	          	=>"", //???????????????????????????????
                             'kincode' 	                =>"", //???????????????????????????????
                             'name2url' 	                =>"", //empty
                             'manufacture_name'  =>$super_excel['fromExcel'][$i]['Brand'],
                             'Category_name'     =>$super_excel['fromExcel'][$i]['Category'],
                             'SubCategory_name'  =>$super_excel['fromExcel'][$i]['Sub Category']

           
                          );  
            

                
               }  // end for all elements of superExcelarray
            
            $m->InsertNewProduct($lastarray['DataToProductTable']);
              $lastarray1['DataToProduct_DescrTable']=array();
              $first_product_id=-1;
              for ($i=0; $i<count($super_excel['fromExcel'])-1;$i++)
            { 
               if ($first_product_id==-1)    
               {
                 $sku=$super_excel['fromExcel'][1]['UPC Code'];
                 $first_product_id=$m->GetRecordNumber("product", "sku", "product_id",$sku);
               }  //if
              
                $lastarray1['DataToProduct_DescrTable'][]=array
                         (
                             'product_id'            	=>$first_product_id+$i,
                             'name' 	             	=>$super_excel['fromExcel'][$i+1]['Name'] 

                          );  
               
              // $first_product_id=$first_product_id+$i;
               
             
              
            } // for
          
              //print_r($lastarray1);
              $m->InsertNewProductDescr($lastarray1['DataToProduct_DescrTable']);
            
          } //if Analizator
            else 
              { 
               // 
              }  //else analizator
       
       } // if submitted
        else
       {
            $v->CreateForm();
        
           
       } // else notsubmitted
     
     
   } // function LoadExcelFile 
         
         
    
    
}//end of class  Controller
class Model
{

 private $database="db_excel2database";
 private $db_user="root";
 private $db_password="";
 private $db_host="localhost";
         
    
public function ConnectToExcel($file='')
{
    require_once 'reader\Excel\reader.php';
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('CP1251');
    $data->read($file);
    error_reporting(E_ALL ^ E_NOTICE);
    
    return $data;
}      



public function ExcelSuperMassiv($file='solgar.xls')
{
    $error=FALSE;
    require_once 'reader\Excel\reader.php';
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('CP1251');
    $data->read($file);
    error_reporting(E_ALL ^ E_NOTICE);
    
 // validate date    
  if  ($data->sheets[0]['cells'][1][1]=="UPC Code") 
              {              } else {$error=TRUE;}
  if  ($data->sheets[0]['cells'][1][2]=="Brand") 
              {              } else {$error=TRUE;}
  if  ($data->sheets[0]['cells'][1][3]=="Category") 
              {              } else {$error=TRUE;}
  if  ($data->sheets[0]['cells'][1][4]=="Sub Category") 
              {              } else {$error=TRUE;}
  if  ($data->sheets[0]['cells'][1][5]=="Name") 
              {              } else {$error=TRUE;}
  if  ($data->sheets[0]['cells'][1][6]=="SRP Price") 
              {              } else {$error=TRUE;}            
  if  ($data->sheets[0]['cells'][1][7]=="Price") 
              {              } else {$error=TRUE;}            
  if  ($data->sheets[0]['cells'][1][8]=="Weight") 
              {              } else {$error=TRUE;}            
  if  ($data->sheets[0]['cells'][1][9]=="Length") 
              {              } else {$error=TRUE;}   
           
  if  ($data->sheets[0]['cells'][1][10]=="Width") 
              {              } else {$error=TRUE;}            
  if  ($data->sheets[0]['cells'][1][11]=="Height") 
              {              } else {$error=TRUE;}               
  
 // validate date 
              
              
$megarray['fromExcel']=array();   

 if($error==FALSE)   
 { for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) 
    {
   
  if(($data->sheets[0]['cells'][$i][7]=="")|| ($data->sheets[0]['cells'][$i][7]=="0.00"))   // priznaki - chtob brat dannye iz Excel file- esli price== 0, ne beriom. Est escho polia 1,3,4!!!
      {  }
  else
   {  $megarray['fromExcel'][]=array(
       
   'UPC Code'=>	$data->sheets[0]['cells'][$i][1],
   'Brand'=>	$data->sheets[0]['cells'][$i][2],
   'Category'=>	$data->sheets[0]['cells'][$i][3],
   'Sub Category'=>$data->sheets[0]['cells'][$i][4],
   'Name'=>	$data->sheets[0]['cells'][$i][5],
   'SRP Price'=>$data->sheets[0]['cells'][$i][6],	
   'Price'=>	$data->sheets[0]['cells'][$i][7],
   'Weight'=>	$data->sheets[0]['cells'][$i][8],
   'Length'=>	$data->sheets[0]['cells'][$i][9],
   'Width'=>	$data->sheets[0]['cells'][$i][10],
   'Height'=> $data->sheets[0]['cells'][$i][11]
           
   );  
  }   // else 
    
   } // for end
 } // if error=False  
    
  return $megarray;  
} // ExcelSuperMAssiv      



public function ConnectToDataBase() // constructor batenka- constructor
{  $link="";
   
     $link= mysql_connect($this->db_host,$this->db_user,$this->db_password);
      mysql_select_db($this->database) or die("couldn't connect!");
    mysql_close($link); 
}       

public function GetDbDataToArray($tablename='',$order_field='')
{  $link="";
   $result="";
   $link= mysql_connect($this->db_host,$this->db_user,$this->db_password);
   mysql_select_db($this->database) or die("couldn't connect!");
   $result=mysql_query("select * from ".$tablename." order by ".$order_field);
   $mas=mysql_fetch_array($result);  // Vyvodit superglobalnyj massiv
   
  $field_name=array();
 

for ($i=0;$i<mysql_num_fields($result);$i++)
{
    $field_name[$i]=mysql_field_name($result, $i);
} ;


  $dbarray[$tablename]=array(); 

//////first record //////////////////////////////

for ($i=0;$i<mysql_num_fields($result);$i++)
{
    $dbarray[$tablename][1][$field_name[$i]]=$mas[$field_name[$i]];
  
} ;
  $j=2;
while ($mas = mysql_fetch_array($result)) 
   {
      for ($i=0;$i<mysql_num_fields($result);$i++)
          {
               $dbarray[$tablename][$j][$field_name[$i]]=$mas[$field_name[$i]]."  ";
          }  //for
          
          $j++;
   } //while  
  
/////////////////////////////////
  
  
   
   


 // print_r($dbarray);
     return $dbarray;   
}

public function GetRecordNumber($table_name="",$field="",$order_field="",$find_record="")
{ 

  $number=-1;  // !!!!!!!!!!! priznak - chto Net v  baze
  
    
  $link="";
  $result="";
  $link= mysql_connect($this->db_host,$this->db_user,$this->db_password);
  mysql_select_db($this->database) or die("couldn't connect!");
$result=mysql_query("select ".$order_field." ,".$field." from ".$table_name." order by ".$order_field);
  
  //$result=mysql_query("select ".$order_field." ,".$field." from ".$table_name." where ".$field."='".$find_record."'  order by ".$order_field);
  
 $row= mysql_fetch_array($result);
  
if ($row[$field]==$find_record)  {$number=$row[$order_field];}
while ($row = mysql_fetch_array($result)) 
      {
    //   $t.=$row[$order_field]."-".$row[$field]."<br>";
       if ($row[$field]==$find_record)  {$number=$row[$order_field];}
       
      }

 
 
  // echo $t; 
 mysql_close($link);
 return $number;   
}        



/*
public function GetRecordNumber($table_name="",$field="",$order_field="",$find_record="")
{ 

  $number=-1;  // !!!!!!!!!!! priznak - chto Net v  baze
  
    
  $link="";
  $result="";
  $link= mysql_connect($this->db_host,$this->db_user,$this->db_password);
  mysql_select_db($this->database) or die("couldn't connect!");
  $result=mysql_query("select ".$order_field." ,".$field." from ".$table_name." where ".$field."='".$find_record."'  order by ".$order_field);
  $row= mysql_fetch_array($result);
  $number=$row[$order_field];

 if ($number=="") {$number=-1;}
 
  // echo $t; 
 mysql_close($link);
 return $number;   
}        

*/



public function InsertNewProductDescr($superglobal)
{  $link="";
   $result="";
  $link= mysql_connect($this->db_host,$this->db_user,$this->db_password);
   mysql_select_db($this->database) or die("couldn't connect!");
   
 for ($i=0;$i<count($superglobal);$i++)
 {  
  $result=mysql_query("INSERT INTO `product_description` (
  `product_id` ,
  `language_id` ,
  `name` ,
  `description` ,
  `meta_description` ,
  `meta_keyword` 
  )
   VALUES (
   '".$superglobal[$i]['product_id']."' , 
   '1',
    '".$superglobal[$i]['name']."',
    '',
    '',
    ''
    );");
 }
  
     mysql_close($link);
}

 
public function InsertNewManufacturer($b_name='')
{  $link="";
   $result="";
 try {
  $b_name=strtoupper($b_name);
  
  $data=explode(" ",$b_name);
  $sm_name=strtolower(implode("",$data));
  
  
  $link= mysql_connect($this->db_host,$this->db_user,$this->db_password);
   mysql_select_db($this->database) or die("couldn't connect!");
  $result=mysql_query("INSERT INTO `manufacturer` 
                       (
                         `manufacturer_id` ,
                         `name` ,
                         `image` ,
                         `sort_order` ,
                         `name2url` 
                       )
                        VALUES (
                         NULL , '".$b_name."', NULL , '0', '". $sm_name."'
                          );");
    
   mysql_close($link);
    }
    catch (Exception $exception) {echo "Error=".$exception->getMessage();}
    
    
    
}   

public function InsertNewProduct($superglobal)
{  $link="";
   $result="";
   $link= mysql_connect($this->db_host,$this->db_user,$this->db_password);
   mysql_select_db($this->database) or die("couldn't connect!");

    
    
 for ($i=0;$i<count($superglobal);$i++)
 {
     mysql_query("
      INSERT INTO `product` (
`product_id` ,
`model` ,
`sku` ,
`upc` ,
`location` ,
`quantity` ,
`stock_status_id` ,
`image` ,
`manufacturer_id` ,
`shipping` ,
`price` ,
`original_price` ,
`points` ,
`tax_class_id` ,
`date_available` ,
`weight` ,
`weight_class_id` ,
`length` ,
`width` ,
`height` ,
`length_class_id` ,
`subtract` ,
`minimum` ,
`sort_order` ,
`status` ,
`date_added` ,
`date_modified` ,
`viewed` ,
`kincode` ,
`name2url` ,
`manufacture_name` ,
`Category_name` ,
`SubCategory_name` 
)
VALUES (

NULL ,
'".$superglobal[$i]['model']."',  
'".$superglobal[$i]['sku']."',   
'".$superglobal[$i]['upc']."',   
'".$superglobal[$i]['location']."',  
'".$superglobal[$i]['quantity']."',  
'".$superglobal[$i]['stock_status_id']."', 
'".$superglobal[$i]['image']."' ,
'".$superglobal[$i]['manufacturer_id']."',           
'".$superglobal[$i]['shipping']."',       

'".$superglobal[$i]['price']."',  
'".$superglobal[$i]['original_price']."',  
'".$superglobal[$i]['points']."',   
'".$superglobal[$i]['tax_class_id']."',  
'".$superglobal[$i]['date_available']."',    
'".$superglobal[$i]['weight']."',        
'".$superglobal[$i]['weight_class_id']."',      
'".$superglobal[$i]['length']."',   
'".$superglobal[$i]['width']."',    
'".$superglobal[$i]['height']."',   
'".$superglobal[$i]['length class id']."',      

'".$superglobal[$i]['subtract']."',     
'".$superglobal[$i]['minimum']."',      
'".$superglobal[$i]['sort order']."',     
'".$superglobal[$i]['status']."',       
'".$superglobal[$i]['date added']."', 
'".$superglobal[$i]['date modified']."', 
'".$superglobal[$i]['viewed']."',              
'".$superglobal[$i]['kincode']."',             
'".$superglobal[$i]['name2url']."',        

'".$superglobal[$i]['manufacture_name']."',        
'".$superglobal[$i]['Category_name']."',         
'".$superglobal[$i]['SubCategory_name']."'           
);
");
 } // for
//echo "<H1>".$superglobal[0]['image']."</h1><br>";


mysql_close($link);
echo "Table Product was update, count of added record=".$i."<br>";


}



    
}// end of class Model






$c=new Controller();
$c->LoadExcelFile();



?>