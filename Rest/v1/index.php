<?php

require_once '../include/DbHandler.php';
require_once '../include/PassHash.php';
require '.././libs/Slim/Slim.php';
// require_once __DIR__.'/src/Facebook/autoload.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();


$user_id = NULL;


function authenticate(\Slim\Route $route)
{
    // Getting request headers
    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();

    // Verifying Authorization Header
    if (isset($headers['Authorization'])) {
        $db = new DbHandler();

        // get the api key
        $api_key = $headers['Authorization'];
        // validating api key
        if (!$db->isValidApiKey($api_key)) {
            // api key is not present in users table
            $response["error"] = true;
            $response["message"] = "Access Denied. Invalid Api key";
            echoRespnse(401, $response);
            $app->stop();
        } else {
            global $user_id;
            // get user primary key id
            $user_id = $db->getUserId($api_key);
        }
    } else {
        // api key is missing in header
        $response["error"] = true;
        $response["message"] = "Api key is misssing";
        echoRespnse(400, $response);
        $app->stop();
    }
}

$app->get('/admininfo', function() use ($app)  {
 
  // $ansid = $app->request->post('ansid');
  //  $upvote_uid = $app->request->post('upvote_uid');
  //     $flag = $app->request->post('flag');
   
  $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
  $strings = "SELECT * FROM Admin";    
  
  $result = $conn->prepare($strings);
  $result->execute();
  $result->bind_result($u_id,$name,$pass);
  $posts = array();

  while($result->fetch()) {       
      $tmp = array();
      $tmp["username"] = $name;
      $tmp["password"] = $pass;
    
      array_push($posts, $tmp); 
           
  }
    
     
     $result->close();
        echoRespnse(201,$posts);
           
 });



 $app->get('/book_info', function() use ($app)  {
	
  $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
  // $conn = new mysqli("localhost", "root", "", "kolpbdc_site");
  
  $strings = "SELECT * FROM Book";
  $result = $conn->prepare($strings);
       
        
  $result->execute();
  $result->bind_result($book_id,$name , $img);
  $posts = array();
  
  while($result->fetch()) {
    $tmp = array();
    $tmp["book_id"] = $book_id;
    $tmp["book_name"] = $name;
    array_push($posts, $tmp);
    #why array_push ?
  }

  $result->close();
        
        
        
  echoRespnse(201,$posts);  
	
	
});


$app->post('/insertbookdata', function() use ($app)  {

  $book = $app->request->post('name');
  $author = $app->request->post('author');
  $buy_price = $app->request->post('buy_price');
  $sell_price = $app->request->post('sell_price');
  $description = $app->request->post('description');
  $genre = $app->request->post('genre');
  $publisher = $app->request->post('publisher');
  $mainImage = $app->request->post('image');
  
  
  // $conn = new mysqli("localhost", "root", "", "kolpbdc_site");
  //$conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_bookstore");
  $conn = new mysqli("localhost", "root", "", "kolpobdc_bookstore");
    
  $imgpath=$book.".png";
  file_put_contents($path,base64_decode($mainImage));
  
  $string=mysqli_real_escape_string($conn,$string);
	
  $strings="INSERT INTO book(book_id,book_name,author,buy_price,sell_price,description,genre,img_link,pdf_link,publisher)  VALUES (" . "NULL". "," . "'". $book . "'". "," . "'". $author . "'". "," . "'". $buy_price. "'" ."," ."'" . $sell_price . "'" . "," . "'". $description. "'" . "," . "'".$genre. "'" . "," . "'". $imgpath. "'" . "," . "'". $pdfpath . "'" . ",". "'" . $publisher . "'" . ")";
  
  
  $result = $conn->query($strings);
  
    

});


