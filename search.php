<?php
session_start();
ob_start();
?>
<!-- 
<html>
<body>
<form action="search.php" method="post" autocomplete="on">
  First name: <input type="text" name="search"><br>
  <input type="submit" value="search">
</form>
 -->


 

<?php 


require_once( 'SolrPhpClient/Apache/Solr/Service.php' );
require('/usr/local/Cellar/solr/solr-4.3.1/concert/solr/vendor/solarium/solarium/examples/init.php');
  
  
  // 
  // Try to connect to the named server, port, and url
  // 
  $solr = new Apache_Solr_Service( 'localhost', '8983', '/solr' );
  
  if ( ! $solr->ping() ) {
    echo 'Solr service not responding.';
    exit;
  }
  
  //
  //
  // Create two documents to represent two auto parts.
  // In practice, documents would likely be assembled from a 
  //   database query. 
  //
  // $parts = array(
//     'spark_plug' => array(
//       'partno' => 1,
//       'name' => 'Spark plug',
//       'model' => array( 'Boxster', '924' ),
//       'year' => array( 1999, 2000 ),
//       'price' => 25.00,
//       'inStock' => true,
//     ),
//     'windshield' => array(
//       'partno' => 2,
//       'name' => 'Windshield',
//       'model' => '911',
//       'year' => array( 1999, 2000 ),
//       'price' => 15.00,
//       'inStock' => false,
//     )
//   );
    
 //  $documents = array();
//   
//   foreach ( $parts as $item => $fields ) {
//     $part = new Apache_Solr_Document();
//     
//     foreach ( $fields as $key => $value ) {
//       if ( is_array( $value ) ) {
//         foreach ( $value as $datum ) {
//           $part->setMultiValue( $key, $datum );
//         }
//       }
//       else {
//         $part->$key = $value;
//       }
//     }
//     
//     $documents[] = $part;
//   }
//     
//   //
//   //
//   // Load the documents into the index
//   // 
//   try {
//     $solr->addDocuments( $documents );
//     $solr->commit();
//     $solr->optimize();
//   }
//   catch ( Exception $e ) {
//     echo $e->getMessage();
//   }
//   
  //
  // 
  // Run some queries. Provide the raw path, a starting offset
  //   for result documents, and the maximum number of result
  //   documents to return. You can also use a fourth parameter
  //   to control how results are sorted and highlighted, 
  //   among other options.
  //
  $offset = 0;
  $limit = 10;
  
  //echo $_POST['search'];
  
  $queries = array(
    'uname:'.$_POST['search'],
    'description:'.$_POST['search'],
    'band_name:'.$_POST['search']
    
//      'user_id:galactus sm1111',
//     'description: beatles sm1111',
//      'band_name:peas'
  );

// echo "query is:".$query;
 
  foreach ( $queries as $query ) {
    $response = $solr->search( $query, $offset, $limit );
    
    if ( $response->getHttpStatus() == 200 ) { 
      // print_r( $response->getRawResponse() );
      
      if ( $response->response->numFound > 0 ) {
        //echo "$query <br />";

        foreach ( $response->response->docs as $doc ) { 
         // echo "user_name :$doc->uname <br/>"." desc: $doc->description <br />"."band_name:$doc->band_name";
             echo "<tr><a href=\"user.php?var=$doc->user_id\"   name=\"click\">$doc->uname</a><br/>";
             echo "<tr><a href=\"concert.php?var=$doc->cid\" name=\"click\">$doc->description</a><br>";
             echo "<tr><a href=\"bands.php?var=$doc->bid\" name=\"click\">$doc->band_name</a><br>";
        }
        
        echo '<br />';
      }
    }
    else {
      echo $response->getHttpStatusMessage();
    }
  }

// 
// $client = new Solarium\Client($config);
// $suggestqry = $client->createSuggester();
// 
// $suggestqry->setHandler('suggest');
// $suggestqry->setDictionary('suggest');
// 
// $suggestqry->setQuery($_POST['search']);
// $suggestqry->setCount(5);
// $suggestqry->setCollate(true);
// 
// $resultset = $client->suggester($suggestqry);
// echo "Query : ".$suggestqry->getQuery();
// foreach ($resultset as $term => $termResult) {
//     echo '<strong>' . $term . '</strong><br/>';
//     echo 'Suggestions:<br/>';
//     foreach($termResult as $result){
//         echo '-> '.$result.'<br/>';
//         $response = array('suggestions' => $result);
//         //$response[] = array('label'=>$result);
//        // echo $response;
//     }
// }
// 
//  echo 'Collation: '.$resultset->getCollation();
// // //$response = array('suggestions' => $termResult);
// // //$response[] = array('label' => $row['name']);
// echo '<br/>json'.json_encode($response);



?>
</body>
</html>