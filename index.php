<?php
//request part//
$question = NULL;
$person = NULL;
//if he presses the button the values will change
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $question = $_POST["question"];
    $person = $_POST["person"];
}
//people part//
$people_info = file_get_contents("people.json");
//converts json file details into an array
$decoded_people_info = json_decode($people_info, true);
if($person == NULL){
    $en_name = array_rand($decoded_people_info);
}
else{
    $en_name = $person;
}
$fa_name = $decoded_people_info[$en_name];
//messages part//
$empty_msg = "سوال خود را بپرس!";
$messages = file_get_contents("messages.txt");
//using explode to extract each line
$each_msg = explode("\n", $messages);
//hash will convert two variables into num and substr takes a part of it 
//and hexdec converts it into decimal
$msg = $each_msg[hexdec(substr(hash('gost-crypto',$question.$person),0,15))%count($each_msg)];
//preg match will find whether the value is used in string or not (we should put \ for escape key)
if(!preg_match("/آیا/i", $question) || (!preg_match("/\?/i", $question) && !preg_match("/\؟/i", $question))){
    $msg = 'سوال درستی پرسیده نشده';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>
<body>
<p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
<div id="wrapper">
    <div id="title">
        <?php if($question != NULL) { ?>
        <span id="label">پرسش:</span>
        <span id="question"><?php echo $question ?></span>
    <?php } ?>
    </div>
    <div id="container">
        <div id="message">
            <p><?php 
            if($question == NULL){
                echo $empty_msg;
            }
            else{
                echo $msg;
            } 
            ?></p>
        </div>
        <div id="person">
            <div id="person">
                <img src="images/people/<?php echo "$en_name.jpg" ?>"/>
                <p id="person-name"><?php echo $fa_name ?></p>
            </div>
        </div>
    </div>
    <div id="new-q">
        <form method="post">
            سوال
            <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..."/>
            را از
            <select name="person"><?php
            foreach($decoded_people_info as $key => $value) {
                if($key == $en_name) {?>
			    <option value="<?php echo $key; ?>" selected><?php echo $value ?></option><?php 
                } 
                else{
                    ?><option value="<?php echo $key; ?>"><?php echo $value ?></option><?php 
                }
            }
            ?></select>
            <input type="submit" value="بپرس"/>
        </form>
    </div>
</div>
</body>
</html>