$app->get('/book_author_price', function() use ($app)  {
	
  $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
  // $conn = new mysqli("localhost", "root", "", "kolpbdc_site");
  
  # There can be multiple authors
  $strings = "SELECT author_id FROM BookAuthor a JOIN Book b WHERE a.book_id = b.book_id";
  $result = $conn->prepare($strings);
       
        
  $result->execute();
  $result->bind_result($book_id,$name , $img);
  $posts = array();
  
  while($result->fetch()) {
    $tmp = array();
    $tmp["book_id"] = $book_id;
    $tmp["book_name"] = $name;

    
    array_push($posts, $tmp);
  }

  $result->close();
    
  echoRespnse(201,$posts);  
	
	
});




$app->get('/book_information', function() use ($app)  {
	
  $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
  // $conn = new mysqli("localhost", "root", "", "kolpbdc_site");
  
  # There can be multiple authors
  $strings = "SELECT Book.name , Author.author_name ,Book.book_id
  FROM Book,BookAuthor,Author
  where Book.book_id = BookAuthor.book_id 
  and BookAuthor.author_id = Author.author_id";

  $result = $conn->prepare($strings);
       
        
  $result = $conn->prepare($strings);
  $result->execute();
  $result->bind_result($book_name, $name , $book_id);
  $posts = array();
  
  $posts = array();
  
  while($result->fetch()) {
    $tmp = array();

    $tmp["book_id"] = $book_id; 
    $tmp["author_name"] = $name;
    $tmp["book_name"] = $book_name;
    array_push($posts, $tmp);
  }

  $result->close();

  echoRespnse(200,$posts);  

});

$app->get('/book_pricelist', function() use ($app)  {
	
  $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
  // $conn = new mysqli("localhost", "root", "", "kolpbdc_site");
  
  # There can be multiple authors
  $strings = "SELECT Book.book_id ,Book.name , Author.author_name , Quality.quality_category,Price.price,Price.price_id
  FROM Book,BookAuthor,Author,Price,Quality where Book.book_id = BookAuthor.book_id
  and BookAuthor.author_id = Author.author_id and Price.book_id = Book.book_id and Price.quality_id = Quality.quality_id ORDER BY Price.price_id";

  $result = $conn->prepare($strings);
       
        
  $result = $conn->prepare($strings);
  $result->execute();
  $result->bind_result($book_id, $name , $author_name , $quality_category,$price, $price_id);
  $posts = array();
  
  while($result->fetch()) {
    $tmp = array();

    $tmp["book_id"] = $book_id; 
    $tmp["name"] = $name;
    $tmp["author_name"] = $author_name;
    $tmp["quality_category"] = $quality_category;
    $tmp["price"] = $price;
    $tmp["price_id"] = $price_id;
    
    array_push($posts, $tmp);
  }

  $result->close();


    
  echoRespnse(201,$posts);  
	
	
});

$app->get('/new_order', function() use ($app) {

  // $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
  //$conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_devtesting");
   $conn = new mysqli("localhost", "root", "", "kolpobdc_bookstore");

  $strings = "SELECT order.order_id ,  user.name, order.address, order.total_cost,
  order.time,order.delivery_status, user.mobile
  FROM order,book,user
  WHERE user.user_id = order.user_id
  AND order.delivery_status = 0
  GROUP BY user.user_id
  ORDER BY order.order_id";
        
  $result = $conn->prepare($strings);
  $result->execute();
  $result->bind_result($order_id, $user_name , $address , $total_cost , $date, $status , $mobile);
  
  $posts = array();
  
  while($result->fetch()) {
    $tmp = array();

    $tmp["order_id"] = $order_id;
    $tmp["user_name"] =  $user_name; 
    $tmp["address"] = $address;
    $tmp["mobile"] = $mobile;

    // $tmp["items"] =  $items;
    $tmp["total_cost"] = $total_cost ;
    $tmp["date"] = $date;
    $tmp["status"] = $status;
    array_push($tmp, getCartItems($order_id));

    array_push($posts, $tmp);

  }

  $result->close();

  echoRespnse(200,$posts); 
});

