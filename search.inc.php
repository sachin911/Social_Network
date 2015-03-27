<?php 


require_once( 'SolrPhpClient/Apache/Solr/Service.php' );
require('/usr/local/Cellar/solr/solr-4.3.1/concert/solr/vendor/solarium/solarium/examples/init.php');
  
 $solr = new Apache_Solr_Service( 'localhost', '8983', '/solr' );
  
  if ( ! $solr->ping() ) {
    echo 'Solr service not responding.';
    exit;
  }
if(isset($_GET['search_text'])){
$search_text=$_GET['search_text'];  
//echo $search_text; 
}

 $client = new Solarium\Client($config);
$suggestqry = $client->createSuggester();

$suggestqry->setHandler('suggest');
$suggestqry->setDictionary('suggest');
$suggestqry->setQuery($_GET['search_text']);
$suggestqry->setCount(5);
$suggestqry->setCollate(true);

$resultset = $client->suggester($suggestqry);
//echo "Query : ".$suggestqry->getQuery();
foreach ($resultset as $term => $termResult) {
   // echo '<strong>' . $term . '</strong><br/>';
    //echo 'Suggestions:<br/>';
    foreach($termResult as $result){
        //echo '-> '.$result.'<br/>';
        //$response = array('suggestions' => $result);
        $response[] = array($result);
       // echo $response;
    }
}

//echo $resultset->getCollation();
// //$response = array('suggestions' => $termResult);
// //$response[] = array('label' => $row['name']);
//echo json_encode($response);

echo '<select>
  <option value="'.$resultset->getCollation().'">'.$resultset->getCollation().'</option>
</select>';



 
  

?>