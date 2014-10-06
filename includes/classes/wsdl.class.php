
<?php
class ws {
    var $client=null;
    function __construct($url) {
        $this->soapUrl = $url;
        try
        {
            $this->client = new SoapClient($this->soapUrl,array("login"=>"wsuser", "password"=>"",'cache_wsdl' => WSDL_CACHE_NONE,'trace' => 1));
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

    function GetRealizations ($req0 = NULL, $req = NULL, $req1 = NULL){
        return $this->client->GetRealizations(array('DocumentID' =>$req0, 'StartDate' => $req, 'EndDate' => $req1));
    }
    
    function GetBuyers($req = NULL){
        
        $params = new stdClass();
        
        $params->ClientID = $req;
        
        return $this->client->GetBuyers($params);
        
    }

    function GetTypes(){
        return $this->client->__getTypes();
    }
};


//print_r($web->GetRealizations('2014-05-01', '2014-05-02'));

$web = new ws('http://212.72.152.66/satesto/ws/GetRealizations.1cws?wsdl');
echo '<pre>';
print_r($web->GetRealizations('', '2014-06-02', '2014-06-06'));

$client = $web->GetRealizations('', '2014-06-02', '2014-06-06');




$count1 = count($client->return->RealizationsTable);
echo '--'.$count1.'--</br>';

for ($i = 0; $i < $count1; $i++) {

    echo $client->return->RealizationsTable[$i]->DocumentID.'</br>';
    echo $client->return->RealizationsTable[$i]->Date.'</br>';
    echo $client->return->RealizationsTable[$i]->CustomerName.'</br>';
    echo $client->return->RealizationsTable[$i]->Customer1CCode.'</br>';
    echo $client->return->RealizationsTable[$i]->Agreement.'</br>';
    echo $client->return->RealizationsTable[$i]->CustomerID.'</br>';
    echo $client->return->RealizationsTable[$i]->CustomerPhone.'</br>';
    echo $client->return->RealizationsTable[$i]->CustomerAddress.'</br>';
    echo $client->return->RealizationsTable[$i]->StoreHouse.'</br>';
    echo $client->return->RealizationsTable[$i]->Subdivision.'</br>';
    echo $client->return->RealizationsTable[$i]->Responsible.'</br></br></br>';
    
    $count = count($client->return->RealizationsTable[$i]->Nomenclature);
    echo '--'.$count.'--</br>';
    
    if ($count == 1) {
        
            echo '--'.$client->return->RealizationsTable[$i]->Nomenclature->NomenclatureName.'--</br>';
            echo '--'.$client->return->RealizationsTable[$i]->Nomenclature->NomenclatureSeries.'--</br>';
            echo '--'.$client->return->RealizationsTable[$i]->Nomenclature->NomenclatureProperty.'--</br>';
            echo '--'.$client->return->RealizationsTable[$i]->Nomenclature->NomenclatureCount.'--</br>';
            echo '--'.$client->return->RealizationsTable[$i]->Nomenclature->VATRate.'--</br>';
            echo '--'.$client->return->RealizationsTable[$i]->Nomenclature->Price.'--</br>';
            echo '--'.$client->return->RealizationsTable[$i]->Nomenclature->Discount.'--</br>';
            echo '--'.$client->return->RealizationsTable[$i]->Nomenclature->Sum.'--</br>';
            echo '--'.$client->return->RealizationsTable[$i]->Nomenclature->VAT.'--</br>';
        
    }else {
    
        for ($j = 0; $j < $count; $j++) {
            
            echo '--'.$client->return->RealizationsTable[$i]->Nomenclature[$j]->NomenclatureName.'--</br>';
            echo '--'.$client->return->RealizationsTable[$i]->Nomenclature[$j]->NomenclatureSeries.'--</br>';
            echo '--'.$client->return->RealizationsTable[$i]->Nomenclature[$j]->NomenclatureProperty.'--</br>';
            echo '--'.$client->return->RealizationsTable[$i]->Nomenclature[$j]->NomenclatureCount.'--</br>';
            echo '--'.$client->return->RealizationsTable[$i]->Nomenclature[$j]->VATRate.'--</br>';
            echo '--'.$client->return->RealizationsTable[$i]->Nomenclature[$j]->Price.'--</br>';
            echo '--'.$client->return->RealizationsTable[$i]->Nomenclature[$j]->Discount.'--</br>';
            echo '--'.$client->return->RealizationsTable[$i]->Nomenclature[$j]->Sum.'--</br>';
            echo '--'.$client->return->RealizationsTable[$i]->Nomenclature[$j]->VAT.'--</br>';
        
        }
        
    }
    
    echo $client->return->RealizationsTable[$i]->WaybillNum.'</br>';
    echo $client->return->RealizationsTable[$i]->WaybillID.'</br>';
    echo $client->return->RealizationsTable[$i]->WaybillStatus.'</br>';
    echo $client->return->RealizationsTable[$i]->WaybillTransportation.'</br>';
    echo $client->return->RealizationsTable[$i]->WaybillTransportationType.'</br>';
    echo $client->return->RealizationsTable[$i]->WaybillMeanOfTransport.'</br>';
    echo $client->return->RealizationsTable[$i]->WaybillTransporter.'</br>';
    echo $client->return->RealizationsTable[$i]->WaybillTransporterID.'</br>';
    echo $client->return->RealizationsTable[$i]->WaybillActivationDate.'</br>';
    echo $client->return->RealizationsTable[$i]->WaybillTransportationPayer.'</br>';
    echo $client->return->RealizationsTable[$i]->WaybillTransportationExpence.'</br>';
    echo $client->return->RealizationsTable[$i]->WaybillDonor.'</br>';
    echo $client->return->RealizationsTable[$i]->WaybillRecivier.'</br>';
    echo $client->return->RealizationsTable[$i]->WaybillNote.'</br>';
    echo $client->return->RealizationsTable[$i]->WaybillRecieveDate.'</br>';

}
echo '</pre>';

//$web = new ws('http://212.72.152.66/satesto/ws/GetBuyers.1cws?wsdl');

// echo '<pre>';

//     $client =$web->GetBuyers('VR0001890');
//     print_r($client);
//     echo $client->return->ClientTable->Name.'</br>';
//     echo $client->return->ClientTable->FullName.'</br>';
//     echo $client->return->ClientTable->Code1C.'</br>';
//     echo $client->return->ClientTable->AgreementsTable->AgreementID.'</br>';
//     echo $client->return->ClientTable->OwnerID.'</br>';
//     echo $client->return->ClientTable->Phone.'</br>';
//     echo $client->return->ClientTable->Address.'</br>';
//     //print_r($web->GetTypes());

// echo '</pre>';

?>