function getCartItems($order_id)
{
  # code...
  $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_devtesting");

  $query = "SELECT Book.name, Price.price ,CartItem.number_of_item , Quality.quality_category 
    FROM CartItem JOIN BookOrder ON CartItem.book_order_id = BookOrder.book_order_id
    JOIN Book ON Book.book_id = CartItem.book_id
    JOIN BookAuthor ON Book.book_id = BookAuthor.book_id
    JOIN Author ON Author.author_id = BookAuthor.author_id
    JOIN Price ON Price.price_id = CartItem.price_id
    JOIN Quality ON Price.quality_id = Quality.quality_id
    WHERE BookOrder.book_order_id = '".$order_id."'";

    $resultN = $conn->prepare($query);
    $resultN->execute();
    $resultN->bind_result($book_name, $sell_price , $items , $quality);
    $item = array();

    while($resultN->fetch()) {
      $temp = array();

      $temp["book_name"] = $book_name;
      $temp["sell_price"] =  $sell_price; 
      $temp["items"] =  $items; 
      $temp["quality"] =  $quality; 

      array_push($item, $temp);
    }
    $resultN->close();

    return $item;

}

$app->get('/update_order_status', function() use ($app) {

  // $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
  $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_devtesting");
  $status = $app->request->get('status');
  $id = $app->request->get('id');

  $posts = "done ".$status." - ".$id;
  
  if($status == "Done"){
    $strings ="UPDATE BookOrder SET delivery_confirmed =1 WHERE BookOrder.book_order_id = '".$id."'";
    $result = $conn->query($strings);
  
  }
  else if($status = "Cancel"){
    $strings ="UPDATE BookOrder SET delivery_confirmed =2 WHERE BookOrder.book_order_id = '".$id."'";
    $result = $conn->query($strings);
  }
  // $result->close();

  echoRespnse(200,$posts); 

});





 $app->get('/IndividualBookDataStore', function() use ($app)  {
 
  // $bookId = $app->request->get('bookId');
   $bookId = 1;
  // $conn = new mysqli("localhost", "testing", "kolpobd", "kolpobdc_site");
  //$conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
   $conn = new mysqli("localhost", "root", "", "kolpobdc_bookstore");


  $strings = "SELECT *
  FROM book
  WHERE book.book_id = '".$bookId."'";    
  
    // $conn = new mysqli("localhost", "testing", "kolpobd", "kolpobdc_site");
 
  
  $resultX = $conn->prepare($strings);
  $resultX->execute();
  $resultX->bind_result($book_id,$book_name,$publisher,$author,$buy_price,$sell_price,$genre,$description,$img_link,$pdf_link);
  $booklist = array();
 
  while($resultX->fetch()) {
      
      $temp  =array();
      $booklist["total"] = $book_id;
      $temp["book_id"] = $book_id;
      $temp["book_name"] = $book_name;
      $temp["publisher"] = $publisher;
      $temp["author"] = $author;
      $temp["buy_price"] = $buy_price;
      $temp["sell_price"] = $sell_price;
      $temp["genre"] = $genre;
      $temp["description"] = $description;
      $temp["img_link"] = $img_link;
      $temp["pdf_link"] = $pdf_link;
      
            
  }
  $resultX->close();

echoRespnse(200,$temp); 
 


        
           
 });



 function ReturnAuthor($bookId)
 {
   # code...
   //$conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
  $conn = new mysqli("localhost", "root", "", "kolpobdc_bookstore");
 
   $strings = "SELECT Author.author_id,Author.author_name
   FROM Book,Author,BookAuthor
   WHERE Book.book_id = BookAuthor.book_id
   AND BookAuthor.author_id = Author.author_id
   AND Book.book_id = '".$bookId."'";
 
 
     $result = $conn->prepare($strings);
     $result->execute();
     $result->bind_result($author_id,$authorName);
     $authorList = array();
    
     while($result->fetch()) {
         
         $temp = array();
         $temp["author_id"] = $author_id;
         $temp["authorName"] = $authorName;
         array_push($authorList, $temp);
               
     }
 
     
     return $authorList;
 
 }


 function ReturnTags($bookId)
 {
   # code...
   $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
 
   $strings = "SELECT Tag.tag_id,Tag.tag_name 
   FROM Tag,Book,BookTag 
   WHERE Tag.tag_id = BookTag.tag_id 
   AND BookTag.book_id = Book.book_id 
   AND Book.book_id  = '".$bookId."'";    
   
   $result = $conn->prepare($strings);
   $result->execute();
   $result->bind_result($TagId,$TagName);
   $Tags = array();
  
   while($result->fetch()) {   
     $temp = array();    
     $temp["TagId"] = $TagId;
     $temp["TagName"] = $TagName;
     array_push($Tags, $temp);
           
   }
  
   $result->close();
 
     
     return $Tags;
 
 }


 function ReturnSemister($bookId)
 {
   # code...
   $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
 
   $strings = "SELECT Semester.semester_id,Semester.number
   FROM Semester,BookSemester,Book
   WHERE Book.book_id = BookSemester.book_id
   AND BookSemester.semester_id = Semester.semester_id
   and Book.book_id = '".$bookId."'";    
    
    $result = $conn->prepare($strings);
    $result->execute();
    $result->bind_result($SemisterId,$SemisterNumber);
    $Semister = array();
   
    while($result->fetch()) {   
      $temp = array();    
      $temp["SemisterId"] = $SemisterId;
      $temp["SemisterNumber"] = $SemisterNumber;
      array_push($Semister, $temp);
            
      
    }
   
    $result->close();
     
     return $Semister;
 
 }


 function ReturnBookEdition($bookId)
 {
   # code...
   $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
 
   $strings = "SELECT Edition.name,Edition.edition_id
   FROM Edition,Book,BookEdition
   WHERE BookEdition.book_id = Book.book_id
   AND Edition.edition_id  = BookEdition.edition_id
   AND Book.book_id = '".$bookId."'";    
  
  $result = $conn->prepare($strings);
  $result->execute();
  $result->bind_result($editionName,$EditionId);
  $BookEdition = array();

  while($result->fetch()) {   
    $temp = array();    
    $temp["editionName"] = $editionName;
    $temp["editionName"] = $EditionId;
    array_push($BookEdition, $temp);
          
  }

  $result->close();
     
     return $BookEdition;
 
 }

 function ReturnDepartment($bookId)
 {
   # code...
   $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
 
   $strings = "SELECT Department.deaprtment_id,Department.name,Department.abbreviation
   FROM Department,Book,BookSemester
   WHERE Book.book_id = BookSemester.book_id
   AND Department.deaprtment_id = BookSemester.department_id
   AND Book.book_id  = '".$bookId."'";    
   
   $result = $conn->prepare($strings);
   $result->execute();
   $result->bind_result($DepartmentId,$DepartmentName,$DepartmentAbbriviation);
   $Department = array();
  
   while($result->fetch()) {   
     $temp = array();    
     $temp["DepartmentId"] = $DepartmentId;
     $temp["DepartmentName"] = $DepartmentName;
     $temp["DepartmentAbbriviation"] = $DepartmentAbbriviation;
     array_push($Department, $temp);
           
   }
  
   $result->close();
     
     return $Department;
 
 }


 function ReturnPriceList($bookId)
 {
   # code...
   $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
 
  
   $strings = "SELECT Price.price_id,Price.price,Price.buying_price,
   Price.quality_id,Price.book_id,Quality.quality_category
   FROM Price,Book,Quality
   WHERE Price.book_id = Book.book_id
   AND Price.quality_id = Quality.quality_id
   AND Book.book_id = '".$bookId."'";    
   
   $result = $conn->prepare($strings);
   $result->execute();
   $result->bind_result($PriceId,$SellingPrice,$BuyingPrice,$QualityId,$BookId,$QualityCatagory);
   $PriceListOfBooks = array();
  
   while($result->fetch()) {   
     $temp = array();    
     $temp["PriceId"] = $PriceId;
     $temp["SellingPrice"] = $SellingPrice;
     $temp["BuyingPrice"] = $BuyingPrice;
     $temp["QualityId"] = $QualityId;
     $temp["BookId"] = $BookId;
     $temp["QualityCatagory"] = $QualityCatagory;
     array_push($PriceListOfBooks, $temp);
           
   }
   $result->close();
     
     return $PriceListOfBooks;
 
 }


 function ReturnUniversity($bookId)
 {
   # code...
   $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
 
  
   $strings = "SELECT University.university_id,University.name,University.abbreviation 
   FROM University,BookSemester,Book 
   WHERE University.university_id = BookSemester.university_id 
   AND BookSemester.book_id = Book.book_id 
   AND Book.book_id = '".$bookId."'";    
    
    $result = $conn->prepare($strings);
    $result->execute();
    $result->bind_result($UniversityId,$UniversityName,$UniversityAbbreviation);
    $University = array();
   
    while($result->fetch()) {   
      $temp = array();    
      $temp["UniversityId"] = $UniversityId;
      $temp["UniversityName"] = $UniversityName;
      $temp["UniversityAbbreviation"] = $UniversityAbbreviation;
      array_push($University, $temp);
            
    }
   
   
    $result->close();
     
     return $University;
 
 }



 function ReturnIndividualBookInfo($bookId)
 {
   # code...
   $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
 
  
   $strings = "SELECT Book.name
    FROM Book
    WHERE Book.book_id = '".$bookId."'";    
    
    $result = $conn->prepare($strings);
    $result->execute();
    $result->bind_result($BookName);
    $AllBookInformationList = array();
   //echo($BookName);
   //echo("\n");
    while($result->fetch()) {  
    $AllBookInformationList["BookId"] = $bookId;
    $AllBookInformationList["BookName"] = $BookName;
    array_push($AllBookInformationList, ReturnAuthor($bookId));
    array_push($AllBookInformationList, ReturnBookEdition($bookId));
    array_push($AllBookInformationList, ReturnTags($bookId));
    array_push($AllBookInformationList, ReturnSemister($bookId));
    array_push($AllBookInformationList, ReturnDepartment($bookId));
    array_push($AllBookInformationList, ReturnUniversity($bookId));
    array_push($AllBookInformationList, ReturnPriceList($bookId));
      
      
    }
    $result->close();
     
     return $AllBookInformationList;
 
 }

 






 $app->get('/AllBookDataStore', function() use ($app)  {
 
 

  // $conn = new mysqli("localhost", "testing", "kolpobd", "kolpobdc_site");
  //$conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
   $conn = new mysqli("localhost", "root", "", "kolpobdc_bookstore");
  
  $strings = "SELECT * FROM book
  ORDER BY book.book_id";    
  
  $resultX = $conn->prepare($strings);
  $resultX->execute();
  $resultX->bind_result($book_id,$book_name,$publisher,$author,$buy_price,$sell_price,$genre,$description,$img_link,$pdf_link);
  $booklist = array();
 
  while($resultX->fetch()) {
      
      $temp  =array();
      //$booklist["total"] = $book_id;
      $temp["book_id"] = $book_id;
      $temp["book_name"] = $book_name;
      $temp["publisher"] = $publisher;
      $temp["author"] = $author;
      $temp["buy_price"] = $buy_price;
      $temp["sell_price"] = $sell_price;
      $temp["genre"] = $genre;
      $temp["description"] = $description;
      $temp["img_link"] = $img_link;
      $temp["pdf_link"] = $pdf_link;
      
      //echo ($temp);
      
      array_push($booklist, $temp);
            
  }
  $resultX->close();


    

 

 

echoRespnse(200,$booklist); 


       
          
});














/*

SELECT book.name , author.author_name , quality.quality_category,price.price,price.price_id,price.quality_id,quality.quality_id
FROM book,bookauthor,author,price,quality
where book.book_id = bookauthor.book_id
and bookauthor.author_id = author.author_id
and price.book_id = book.book_id
and price.quality_id = quality.quality_id
GROUP BY price.quality_id
ORDER BY price.price_id





SELECT book.name , author.author_name , quality.quality_category,price.price,price.price_id,price.quality_id,quality.quality_id
FROM book,bookauthor,author a1,price,quality
where book.book_id = bookauthor.book_id
and bookauthor.author_id = author.author_id
and price.book_id = book.book_id
and price.quality_id = quality.quality_id
and price.price_id IN
(
	SELECT price.price_id
    FROM price
    
);





SELECT Book.name , Author.author_name , Quality.quality_category,Price.price,Price.price_id,Price.quality_id,Quality.quality_id
 FROM Book,BookAuthor,Author,Price,Quality where Book.book_id = BookAuthor.book_id 
 and BookAuthor.author_id = Author.author_id and Price.book_id = Book.book_id and Price.quality_id = Quality.quality_id ORDER BY Price.price_id

*/




// $app->post('/answerDownVotefaz', function() use ($app)  {

//      $ansid = $app->request->post('ansid');
//   $upvote_uid = $app->request->post('upvote_uid');
//      $flag = $app->request->post('flag');
//   $conn = new mysqli("localhost", "root", "aquarium201", "online_sohopathi");
//     $strings="11";
//         if($flag==1){
//             $strings ="CALL DOWNVOTE_TRACK("."'".$ansid."','".$upvote_uid."')";
//         }
//         else{
//             $strings ="CALL ANS_DOWNVOTE_TRACE("."'".$ansid."','".$upvote_uid."')";
//         }
//     //  $strings = "UPDATE answers SET upvote=upvote+1 WHERE answer_id =". "'".$ansid."'";    
//       $result = $conn->query($strings);
//       //$result->close();
//  echoRespnse(201,$strings);
      
    
    
    
// });




// $app->post('/viewanswerscount', function() use ($app)  {

//      $meid = $app->request->post('meid');
//   $conn = new mysqli("localhost", "root", "aquarium201", "online_sohopathi");
  
//   $strings="SELECT count(*),IFNULL((SELECT SUM(B.upvote) FROM answers A JOIN answer_vote B ON B.answer_id=A.answer_id group by A.userid HAVING L.userid=A.userid),0) As likeno,IFNULL((SELECT SUM(B.downvote) FROM answers A JOIN answer_vote B ON B.answer_id=A.answer_id group by A.userid HAVING L.userid=A.userid),0) As dislikeno ,(SELECT FIND_IN_SET( points,(SELECT GROUP_CONCAT( points ORDER BY points DESC ) FROM LeaderBoard ) ) FROM LeaderBoard WHERE userid=L.userid)AS rank ,(SELECT points FROM LeaderBoard where userid=L.userid)As pnts, (SELECT userpic FROM `questions` WHERE userid=". "'".$meid."'"."ORDER by id DESC LIMIT 1
//   ) As fbpic, (SELECT count(*) from questions  WHERE userid=". "'".$meid."'".")As queCount from answers L where userid=". "'".$meid."'";        
//       $result = $conn->prepare($strings);
//   $result->execute();
//   $result->bind_result($ansCount,$likeno,$dislikeno,$rank,$points,$fbpic,$queCount);
//   $posts = array();
//   while($result->fetch()) {       
//       $tmp = array();
//           $tmp["ansCount"] = $ansCount;
//             $tmp["likeno"] = $likeno;
//       $tmp["dislikeno"] = $dislikeno;
//       $tmp["rank"] = $rank;
//       $tmp["points"] = $points;
//       $tmp["fbpic"] = $fbpic;
//       $tmp["queCount"] = $queCount;
      
//           array_push($posts, $tmp); 
           
//        }
    
     
//      $result->close();
//         echoRespnse(201,$posts);
    
// });


function echoRespnse($status_code, $response)
{
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

$app->run();

?>